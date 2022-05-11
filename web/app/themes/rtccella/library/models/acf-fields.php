<?php

/**
 * Custom fields
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Models;

use StoutLogic\AcfBuilder\FieldsBuilder;


add_filter(
	'wp-lemon/filter/model/acf-fields/job',
	function ($fields) {
		$fields
			->removeField('salary')
			->removeField('extra')
			->removeField('location')
			->removeField('contract');

		$fields
			->addRadio(
				'location_type',
				[
					'label' => __('Locatie', 'wp-lemon-child'),
					'choices' => [
						'school' => __('Kies school', 'wp-lemon-child'),
						'own'    => __('Eigen locatie kiezen', 'wp-lemon-child'),
					],
				]
			)
			->addPostObject(
				'school',
				[
					'label' => 'School',
					'instructions' => '',
					'required' => 1,
					'post_type' => ['school'],
					'return_format' => 'id',
					'ui' => 1,
				]
			)
			->conditional('location_type', '==', 'school')
			->addGoogleMap(
				'location',
				[
					'label' => 'Locatie',
					'instructions' => '',
					'required' => 1,
				]
			)
			->conditional('location_type', '==', 'own')
			->addEmail('apply_mailaddress', [
				'label' => 'E-mailadres voor sollicitatie',
				'instructions' => '',
				'required' => 1,
			])
			->conditional('location_type', '==', 'own')
			->addCheckbox(
				'groups',
				[
					'label' => __('Groep', 'wp-lemon-child'),
					'allow_null' => 1,
					'wrapper' => [
						'width' => '50',
					],
					'choices' => [
						'onderbouw' => __('Onderbouw', 'wp-lemon-child'),
						'middenbouw' => __('Bovenbouw', 'wp-lemon-child'),
						'bovenbouw' => __('Bovenbouw', 'wp-lemon-child'),
					],
				]
			)
			->addCheckbox(
				'job_type',
				[
					'label' => __('Type baan', 'wp-lemon-child'),
					'allow_null' => 1,
					'wrapper' => [
						'width' => '50',
					],
					'choices' => [
						'leerkracht'    => __('Leerkracht', 'wp-lemon-child'),
						'vervangend'    => __('Vervangend', 'wp-lemon-child'),
						'oop'           => __('Onderwijs Ondersteunend Personeel', 'wp-lemon-child'),
						'ib'            => __('IB\'er', 'wp-lemon-child'),
						'lio'           => __('Lio\'er', 'wp-lemon-child'),
						'directeur'     => __('Directeur', 'wp-lemon-child'),
						'zij-instromer' => __('Zij-instromer', 'wp-lemon-child'),
					],
				]
			)
			->addRange(
				'hours',
				[
					'label' => __('Aantal uur', 'wp-lemon-child'),
					'min' => 1,
					'max' => 40,
					'step' => 1,
				]
			)
			->addText(
				'geocode',
				[
					'label'        => __('Coördinaten', 'wp-lemon-child'),
					'instructions' => 'Dit veld wordt automatisch geupdatet bij aanpassing van de school of locatie.',
					'disabled' => 1,
				]
			);
		return $fields;
	}
);



add_action(
	'acf/init',
	function () {
		/**
		 * Extra fields for jobs.
		 */
		$school_fields = new FieldsBuilder(
			'school',
			[
				'title' => __('Scholen', 'wp-lemon-child'),
				'style' => 'seamless',
			]
		);
		$school_fields
			->addEmail('apply_mailaddress', [
				'label' => 'E-mailadres voor sollicitatie',
				'instructions' => 'Naar welk E-mailadres wordt de sollicitatie verstuurd?',
				'required' => 1,
			])
			->addGoogleMap(
				'location',
				[
					'label' => 'Locatie',
					'instructions' => '',
					'required' => 1,
				]
			);

		$school_fields->setLocation('post_type', '==', 'school');
		acf_add_local_field_group($school_fields->build());

		/**
		 * Extra fields for jobs.
		 */
		$school_fields = new FieldsBuilder(
			'subscriber',
			[
				'title' => __('Vacature alerts', 'wp-lemon-child'),
				'style' => 'seamless',
			]
		);
		$school_fields
			->addText(
				'name',
				[
					'label' => 'Naam',
					'instructions' => '',
					'required' => 1,
				]
			);

		$school_fields->setLocation('post_type', '==', 'subscribers');
		acf_add_local_field_group($school_fields->build());
	}
);
