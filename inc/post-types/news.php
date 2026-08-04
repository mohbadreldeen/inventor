<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'wp_inventor_register_news_post_type' );

function wp_inventor_register_news_post_type() {
	$labels = array(
		'name'                  => __( 'News', 'hello-elementor-child' ),
		'singular_name'         => __( 'News Item', 'hello-elementor-child' ),
		'menu_name'             => __( 'News', 'hello-elementor-child' ),
		'name_admin_bar'        => __( 'News Item', 'hello-elementor-child' ),
		'add_new'               => __( 'Add New', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Add New News Item', 'hello-elementor-child' ),
		'new_item'              => __( 'New News Item', 'hello-elementor-child' ),
		'edit_item'             => __( 'Edit News Item', 'hello-elementor-child' ),
		'view_item'             => __( 'View News Item', 'hello-elementor-child' ),
		'all_items'             => __( 'All News', 'hello-elementor-child' ),
		'search_items'          => __( 'Search News', 'hello-elementor-child' ),
		'parent_item_colon'     => __( 'Parent News:', 'hello-elementor-child' ),
		'not_found'             => __( 'No news found.', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No news found in Trash.', 'hello-elementor-child' ),
		'featured_image'        => __( 'News Image', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Set news image', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Remove news image', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Use as news image', 'hello-elementor-child' ),
		'archives'              => __( 'News Archives', 'hello-elementor-child' ),
		'insert_into_item'      => __( 'Insert into news item', 'hello-elementor-child' ),
		'uploaded_to_this_item' => __( 'Uploaded to this news item', 'hello-elementor-child' ),
		'filter_items_list'     => __( 'Filter news list', 'hello-elementor-child' ),
		'items_list_navigation' => __( 'News list navigation', 'hello-elementor-child' ),
		'items_list'            => __( 'News list', 'hello-elementor-child' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'taxonomies'          => array( 'category' ),
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'news', 'with_front' => false ),
		'menu_icon'           => 'dashicons-megaphone',
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'elementor' ),
		'capability_type'     => 'post',
		'query_var'           => true,
	);

	register_post_type( 'news', $args );
}

function wp_inventor_news_flush_rewrite_rules() {
	wp_inventor_register_news_post_type();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'wp_inventor_news_flush_rewrite_rules' );