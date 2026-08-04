<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'wp_inventor_register_usecase_post_type' );

function wp_inventor_register_usecase_post_type() {
	$labels = array(
		'name'                  => __( 'Usecases', 'hello-elementor-child' ),
		'singular_name'         => __( 'Usecase', 'hello-elementor-child' ),
		'menu_name'             => __( 'Usecases', 'hello-elementor-child' ),
		'name_admin_bar'        => __( 'Usecase', 'hello-elementor-child' ),
		'add_new'               => __( 'Add New', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Add New Usecase', 'hello-elementor-child' ),
		'new_item'              => __( 'New Usecase', 'hello-elementor-child' ),
		'edit_item'             => __( 'Edit Usecase', 'hello-elementor-child' ),
		'view_item'             => __( 'View Usecase', 'hello-elementor-child' ),
		'all_items'             => __( 'All Usecases', 'hello-elementor-child' ),
		'search_items'          => __( 'Search Usecases', 'hello-elementor-child' ),
		'parent_item_colon'     => __( 'Parent Usecases:', 'hello-elementor-child' ),
		'not_found'             => __( 'No usecases found.', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No usecases found in Trash.', 'hello-elementor-child' ),
		'featured_image'        => __( 'Usecase Image', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Set usecase image', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Remove usecase image', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Use as usecase image', 'hello-elementor-child' ),
		'archives'              => __( 'Usecases Archives', 'hello-elementor-child' ),
		'insert_into_item'      => __( 'Insert into usecase', 'hello-elementor-child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this usecase', 'hello-elementor-child' ),
		'filter_items_list'     => __( 'Filter usecase list', 'hello-elementor-child' ),
		'items_list_navigation' => __( 'Usecase list navigation', 'hello-elementor-child' ),
		'items_list'            => __( 'Usecase list', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'usecases', 'with_front' => false ),
		'menu_icon'           => 'dashicons-portfolio',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'elementor' ),
		'capability_type'     => 'post',
		'query_var'           => true,
	);

	register_post_type( 'usecase', $args );
}

function wp_inventor_usecase_flush_rewrite_rules() {
	wp_inventor_register_usecase_post_type();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'wp_inventor_usecase_flush_rewrite_rules' );
