<?php

/**
 * Custom fields
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Controllers;

add_action('acf/save_post', __NAMESPACE__ . '\\maybe_update_geocode');

/**
 * When a post is saved, update the geocode if empty.
 *
 * @param int $post_id the post id of the updated post.
 * @return void
 */
function maybe_update_geocode($post_id)
{
	$type = get_field('location', $post_id);
	$parent_id = $post_id;

	if ('own' !== $type) {
		$parent_id = get_field('school', $post_id);
	}

	$location = get_field('location', $parent_id);

	if (empty($location)) {
		update_field('geocode', null, $post_id);
		return;
	}

	$value = '(' . $location['lat'] . ', ' . $location['lng'] . ')';
	update_field('geocode', $value, $post_id);
}
