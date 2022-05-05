<?php

/**
 * ACF Block declaration
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Blocks;

use HighGround\Bulldozer\BlockRenderer;

/**
 * Example block that can be copied for making extra blocks.
 * Change the VideoCard_Block class on rule 11 and below the class and uncomment the class call.
 * Don't include the render callback in the block register since we add that in our main BlockRenderer abstract class.
 */
class VideoCard_Block extends BlockRenderer
{


	/**
	 * Register a new ACF Block.
	 *
	 * The array is passed to the acf_register_block_type() function that registers the block with ACF.
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
	 * @return array
	 */
	public function block_register(): array
	{
		$block = [
			'name'        => 'video-card',
			'title'       => __('video kaart', 'wp-lemon-child'),
			'description' => __('toont video + titel', 'wp-lemon-child'),
			'category'    => 'wp-lemon-blocks',
			'icon'        => 'format-video',
			'keywords'    => [
				_x('These', 'Block keyword', 'wp-lemon-child'),
				_x('Are', 'Block keyword', 'wp-lemon-child'),
				_x('The', 'Block keyword', 'wp-lemon-child'),
				_x('Keywords', 'Block keyword', 'wp-lemon-child'),
			],
			'supports'    => [
				'mode'  => false,
				'align' => false,
				'jsx'   => true,
			],
		];
		return $block;
	}


	/**
	 * Extend the base context of our block.
	 * With this function we can add for video-card a query or
	 * other custom content.
	 *
	 * @param array $context      Holds the block data.
	 * @return array  $context    Returns the array with the extra content that merges into the original block context.
	 */
	public function block_context($context): array
	{
		$this->classes[] = 'wp-embed-aspect-16-9 wp-has-aspect-ratio';
		// $allowed_blocks  = apply_filters("wp-lemon/filter/blocks/{$this->slug}/allowed-blocks", ['core/heading', 'core/paragraph']);

		$args = [
			// 'InnerBlocks'     => '<InnerBlocks allowedBlocks="' . esc_attr(wp_json_encode($allowed_blocks)) . '" />',
		];

		return array_merge($context, $args);
	}


	/**
	 * Register fields to the block.
	 *
	 * The array is passed to the acf_register_block_type() function that registers the block with ACF.
	 *
	 * @link https://github.com/StoutLogic/acf-builder
	 * @return FieldsBuilder
	 */
	public function add_fields(): object
	{
		$this->registered_fields
			->addText(
				'title',
				[
					'label' => 'titel',
					'instructions' => 'dit wordt de titel onder de video',
					'required' => 1,
				]
			)
			->addOembed(
				'video',
				[
					'label' => 'video',
					'instructions' => 'dit wordt de video',
					'required' => 1,
				]
			);

		return $this->registered_fields;
	}
}

/**
 * Enable the class
 */
new VideoCard_Block();
