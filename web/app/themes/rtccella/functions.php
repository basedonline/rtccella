<?php

/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child;

use HighGround\Bulldozer\Autoloader;
use HighGround\Bulldozer\Bulldozer;

/**
 * Start loading files once wp-lemon is completely loaded.
 *
 * @return void
 */
function parent_loaded()
{
	$autoloader = new Autoloader();
	$autoloader->child(['models', 'blocks', 'controllers']); // order in which our folders get autoloaded.

	$includes = [
		'library/child-setup.php',
		'library/classes/class-subscribe-weekly-job-alerts.php',
		'library/classes/class-unsubscribe-weekly-job-alerts.php',
		'library/classes/class-send-weekly-job-alerts.php',
		'library/classes/class-modal.php',
		'library/classes/class-mailer.php',
		'library/hooks.php',
	];

	foreach ($includes as $file) {
		$filepath = locate_template($file);
		if (!$filepath) {
			/* translators: %s: file to include */
			Bulldozer::frontend_error(sprintf(__('Error locating %s for inclusion', 'wp-lemon'), $file));
		}
		require_once $filepath;
	}
	unset($file, $filepath);
}
add_action('parent_loaded', __NAMESPACE__ . '\\parent_loaded');

function logger(string $prefix,  string $type, string $log_message)
{
	error_log('[' . date('d-m-Y H:i') . '] ' . $prefix . ' 🡆  ' . $type . ' 🡆 ' . $log_message . PHP_EOL, 3, ABSPATH . '../../application.log');
}
