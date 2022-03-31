<?php

/**
 * Mailerlist class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Classes;

use Routes;
use Timber\PostQuery;
use Timber\Timber;
use WP_Lemon\Classes\Mail;

use function WP_Lemon\Child\logger;

SendWeeklyJobAlerts::register();
date_default_timezone_set('Europe/Amsterdam');
class SendWeeklyJobAlerts
{
   const LOG_TYPE = 'weekly-job-alerts';
   const TIME = '17:18:00';
   /**
    * Handle the registration of the current class.
    *
    * @return void
    */
   public static function register()
   {
      $handler = new self();
      add_action('rtc_initiate_mails', array($handler, 'initiate_mails'));
      add_action('init',  [$handler, 'initiate_mails']);
      dd_action('init', [$this, 'unsubscribe_endpoint']);
   }

   function add_cron()
   {
      wp_schedule_event(strtotime(self::TIME), 'daily', 'rtc_initiate_mails', $args = array());
   }

   public static function initiate_mails()
   {
      if (date('l') !== 'Tuesday') {
         //return;
      }

      $args        = array(
         'posts_per_page'    => -1,
         'post_type'         => 'job',
         'post_status'       => array('publish'),
         'date_query'        => array(
            'after'        => 'previous week Tuesday',
         )
      );
      $jobs = new PostQuery($args);
      if (empty($jobs)) {
         logger(__METHOD__, self::LOG_TYPE, 'No jobs found');
         return;
      }

      $args = [
         'post_type' => 'subscribers',
         'post_status' => 'publish',
         'posts_per_page' => -1,
      ];

      $subscribers = get_posts($args);

      if (empty($subscribers)) {
         logger(__METHOD__, self::LOG_TYPE, 'No subscribers found');
         return;
      }

      foreach ($subscribers as $subscriber) {
         $email = get_the_title($subscriber->ID);
         $name = get_field('name', $subscriber->ID);
         $title = 'De nieuwe vacatures op RTC Cella';
         $unsubscribe_link = sha1($email . $name);
         $message = Timber::compile('mail/weekly-job-alert.twig', [
            'jobs'        => $jobs,
            'name'        => $name,
            'email'       => $email,
            'unsubscribe' => get_home_url(null, 'afmelden') . '?hash=' . $unsubscribe_link,
         ]);

         //new Mail($email, $title, $message, __METHOD__);
      }
   }

   // TODO: Afmelden functie afmaken.
   public function unsubscribe_endpoint()
   {
      Routes::map('afmelden', function ($params) {
         Routes::load('templates/afmelden.php', null, false);
      });
   }
}
