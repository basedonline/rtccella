<?php

/**
 * The starting point for the your child theme.
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child;

use HighGround\Bulldozer\Site_Icons;

/**
 * Theme initialize
 *
 * Fires on website load after the parent theme is loaded.
 *
 * - default text Domain
 * - navigation menus
 * - theme support
 *
 * @since 2.0
 * @return void
 */
function theme_initialize()
{
	load_child_theme_textdomain('wp-lemon-child', get_stylesheet_directory() . '/resources/languages/');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\theme_initialize');

/**
 * Extend or overwrite parent context.
 *
 * @param array $context the existing context we are going to extend.
 * @since 2.0.0
 *
 * @return array
 */
function child_context($context)
{
	$logo_overwrite = [
		'header' => [
			'width' => '140',
			'height' => '60',
		],
		'footer' => [
			'width' => '260',
			'height' => '91',
		],
	];
	$context['logo'] = array_replace_recursive($context['logo'], $logo_overwrite);

	return $context;
}
add_filter('timber_context', __NAMESPACE__ . '\\child_context');

/**
 * Setup Site icons and manifest.
 */
$icons                   = new Site_Icons();
$icons->short_name       = 'Short name';
$icons->background_color = '#f7d600';       // must be hex color.
$icons->theme_color      = '#f7d600';       // must be hex color.
