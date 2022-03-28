<?php

namespace WP_Lemon\Child\Models;

function cpt_school()
{
    $labels = array(
        'name'                  => _x('Scholen', 'Post Type General Name', 'wp-lemon'),
        'singular_name'         => _x('School', 'Post Type Singular Name', 'wp-lemon'),
        'menu_name'             => __('Scholen', 'wp-lemon'),
        'name_admin_bar'        => __('Scholen', 'wp-lemon'),
        'add_new_item'          => __('Nieuwe school', 'wp-lemon'),
        'add_new'               => __('Nieuwe school', 'wp-lemon'),
        'edit_item'             => __('Bewerk school', 'wp-lemon'),
        'update_item'           => __('Update school', 'wp-lemon'),
        'view_item'             => __('Bekijk school', 'wp-lemon'),
    );
    $args = [
        'label'                  => __('Scholen', 'wp-lemon'),
        'labels'                 => $labels,
        'supports'               => array('title', 'editor', 'thumbnail'),
        'taxonomies'             => array('district'),
        'hierarchical'           => false,
        'public'                 => true,
        'show_ui'                => true,
        'show_in_menu'           => true,
        'menu_position'          => 5,
        'rewrite'                => array('slug' => 'school'),
        'menu_icon'              => 'dashicons-welcome-learn-more',
        'show_in_admin_bar'      => true,
        'show_in_nav_menus'      => true,
        'show_in_rest'           => true,
        'can_export'             => true,
        'has_archive'            => false,
        'exclude_from_search'    => true,
        'publicly_queryable'     => false,
        'capability_type'        => 'post',
        'maybe_has_archive_page' => true,
        'enable_overview_block'  => true,
        'enable_latest_block'    => true,
    ];
    register_post_type('school', $args);
}
add_action('init', __NAMESPACE__ . '\\cpt_school', 0);
