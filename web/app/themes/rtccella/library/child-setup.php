<?php

/**
 * The starting point for the your child theme.
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child;

use HighGround\Bulldozer\Site_Icons;

use function DeliciousBrains\WPMDB\Container\DI\value;

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

	register_nav_menus(
		[
			'home_menu' => __('Home menu', 'wp-lemon-child'),
		]
	);

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

	$home_nav = wp_nav_menu(
		[
			'echo'           => false,
			'theme_location' => 'home_menu',
			'menu_class'     => 'home-menu nav',
			'depth'          => 1,
			'container'      => false,
		]
	);
	$context['nav']['home'] = $home_nav;

	return $context;
}
add_filter('timber_context', __NAMESPACE__ . '\\child_context');

/**
 * Setup Site icons and manifest.
 */
$icons                   = new Site_Icons();
$icons->short_name       = 'RTC Cella';
$icons->background_color = '#f0f0f0';       // must be hex color.
$icons->theme_color      = '#0090df';       // must be hex color.
