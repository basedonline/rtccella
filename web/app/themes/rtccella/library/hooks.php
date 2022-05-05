<?php

/**
 * A place where you can put your actions and filters for wp-lemon and plugins.
 *
 * @link https://studio-lemon.github.io/wp-lemon-docs/expand/hooks
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child;

use Timber\Timber;

add_action(
	'wp-lemon/action/entry/before',
	function () {

		if (is_singular('story')) {
			return;
		}

		if (is_singular('news')) {
			return;
		}
		if (is_singular('job')) {
			return;
		}

		$intro = get_field('intro', get_the_ID());

		if (empty($intro['title'])) {
			return;
		}

		$context = Timber::get_context();
		$context = [
			'intro' => $intro,
			'search' => 'job' === get_post_meta(get_the_ID(), '_archive_page', true) ? true : false,
		];

		Timber::render('partials/intro.twig', $context);
	}
);

add_action(
	'wp-lemon/action/footer/before',
	function () {
		$context = Timber::get_context();
		Timber::render('partials/footer/footer-before.twig', $context);
	}
);


add_action(
	'wp-lemon/action/body/after',
	function () {
		$context = Timber::get_context();
		Timber::render('partials/modal/modal.twig', $context);
	}
);

add_action(
	'wp-lemon/action/footer-widgets/before',
	function () {
		$context = Timber::get_context();
		Timber::render('partials/footer/footer-logobar.twig', $context);
	}
);

add_action(
	'wp-lemon/action/footer-widgets/after',
	function () {
		echo '<div class="footer__bottom-shapes"><div class="footer__white footer__shape"></div><div class="footer__light-blue footer__shape"></div></div>';
	}
);


add_filter(
	'wp-lemon/filter/copyright-message',
	function ($message) {
		$context = Timber::get_context();
		return Timber::compile('partials/footer/footer-bottom.twig', $context);
	}
);

add_filter(
	'wp-lemon/filter/card/content/footer',
	function ($content, $id, $fields) {
		$context = Timber::get_context();
		$context['fields'] = $fields;
		return Timber::compile('components/cards/card-footer.twig', $context);
	},
	10,
	3
);


add_filter(
	'wp-lemon/filter/footer/show-logo',
	function () {
		return false;
	}
);


add_filter(
	'body_class',
	function ($classes) {
		$extra_classes = [];

		if (!is_singular('page') && !is_singular('job')) {
			return $classes;
		}

		$intro = get_field('intro', get_the_ID());

		!empty($intro['title']) || is_singular('job') ? $extra_classes[] = 'has-intro' : null;

		return array_merge($classes, $extra_classes);
	}
);


/**
 * Update job posttype.
 *
 * @param array  $args posttype arguments.
 * @param string $post_type posttype name.
 * @return array updated args.
 */
function custom_post_type_args($args, $post_type)
{
	if ('job' == $post_type) {
		$args['enable_latest_block'] = false;
	}

	return $args;
}
add_filter('register_post_type_args', __NAMESPACE__ . '\\custom_post_type_args', 20, 2);
