<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'wp_inventor_register_hardware_post_type' );

function wp_inventor_register_hardware_post_type() {
	$labels = array(
		'name'                  => __( 'Hardware', 'hello-elementor-child' ),
		'singular_name'         => __( 'Hardware Item', 'hello-elementor-child' ),
		'menu_name'             => __( 'Hardware', 'hello-elementor-child' ),
		'name_admin_bar'        => __( 'Hardware Item', 'hello-elementor-child' ),
		'add_new'               => __( 'Add New', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Add New Hardware Item', 'hello-elementor-child' ),
		'new_item'              => __( 'New Hardware Item', 'hello-elementor-child' ),
		'edit_item'             => __( 'Edit Hardware Item', 'hello-elementor-child' ),
		'view_item'             => __( 'View Hardware Item', 'hello-elementor-child' ),
		'all_items'             => __( 'All Hardware', 'hello-elementor-child' ),
		'search_items'          => __( 'Search Hardware', 'hello-elementor-child' ),
		'parent_item_colon'     => __( 'Parent Hardware:', 'hello-elementor-child' ),
		'not_found'             => __( 'No hardware found.', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No hardware found in Trash.', 'hello-elementor-child' ),
		'featured_image'        => __( 'Hardware Image', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Set hardware image', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Remove hardware image', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Use as hardware image', 'hello-elementor-child' ),
		'archives'              => __( 'Hardware Archives', 'hello-elementor-child' ),
		'insert_into_item'      => __( 'Insert into hardware item', 'hello-elementor-child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this hardware item', 'hello-elementor-child' ),
		'filter_items_list'     => __( 'Filter hardware list', 'hello-elementor-child' ),
		'items_list_navigation' => __( 'Hardware list navigation', 'hello-elementor-child' ),
		'items_list'            => __( 'Hardware list', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'hardware', 'with_front' => false ),
		'menu_icon'           => 'dashicons-admin-tools',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'elementor' ),
		'capability_type'     => 'post',
		'query_var'           => true,
	);

	register_post_type( 'hardware', $args );
}

function wp_inventor_hardware_flush_rewrite_rules() {
	wp_inventor_register_hardware_post_type();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'wp_inventor_hardware_flush_rewrite_rules' );
