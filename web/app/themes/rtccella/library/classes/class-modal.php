<?php

/**
 * Mailerlist class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Classes;

use Timber\Timber;

Modal::register();

class Modal
{

   /**
    * Action hook used by the AJAX class.
    *
    * @var string
    */
   const ACTION = 'load_modal';

   /**
    * Action argument used by the nonce validating the AJAX request.
    *
    * @var string
    */
   const NONCE = 'archive_ajax';

   /**
    * Incoming parameters
    *
    * These are sent during the Ajax call.
    *
    * @var string
    */
   public $parameters = '';

   public string $type;

   /**
    * Handle the registration of the current class.
    *
    * @return void
    */
   public static function register()
   {
      $handler = new self();

      add_action('wp_ajax_' . self::ACTION, [$handler, 'handle']);
      add_action('wp_ajax_nopriv_' . self::ACTION, [$handler, 'handle']);
   }


   /**
    * Hook into the wp_ajax actions
    *
    * @return bool
    */
   public function handle()
   {
      // Make sure we are getting a valid AJAX request.
      check_ajax_referer(self::NONCE, 'nonce');

      if (empty($_POST['parameters'])) {
         return false;
      }

      $params           = sanitize_text_field(wp_unslash($_POST['parameters']));
      $this->parameters = json_decode($params, true);

      $this->query();
      return true;
   }


   /**
    * Run the query
    *
    * @return void
    */
   public function query()
   {
      $context = Timber::get_context();
      $this->rendered_html = Timber::compile('partials/modal/newsletter.twig', $context);


      wp_send_json(
         [
            'response' => 'success',
            'title'    => 'Schrijf je in voor vacature alerts.',
            'content'  => $this->rendered_html,
         ]
      );
   }
}
