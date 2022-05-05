<?php

/**
 * Mailerlist class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Classes;

new SubscribeWeeklyJobAlerts();


/**
 * Manage the weekly alerts.
 */
class SubscribeWeeklyJobAlerts
{
	/**
	 * The constructor.
	 */
	public function __construct()
	{
		add_action('init', [$this, 'register_post_type']);
		add_action('rest_api_init', [$this, 'register_rest_routes']);
	}

	/**
	 * Sets up the post type.
	 */
	public function register_post_type()
	{
		register_post_type(
			'subscribers',
			[
				'labels' => [
					'name' => __('Vacature alerts', 'wp-lemon-child'),
					'singular_name' => __('Vacature alert', 'wp-lemon-child'),
				],
				'supports' => ['title'],
				'public' => false,
				'has_archive' => false,
				'show_ui' => true,
				'show_in_menu' => true,
			]
		);
	}

	/**
	 * Registers rest route to unsubscribe.
	 *
	 * @return void
	 */
	public function register_rest_routes()
	{
		register_rest_route(
			'rtc/v1',
			'/newsletter-list',
			[
				'methods' => 'POST',
				'methods'  => \WP_REST_Server::CREATABLE,
				'permission_callback' => '__return_true',
				'callback' => function ($request) {
					$request = $request->get_body();
					$request = json_decode($request, true);
					$email = sanitize_email($request['email']);
					$name = sanitize_text_field($request['name']);
					$result = $this->add_subscriber($email, $name);

					return rest_ensure_response($result);
				},
			]
		);
	}

	/**
	 * Add subscriber to the list when endpoint is called.
	 *
	 * @param string $email The email address.
	 * @param string $name The name.
	 * @return string The result.
	 */
	private function add_subscriber($email, $name)
	{
		if (!is_admin()) {
			require_once(ABSPATH . 'wp-admin/includes/post.php');
		}
		$title = sanitize_title($email);

		if (post_exists($email)) {
			return 'email_exists';
		}

		$post_id = wp_insert_post(
			[
				'post_type'   => 'subscribers',
				'post_name'   => $title,
				'post_title'  => $email,
				'post_status' => 'publish',
			]
		);
		update_field('name', $name, $post_id);
		return 'success';
	}
}

/**
 * Updates the table head of the backend.
 *
 * @param array $columns the default columns.
 * @return array the updated defaults.
 */
function subscribers_table_head($columns)
{
	$columns['title']  = 'E-mail';
	$columns['name']   = 'Naam';

	unset($columns['date']);
	return $columns;
}


/**
 * Updates the content of the backend.
 *
 * @param string $column_name the column we are changing.
 * @param int    $post_id the id.
 * @return void
 */
function subscribers_table_content($column_name, $post_id)
{

	if ('name' == $column_name) {
		$name = get_post_meta($post_id, 'name', true);
		echo esc_html($name);
	}
}
add_filter('manage_subscribers_posts_columns', __NAMESPACE__ . '\\subscribers_table_head');
add_action('manage_subscribers_posts_custom_column', __NAMESPACE__ . '\\subscribers_table_content', 10, 2);
