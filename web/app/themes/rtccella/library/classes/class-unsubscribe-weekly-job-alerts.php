<?php

/**
 * Mailerlist class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Classes;

use Routes;

new ManageWeeklyJobAlerts();

/**
 * Manage the weekly alerts.
 */
class ManageWeeklyJobAlerts
{
	/**
	 * The constructor.
	 */
	public function __construct()
	{
		add_action('init', [$this, 'unsubscribe_endpoint']);
	}

	/**
	 * Set up the unsubscribe endpoint.
	 */
	public function unsubscribe_endpoint()
	{
		Routes::map(
			'nieuwsbrief/:action/:hash',
			function ($params) {

				Routes::load('nieuwsbrief.php', $params, false);
			}
		);
	}
}
