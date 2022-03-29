<?php

/**
 * Mailerlist class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Classes;

class JobAlert
{
   protected $settings = [
      'page_name' => 'Newsletter list',
      'permission' => 'edit_pages',
      'page_slug' => 'newsletter-list',
      'success_message' => 'success'
   ];

   public function __construct()
   {
      add_action('init', [$this, 'register_post_type']);
   }


   public function register_post_type()
   {
      register_post_type('subscribers', [
         'labels' => [
            'name' => __('Vacature alerts'),
            'singular_name' => __('Vacature alert')
         ],
         'supports' => ['title'],
         'public' => false,
         'has_archive' => false,
         'show_ui' => true,
         'show_in_menu' => true,
      ]);
   }

   public function initEndPoint()
   {
      register_rest_route('/api', '/newsletter-list', [
         'methods' => 'POST',
         'callback' => function ($request) {
            $request = $request->get_params();

            $this->add_subscriber($request['email'], $request['name']);

            return $this->settings['success_message'];
         },
      ]);
   }

   private function add_subscriber($email, $name)
   {
      $post_id = wp_insert_post([
         'post_type' => 'subscribers',
         'post_title' => $email,
         'post_status' => 'publish'
      ]);

      return $post_id;
   }
}

new JobAlert();

add_filter('manage_subscribers_posts_columns',  __NAMESPACE__ . '\\subscribers_table_head');
function subscribers_table_head($defaults)
{
   $defaults['title']  = 'E-mail';
   $defaults['name']   = 'Naam';

   unset($defaults['date']);
   return $defaults;
}

add_action('manage_subscribers_posts_custom_column', __NAMESPACE__ . '\\subscribers_table_content', 10, 2);

function subscribers_table_content($column_name, $post_id)
{

   if ($column_name == 'name') {
      $name = get_post_meta($post_id, 'name', true);
      echo $name;
   }
}
