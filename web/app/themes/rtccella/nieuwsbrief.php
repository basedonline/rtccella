<?php

/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage WP_Lemon
 * @since 2.0.0
 */

use Timber\Timber;

global $params;
$job_action = $params['action'];
$hash = $params['hash'];
$context         = Timber::get_context();
$timber_post     = Timber::get_post();

if ('uitschrijven' == $job_action) {
	$context['title'] = 'Uitschrijven';
	$hash = explode('-', $hash);
	$job_id = (int) $hash[0];
	$sha_hash = $hash[1];
	$email = get_the_title($job_id);
	$name = get_field('name', $job_id);
	$hash = sha1($email . $name);
	if (!get_post_status($job_id)) {
		$context['message'] = 'U bent reeds uitgeschreven voor jobalerts.';
	} elseif ($hash == $sha_hash) {
		wp_delete_post($job_id);
		$context['message'] = 'U bent uitgeschreven voor jobalerts';
	} else {
		$context['message'] = 'Uw link is niet correct';
	}
}


$context['post'] = $timber_post;
Timber::render('templates/nieuwsbrief.twig', $context);
