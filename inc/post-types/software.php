<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'wp_inventor_register_software_post_type' );

function wp_inventor_register_software_post_type() {
	$labels = array(
		'name'                  => __( 'Software', 'hello-elementor-child' ),
		'singular_name'         => __( 'Software', 'hello-elementor-child' ),
		'menu_name'             => __( 'Software', 'hello-elementor-child' ),
		'name_admin_bar'        => __( 'Software', 'hello-elementor-child' ),
		'add_new'               => __( 'Add New', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Add New Software', 'hello-elementor-child' ),
		'new_item'              => __( 'New Software', 'hello-elementor-child' ),
		'edit_item'             => __( 'Edit Software', 'hello-elementor-child' ),
		'view_item'             => __( 'View Software', 'hello-elementor-child' ),
		'all_items'             => __( 'All Software', 'hello-elementor-child' ),
		'search_items'          => __( 'Search Software', 'hello-elementor-child' ),
		'parent_item_colon'     => __( 'Parent Software:', 'hello-elementor-child' ),
		'not_found'             => __( 'No software found.', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No software found in Trash.', 'hello-elementor-child' ),
		'featured_image'        => __( 'Software Image', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Set software image', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Remove software image', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Use as software image', 'hello-elementor-child' ),
		'archives'              => __( 'Software Archives', 'hello-elementor-child' ),
		'insert_into_item'      => __( 'Insert into software', 'hello-elementor-child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this software', 'hello-elementor-child' ),
		'filter_items_list'     => __( 'Filter software list', 'hello-elementor-child' ),
		'items_list_navigation' => __( 'Software list navigation', 'hello-elementor-child' ),
		'items_list'            => __( 'Software list', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'software', 'with_front' => false ),
		'menu_icon'           => 'dashicons-admin-generic',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'elementor' ),
		'capability_type'     => 'post',
		'query_var'           => true,
	);

	register_post_type( 'software', $args );
}

function wp_inventor_software_flush_rewrite_rules() {
	wp_inventor_register_software_post_type();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'wp_inventor_software_flush_rewrite_rules' );
