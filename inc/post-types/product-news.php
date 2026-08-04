<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'wp_inventor_register_product_news_post_type' );

function wp_inventor_register_product_news_post_type() {
	$labels = array(
		'name'                  => __( 'Product News', 'hello-elementor-child' ),
		'singular_name'         => __( 'Product News Item', 'hello-elementor-child' ),
		'menu_name'             => __( 'Product News', 'hello-elementor-child' ),
		'name_admin_bar'        => __( 'Product News Item', 'hello-elementor-child' ),
		'add_new'               => __( 'Add New', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Add New Product News Item', 'hello-elementor-child' ),
		'new_item'              => __( 'New Product News Item', 'hello-elementor-child' ),
		'edit_item'             => __( 'Edit Product News Item', 'hello-elementor-child' ),
		'view_item'             => __( 'View Product News Item', 'hello-elementor-child' ),
		'all_items'             => __( 'All Product News', 'hello-elementor-child' ),
		'search_items'          => __( 'Search Product News', 'hello-elementor-child' ),
		'parent_item_colon'     => __( 'Parent Product News:', 'hello-elementor-child' ),
		'not_found'             => __( 'No product news found.', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No product news found in Trash.', 'hello-elementor-child' ),
		'featured_image'        => __( 'Product News Image', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Set product news image', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Remove product news image', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Use as product news image', 'hello-elementor-child' ),
		'archives'              => __( 'Product News Archives', 'hello-elementor-child' ),
		'insert_into_item'      => __( 'Insert into product news item', 'hello-elementor-child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this product news item', 'hello-elementor-child' ),
		'filter_items_list'     => __( 'Filter product news list', 'hello-elementor-child' ),
		'items_list_navigation' => __( 'Product news list navigation', 'hello-elementor-child' ),
		'items_list'            => __( 'Product news list', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'product-news', 'with_front' => false ),
		'menu_icon'           => 'dashicons-megaphone',
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'elementor' ),
		'capability_type'     => 'post',
		'query_var'           => true,
	);

	register_post_type( 'product-news', $args );
}

function wp_inventor_product_news_flush_rewrite_rules() {
	wp_inventor_register_product_news_post_type();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'wp_inventor_product_news_flush_rewrite_rules' );