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
 * Change the Example_Block class on rule 11 and below the class and uncomment the class call.
 * Don't include the render callback in the block register since we add that in our main BlockRenderer abstract class.
 */
class Example_Block extends BlockRenderer
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
			'name'        => 'example',                                     // searches for resources/views/blocks/example.twig.
			'title'       => __('Example name', 'wp-lemon-child'),
			'description' => __('Example description', 'wp-lemon-child'),
			'category'    => 'wp-lemon-blocks',                             // default custom category or add new ones.
			'icon'        => 'desktop',                                     // regular dashicons without the 'dashicon-' prefix.
			'keywords'    => [
				_x('key 1', 'Block keyword', 'wp-lemon-child'),
				_x('key 2', 'Block keyword', 'wp-lemon-child'),
				_x('key 3', 'Block keyword', 'wp-lemon-child'),
			],
			'supports' => [
				'align' => false,
				'mode'  => false,
			],
		];
		return $block;
	}

	/**
	 * Extend the base context of our block.
	 * With this function we can add for example a query or
	 * other custom content.
	 *
	 * @param array $context    Holds the current block data that you'll expand in this method.
	 * @return array  $context    Returns the array with the extra content that merges into the original block context.
	 */
	public function block_context($context): array
	{

		return $context;
	}

	/**
	 * Register fields via acfbuilder
	 *
	 * @link https://github.com/StoutLogic/acf-builder
	 * @return object
	 */
	public function add_fields(): object
	{
		return $this->registered_fields;
	}
}

/**
 * Enable the class
 */
// new Example_Block();
