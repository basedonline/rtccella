<?php

/**
 * Custom fields
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Child\Models;

use StoutLogic\AcfBuilder\FieldsBuilder;


add_filter('wp-lemon/filter/model/acf-fields/job', function ($fields) {
   $fields
      ->removeField('salary')
      ->removeField('extra')
      ->removeField('location')
      ->removeField('contract');

   $fields
      ->addRadio('location_type', [
         'label' => __('Locatie', 'wp-lemon'),
         'choices' => [
            'school' => __('Kies school', 'wp-lemon'),
            'own'    => __('Eigen locatie kiezen', 'wp-lemon'),
         ]
      ])
      ->addPostObject('school', [
         'label' => 'School',
         'instructions' => '',
         'required' => 1,
         'post_type' => ['school'],
         'return_format' => 'id',
         'ui' => 1,
      ])
      ->conditional('location_type', '==', 'school')
      ->addGoogleMap('location', [
         'label' => 'Locatie',
         'instructions' => '',
         'required' => 1,
      ])
      ->conditional('location_type', '==', 'own')
      ->addSelect('groups', [
         'label' => __('Groep', 'wp-lemon'),
         'allow_null' => 1,
         'choices' => [
            'onderbouw' => __('Onderbouw', 'wp-lemon'),
            'middenbouw' => __('Bovenbouw', 'wp-lemon'),
            'bovenbouw' => __('Bovenbouw', 'wp-lemon'),
         ]
      ])
      ->addSelect('type', [
         'label' => __('Type baan', 'wp-lemon'),
         'allow_null' => 1,
         'choices' => [
            'leerkracht'    => __('Leerkracht', 'wp-lemon'),
            'vervangend'    => __('vervangend', 'wp-lemon'),
            'oop'           => __('Onderwijs Ondersteunend Personeel', 'wp-lemon'),
            'ib'            => __('IB\'er', 'wp-lemon'),
            'lio'           => __('Lio\'er', 'wp-lemon'),
            'directeur'     => __('Directeur', 'wp-lemon'),
            'zij-instromer' => __('Zij-instromer', 'wp-lemon'),
         ]
      ])
      ->addRange(
         'hours',
         [
            'label' => __('Aantal uur', 'wp-lemon'),
            'min' => 1,
            'max' => 40,
            'step' => 1,
         ]
      )
      ->addText('geocode', [
         'label'        => __('Coördinaten', 'wp-lemon-child'),
         'instructions' => 'Dit veld wordt automatisch geupdatet bij aanpassing van de school of locatie.',
         'disabled' => 1
      ]);
   return $fields;
});



add_action(
   'acf/init',
   function () {
      /**
       * Extra fields for jobs.
       */
      $school_fields = new FieldsBuilder(
         'school',
         [
            'title' => __('Scholen', 'wp-lemon'),
            'style' => 'seamless',
         ]
      );
      $school_fields
         ->addGoogleMap('location', [
            'label' => 'Locatie',
            'instructions' => '',
            'required' => 1,
         ]);

      $school_fields->setLocation('post_type', '==', 'school');
      acf_add_local_field_group($school_fields->build());


      /**
       * Extra fields for jobs.
       */
      $school_fields = new FieldsBuilder(
         'subscriber',
         [
            'title' => __('Vacature alerts', 'wp-lemon'),
            'style' => 'seamless',
         ]
      );
      $school_fields
         ->addText('name', [
            'label' => 'Naam',
            'instructions' => '',
            'required' => 1,
         ]);

      $school_fields->setLocation('post_type', '==', 'subscribers');
      acf_add_local_field_group($school_fields->build());
   }
);
