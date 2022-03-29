<?php

/**
 * The main template file
 *
 * Template Name: Vacatures
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage WP_Lemon
 * @since 2.1.3
 */

use Timber\PostQuery;

$context = Timber\Timber::get_context();
$timber_post = Timber\Timber::get_post();
$context['post'] = $timber_post;

$archive_query_args = [
   'post_type'      => 'job',
   'post_status'    => 'publish',
   'facetwp' => true,
   'posts_per_page' => 9,
];

$context['jobs']  =  new PostQuery($archive_query_args);

$context['facets'] = [
   [
      'label' => __('Location', 'wp-lemon'),
      'shortcode' => '[facetwp facet="location"]',
   ],
   [
      'label' => __('Groepen', 'wp-lemon'),
      'shortcode' => '[facetwp facet="groups"]',
   ],
   [
      'label' => __('Type baan', 'wp-lemon'),
      'shortcode' => '[facetwp facet="type_job"]',
   ],
   [
      'label' => __('Aantal uur', 'wp-lemon'),
      'shortcode' => '[facetwp facet="hours"]',
   ]
];
$context['pager'] =  apply_shortcodes('[facetwp facet="pager"]');
Timber\Timber::render('templates/jobs.twig', $context);
