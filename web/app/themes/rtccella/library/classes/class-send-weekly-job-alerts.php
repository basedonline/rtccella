<?php

/**
 * Mailerlist class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Classes;

use Timber\Timber;
use WP_Lemon\Classes\Mail;

use function WP_Lemon\Child\logger;

SendWeeklyJobAlerts::register();

/**
 * The class that initiates the weekly job alerts.
 */
class SendWeeklyJobAlerts
{
	/**
	 * Log name
	 */
	const LOG_TYPE = 'weekly-job-alerts';

	/**
	 * Time to sent the mails.
	 */
	const TIME = '17:00';


	/**
	 * Handle the registration of the current class.
	 *
	 * @return void
	 */
	public static function register()
	{
		$handler = new self();
		add_action('init', [$handler, 'add_cron']);
		add_action('rtc_initiate_mails', [$handler, 'mail_job']);
	}

	/**
	 * Add the cron job.
	 *
	 * @return void
	 */
	public function add_cron()
	{
		if (!wp_next_scheduled('rtc_initiate_mails')) {
			$date = new \DateTime();
			$date->modify('next monday ' . self::TIME);
			$timestamp = $date->getTimestamp();
			wp_schedule_event($timestamp, 'weekly', 'rtc_initiate_mails', $args = []);
		}
	}

	/**
	 * Initiate the mails.
	 *
	 * @return void
	 */
	public static function mail_job()
	{
		$args        = [
			'posts_per_page'    => -1,
			'post_type'         => 'job',
			'post_status'       => ['publish'],
			'date_query'        => [
				'after'        => 'previous week Tuesday',
			],
		];

		$jobs = Timber::get_posts($args);

		if (empty($jobs)) {
			logger(__METHOD__, self::LOG_TYPE, 'No jobs found posted in the last week.');
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
			$title = 'De nieuwste vacatures op RTC Cella';
			$unsubscribe_link = sha1($email . $name);
			$message = Timber::compile(
				'mail/weekly-job-alert.twig',
				[
					'jobs'        => $jobs,
					'name'        => $name,
					'email'       => $email,
					'unsubscribe' => get_home_url(null, 'nieuwsbrief/uitschrijven/') . $subscriber->ID . '-' . $unsubscribe_link,
				]
			);

			new Mail($email, $title, $message, __METHOD__);
		}
	}
}
