<?php
/**
 * Shared helper methods for Inventor post-based Elementor widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait WP_Inventor_Post_Widget_Helpers_Trait {

	/**
	 * Build query args from shared post widget settings.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	protected static function build_shared_post_query_args( $settings ) {
		$post_type = ! empty( $settings['post_type'] ) ? sanitize_key( $settings['post_type'] ) : 'post';

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => ! empty( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 6,
			'orderby'        => ! empty( $settings['orderby'] ) ? sanitize_key( $settings['orderby'] ) : 'date',
			'order'          => ! empty( $settings['order'] ) ? sanitize_key( $settings['order'] ) : 'DESC',
			'post_status'    => 'publish',
		);

		$query_category_mode = ! empty( $settings['query_category_mode'] ) ? sanitize_key( $settings['query_category_mode'] ) : 'all';

		if ( 'selected' === $query_category_mode && ! empty( $settings['category'] ) && is_array( $settings['category'] ) && taxonomy_exists( 'category' ) ) {
			$category_slugs = array_values( array_filter( array_map( 'sanitize_title', $settings['category'] ) ) );
			if ( ! empty( $category_slugs ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $category_slugs,
					),
				);
			}
		}

		if ( 'post' === $post_type && ! empty( $settings['tags'] ) && is_array( $settings['tags'] ) ) {
			$args['tag_slug__in'] = array_map( 'sanitize_title', $settings['tags'] );
		}

		if ( ! empty( $settings['exclude_posts'] ) ) {
			$args['post__not_in'] = array_values( array_filter( array_map( 'absint', explode( ',', (string) $settings['exclude_posts'] ) ) ) );
		}

		if ( ! empty( $settings['include_posts'] ) ) {
			$args['post__in'] = array_values( array_filter( array_map( 'absint', explode( ',', (string) $settings['include_posts'] ) ) ) );
		}

		return $args;
	}

	/**
	 * Get available post type options for the control.
	 *
	 * @return array
	 */
	protected function get_post_type_options() {
		$options    = array();
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		if ( isset( $post_types['post'] ) ) {
			$options['post'] = $post_types['post']->labels->singular_name;
		}

		foreach ( $post_types as $post_type_key => $post_type_object ) {
			if ( 'post' === $post_type_key ) {
				continue;
			}

			$options[ $post_type_key ] = $post_type_object->labels->singular_name;
		}

		if ( empty( $options ) ) {
			$options['post'] = esc_html__( 'Post', 'hello-elementor-child' );
		}

		return $options;
	}

	/**
	 * Resolve post item layout class from template setting.
	 *
	 * @param string $template Template key.
	 * @return string
	 */
	protected function get_post_item_type_class( $template ) {
		if ( 'horizontal' === $template ) {
			return 'inventor-post-item-horizontal';
		}

		if ( 'horizontal-right' === $template ) {
			return 'inventor-post-item-horizontal-right';
		}

		return 'inventor-post-item-vertical';
	}

	/**
	 * Resolve selected item template variant.
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	protected function get_post_item_template_variant( $settings ) {
		return ! empty( $settings['item_template_variant'] ) ? sanitize_key( $settings['item_template_variant'] ) : 'template_1';
	}

	/**
	 * Resolve CSS class for a template variant.
	 *
	 * @param string $item_template_variant Template variant key.
	 * @return string
	 */
	protected function get_post_item_template_class( $item_template_variant ) {
		if ( 'featured-horizontal' === $item_template_variant ) {
			return 'inventor-post-item-featured-horizontal';
		}

		if ( 'product-news-featured' === $item_template_variant ) {
			return 'inventor-post-item-product-news-featured';
		}

		return 'inventor-post-item-template-1';
	}

	/**
	 * Resolve template file path for a template variant.
	 *
	 * @param string $item_template_variant Template variant key.
	 * @return string
	 */
	protected function get_post_item_template_file( $item_template_variant ) {
		if ( 'featured-horizontal' === $item_template_variant ) {
			return get_stylesheet_directory() . '/inc/elementor-widgets/templates/post-grid-item-template-featured-horizontal.php';
		}

		if ( 'product-news-featured' === $item_template_variant ) {
			return get_stylesheet_directory() . '/inc/elementor-widgets/templates/post-grid-item-template-product-news-featured.php';
		}

		return get_stylesheet_directory() . '/inc/elementor-widgets/templates/post-grid-item.php';
	}

	/**
	 * Get read-more icon URL for post item templates.
	 *
	 * @return string
	 */
	protected function get_post_item_read_more_icon_url() {
		return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/svg/arrow-top-right-blue.svg';
	}

	/**
	 * Get estimated reading time text.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	protected function get_reading_time_text( $post_id ) {
		$content      = get_post_field( 'post_content', $post_id );
		$word_count   = str_word_count( wp_strip_all_tags( (string) $content ) );
		$minutes      = max( 1, (int) ceil( $word_count / 200 ) );
		$minutes_text = sprintf(
			/* translators: %d: reading time in minutes */
			esc_html__( '%d min read', 'hello-elementor-child' ),
			$minutes
		);

		return $minutes_text;
	}
}