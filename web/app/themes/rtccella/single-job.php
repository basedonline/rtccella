<?php

/**
 * Single template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package WordPress
 * @subpackage WP_Lemon
 * @since 2.0.0
 */

use Timber\Timber;
use function WP_Lemon\Controllers\custom_archive_page;
use function WP_Lemon\Controllers\other_items_query;
use function WP_Lemon\Controllers\share_context;

$context = Timber::get_context();
$timber_post = Timber::get_post();
$context['post'] = $timber_post;
$context['share_context'] = share_context(get_the_ID());

if (custom_archive_page(get_post_type())) {
	$archive_page_id = custom_archive_page(get_post_type());
	$context['custom_archive_page'] = get_permalink($archive_page_id);
}

$context['intro'] = [
	'subtitle' => 'Vacature',
	'jobmeta'  => true,
	'title'    => get_the_title(),
];

$context['apply_form'] = apply_shortcodes('[fluentform id="3"]');

$context['more'] = [
	'type' => 'job',
	'title' => 'Andere interessante vacatures',
	'card_type' => 'job',
	'items' => other_items_query('job', 3, get_the_ID()),
	'button' => [
		'text' => 'Alle vacatures',
		'link' => get_permalink($archive_page_id),
	],
];
Timber::render(
	[
		'templates/single-' . $timber_post->post_type . '.twig',
		'templates/single.twig',
	],
	$context
);
