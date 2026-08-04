<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'wp_inventor_register_digital_tags_post_type' );

function wp_inventor_register_digital_tags_post_type() {
	$labels = array(
		'name'                  => __( 'Digital Tags', 'hello-elementor-child' ),
		'singular_name'         => __( 'Digital Tag', 'hello-elementor-child' ),
		'menu_name'             => __( 'Digital Tags', 'hello-elementor-child' ),
		'name_admin_bar'        => __( 'Digital Tag', 'hello-elementor-child' ),
		'add_new'               => __( 'Add New', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Add New Digital Tag', 'hello-elementor-child' ),
		'new_item'              => __( 'New Digital Tag', 'hello-elementor-child' ),
		'edit_item'             => __( 'Edit Digital Tag', 'hello-elementor-child' ),
		'view_item'             => __( 'View Digital Tag', 'hello-elementor-child' ),
		'all_items'             => __( 'All Digital Tags', 'hello-elementor-child' ),
		'search_items'          => __( 'Search Digital Tags', 'hello-elementor-child' ),
		'parent_item_colon'     => __( 'Parent Digital Tags:', 'hello-elementor-child' ),
		'not_found'             => __( 'No digital tags found.', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No digital tags found in Trash.', 'hello-elementor-child' ),
		'featured_image'        => __( 'Digital Tag Image', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Set digital tag image', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Remove digital tag image', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Use as digital tag image', 'hello-elementor-child' ),
		'archives'              => __( 'Digital Tag Archives', 'hello-elementor-child' ),
		'insert_into_item'      => __( 'Insert into digital tag', 'hello-elementor-child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this digital tag', 'hello-elementor-child' ),
		'filter_items_list'     => __( 'Filter digital tags list', 'hello-elementor-child' ),
		'items_list_navigation' => __( 'Digital tags list navigation', 'hello-elementor-child' ),
		'items_list'            => __( 'Digital tags list', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'digital-tags', 'with_front' => false ),
		'menu_icon'           => 'dashicons-tag',
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'elementor' ),
		'capability_type'     => 'post',
		'query_var'           => true,
	);

	register_post_type( 'digital-tags', $args );
}

function wp_inventor_digital_tags_flush_rewrite_rules() {
	wp_inventor_register_digital_tags_post_type();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'wp_inventor_digital_tags_flush_rewrite_rules' );