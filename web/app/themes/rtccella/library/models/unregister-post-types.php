<?php

/**
 * A place where you can manage posttypes from the parent theme.
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Models;

/**
 * Unregister post types declared in the parent theme.
 *
 * @return void
 */
function unregister_post_types()
{
	// unregister_post_type('job');


}

add_action('init', __NAMESPACE__ . '\\unregister_post_types', 0);
