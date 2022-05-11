<?php

/**
 * ACF Block declaration
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Blocks;

use HighGround\Bulldozer\BlockRenderer;

use function WP_Lemon\Controllers\latest_items_query;

/**
 * Example block that can be copied for making extra blocks.
 * Change the LatestJobs_Block class on rule 11 and below the class and uncomment the class call.
 * Don't include the render callback in the block register since we add that in our main BlockRenderer abstract class.
 */
class LatestJobs_Block extends BlockRenderer
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
			'name'        => 'latest-jobs',
			'title'       => __('laatste vacatures', 'wp-lemon-child'),
			'description' => __('Toont de laatste 3 vacatures en een vacature alert card', 'wp-lemon-child'),
			'category'    => 'wp-lemon-child-blocks',
			'icon'        => 'editor-table',
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
	 * With this function we can add for latest-jobs a query or
	 * other custom content.
	 *
	 * @param array $context      Holds the block data.
	 * @return array  $context    Returns the array with the extra content that merges into the original block context.
	 */
	public function block_context($context): array
	{
		$latest_jobs = latest_items_query('job', 3);
		$context['latest_jobs'] = $latest_jobs;
		$context['card_type'] = 'jobs';
		$context['holder_classes'] = 'col-12 col-md-6 col-lg-4';
		$message = __("There aren't any jobs, so this block will be empty.", 'wp-lemon-child');

		if (empty($latest_jobs)) {
			parent::add_notification($message, 'warning');
		}

		return $context;
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

		return $this->registered_fields;
	}
}

/**
 * Enable the class
 */
new LatestJobs_Block();
