<?php
/**
 * Shared bootstrap for featured-style post item templates.
 *
 * Expected incoming variables:
 * - int    $post_id
 * - string $permalink
 * - array  $settings
 * - string $item_type
 * - string $item_template_class
 * - string $filter_terms_attr
 * - string $read_more_icon_url
 * - string $reading_time_text
 * - int    $summary_length
 */

$post_id             = isset( $post_id ) ? (int) $post_id : 0;
$permalink           = isset( $permalink ) ? (string) $permalink : '';
$settings            = isset( $settings ) && is_array( $settings ) ? $settings : array();
$item_type           = isset( $item_type ) ? (string) $item_type : 'inventor-post-item-horizontal';
$item_template_class = isset( $item_template_class ) ? (string) $item_template_class : '';
$filter_terms_attr   = isset( $filter_terms_attr ) ? (string) $filter_terms_attr : '';
$read_more_icon_url  = isset( $read_more_icon_url ) ? (string) $read_more_icon_url : '';
$reading_time_text   = isset( $reading_time_text ) ? (string) $reading_time_text : '';
$summary_length      = isset( $summary_length ) ? absint( $summary_length ) : 24;
$show_summary        = ! isset( $settings['show_excerpt'] ) || 'yes' === $settings['show_excerpt'];
$summary_text        = get_the_excerpt();

if ( '' === trim( (string) $summary_text ) ) {
	$summary_text = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
}

$summary_text = wp_trim_words( $summary_text, max( 8, $summary_length ), '...' );
