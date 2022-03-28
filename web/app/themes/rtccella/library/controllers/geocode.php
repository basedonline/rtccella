<?php

/**
 * Custom fields
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Controllers;

add_action('acf/save_post', __NAMESPACE__ . '\\maybe_update_geocode', 5);

function maybe_update_geocode($post_id)
{
   $fields = $_POST['acf'];
   $parent_id =  $post_id;

   if ($fields['location_type'] === 'school') {
      $parent_id = $fields['school'];
   }
   $location = get_field('location', $parent_id);

   if (empty($location)) {
      update_field('geocode', null, $post_id);
      return;
   }

   $value = '(' . $location["lat"] . ', ' . $location["lng"] . ')';
   update_field('geocode', $value, $post_id);
}
