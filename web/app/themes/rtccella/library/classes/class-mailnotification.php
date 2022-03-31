<?php

/**
 * Mailerlist class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Classes;

new JobAlert();
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
      add_action('rest_api_init', [$this, 'register_rest_routes']);
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

   public function register_rest_routes()
   {
      register_rest_route('rtc/v1', '/newsletter-list', [
         'methods' => 'POST',
         'methods'  => \WP_REST_Server::CREATABLE,
         'callback' => function ($request) {
            $request = $request->get_body();
            $request = json_decode($request, true);
            $email = sanitize_email($request['email']);
            $name = sanitize_text_field($request['name']);
            $result = $this->add_subscriber($email, $name);

            return rest_ensure_response($result);
         },
      ]);
   }

   private function add_subscriber($email, $name)
   {
      if (!is_admin()) {
         require_once(ABSPATH . 'wp-admin/includes/post.php');
      }
      $title = sanitize_title($email);

      if (post_exists($email)) {
         return 'email_exists';
      }

      $post_id = wp_insert_post([
         'post_type'   => 'subscribers',
         'post_name'   => $title,
         'post_title'  => $email,
         'post_status' => 'publish'
      ]);
      update_field('name', $name, $post_id);
      return 'success';
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
