<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once get_stylesheet_directory() . '/inc/post-types/hardware.php';
require_once get_stylesheet_directory() . '/inc/post-types/digital-tags.php';
require_once get_stylesheet_directory() . '/inc/post-types/news.php';
require_once get_stylesheet_directory() . '/inc/post-types/product-news.php';
require_once get_stylesheet_directory() . '/inc/post-types/usecase.php';
require_once get_stylesheet_directory() . '/inc/post-types/software.php';
require_once get_stylesheet_directory() . '/inc/post-types/support-article.php';

add_action( 'after_setup_theme', 'wp_inventor_hello_elementor_child_setup' );
add_action( 'wp_enqueue_scripts', 'wp_inventor_hello_elementor_child_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'wp_inventor_hello_elementor_child_enqueue_scripts' );
add_action( 'elementor/frontend/after_enqueue_styles', 'wp_inventor_hello_elementor_child_enqueue_styles' );
add_action( 'elementor/preview/enqueue_styles', 'wp_inventor_hello_elementor_child_enqueue_styles' );
add_action( 'elementor/editor/after_enqueue_styles', 'wp_inventor_hello_elementor_child_enqueue_styles' );
add_action( 'elementor/frontend/after_enqueue_scripts', 'wp_inventor_hello_elementor_child_enqueue_scripts' );
add_action( 'elementor/preview/enqueue_scripts', 'wp_inventor_hello_elementor_child_enqueue_scripts' );
add_action( 'elementor/widgets/register', 'wp_inventor_hello_elementor_child_register_widgets' );
add_action( 'elementor/elements/elements_registered', 'wp_inventor_hello_elementor_child_register_atomic_elements' );
add_action( 'wp_ajax_wp_inventor_support_article_search', 'wp_inventor_support_article_search_ajax' );
add_action( 'wp_ajax_nopriv_wp_inventor_support_article_search', 'wp_inventor_support_article_search_ajax' );
add_action( 'wp_ajax_wp_inventor_post_grid_load_more', 'wp_inventor_post_grid_load_more_ajax' );
add_action( 'wp_ajax_nopriv_wp_inventor_post_grid_load_more', 'wp_inventor_post_grid_load_more_ajax' );


function wp_inventor_hello_elementor_child_asset_version( $relative_path ) {
	$asset_path = get_stylesheet_directory() . '/' . ltrim( $relative_path, '/' );

	return file_exists( $asset_path ) ? filemtime( $asset_path ) : wp_get_theme()->get( 'Version' );
}

function wp_inventor_hello_elementor_child_setup() {
	add_theme_support( 'post-thumbnails', array( 'hardware', 'digital-tags', 'news', 'product-news', 'usecase', 'software', 'support-article' ) );
}

function wp_inventor_hello_elementor_child_enqueue_styles() {
	$parent_theme = wp_get_theme( 'hello-elementor' );
	$compiled_style_path = get_stylesheet_directory() . '/assets/css/main.css';
	$compiled_rtl_style_path = get_stylesheet_directory() . '/assets/css/main-rtl.css';

	$style_dependencies = [ 'hello-elementor-theme-style' ];
	wp_enqueue_style(
		'hello-elementor-theme-style',
		get_template_directory_uri() . '/theme.css',
		[],
		$parent_theme->get( 'Version' )
	);

	if ( file_exists( $compiled_style_path ) ) {
		wp_enqueue_style(
			'hello-elementor-child-build-style',
			get_stylesheet_directory_uri() . '/assets/css/main.css',
			$style_dependencies,
			wp_inventor_hello_elementor_child_asset_version( 'assets/css/main.css' )
		);

		if ( is_rtl() && file_exists( $compiled_rtl_style_path ) ) {
			wp_enqueue_style(
				'hello-elementor-child-build-style-rtl',
				get_stylesheet_directory_uri() . '/assets/css/main-rtl.css',
				array( 'hello-elementor-child-build-style' ),
				wp_inventor_hello_elementor_child_asset_version( 'assets/css/main-rtl.css' )
			);
		}
	} else {
		wp_enqueue_style(
			'hello-elementor-child-style',
			get_stylesheet_uri(),
			$style_dependencies,
			wp_get_theme()->get( 'Version' )
		);
	}
}

function wp_inventor_hello_elementor_child_enqueue_scripts() {
	$compiled_script_path = get_stylesheet_directory() . '/assets/js/main.js';

	if ( file_exists( $compiled_script_path ) ) {
		wp_enqueue_script(
			'hello-elementor-child-build-script',
			get_stylesheet_directory_uri() . '/assets/js/main.js',
			[],
			wp_inventor_hello_elementor_child_asset_version( 'assets/js/main.js' ),
			true
		);

		wp_localize_script(
			'hello-elementor-child-build-script',
			'wpInventorSupportArticleSearch',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wp_inventor_support_article_search' ),
			)
		);
	}
}

function wp_inventor_hello_elementor_child_register_widgets( $widgets_manager ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}

	$widgets = array(
		'before-after-image.php'         => 'WP_Inventor_Before_After_Image_Widget',
		'wpml-language-switcher.php'     => 'WP_Inventor_WPML_Language_Switcher_Widget',
		'post-reading-time.php'          => 'WP_Inventor_Post_Reading_Time_Widget',
		'data-table.php'                 => 'WP_Inventor_Data_Table_Widget',
		'support-article-search.php'     => 'WP_Inventor_Support_Article_Search_Widget',
		'support-article-categories.php' => 'WP_Inventor_Support_Article_Categories_Widget',
		'support-article-single-category.php' => 'WP_Inventor_Support_Article_Single_Category_Widget',
		'support-article-list.php'       => 'WP_Inventor_Support_Article_Popular_Widget',
		'support-article-grouped-archive.php' => 'WP_Inventor_Support_Article_Grouped_Archive_Widget',
		'inventor-post-grid.php'         => 'WP_Inventor_Post_Grid_Widget',
		'inventor-post-slider.php'       => 'WP_Inventor_Post_Slider_Widget',
	);

	foreach ( $widgets as $file => $class_name ) {
		require_once get_stylesheet_directory() . '/inc/elementor-widgets/' . $file;

		if ( class_exists( $class_name ) ) {
			$widgets_manager->register( new $class_name() );
		}
	}
}

function wp_inventor_hello_elementor_child_register_atomic_elements( $elements_manager ) {
	if ( ! class_exists( '\\Elementor\\Modules\\AtomicWidgets\\Elements\\Base\\Atomic_Element_Base' ) ) {
		return;
	}

	$atomic_elements = array(
		'atomic-timeline-content.php' => 'WP_Inventor_Atomic_Timeline_Content',
		'atomic-timeline-item.php' => 'WP_Inventor_Atomic_Timeline_Item',
		'atomic-timeline.php' => 'WP_Inventor_Atomic_Timeline',
	);

	foreach ( $atomic_elements as $file => $class_name ) {
		require_once get_stylesheet_directory() . '/inc/elementor-atomic/' . $file;

		if ( class_exists( $class_name ) ) {
			$elements_manager->register_element_type( new $class_name() );
		}
	}
}

function wp_inventor_support_article_search_ajax() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'wp_inventor_support_article_search' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid request.', 'hello-elementor-child' ) ), 403 );
	}

	$query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
	$limit = isset( $_POST['limit'] ) ? absint( wp_unslash( $_POST['limit'] ) ) : 8;
	$limit = max( 1, min( 20, $limit ) );

	if ( '' === $query ) {
		wp_send_json_success( array( 'items' => array() ) );
	}

	$search_query = new WP_Query(
		array(
			'post_type'              => 'support-article',
			'post_status'            => 'publish',
			's'                      => $query,
			'posts_per_page'         => $limit,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	$items = array();

	if ( $search_query->have_posts() ) {
		while ( $search_query->have_posts() ) {
			$search_query->the_post();

			$post_id = get_the_ID();
			$excerpt = get_the_excerpt();

			if ( '' === $excerpt ) {
				$excerpt = wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );
			}

			$items[] = array(
				'id'      => $post_id,
				'title'   => get_the_title(),
				'url'     => get_permalink(),
				'excerpt' => wp_trim_words( $excerpt, 20 ),
			);
		}

		wp_reset_postdata();
	}

	wp_send_json_success( array( 'items' => $items ) );
}

function wp_inventor_post_grid_load_more_ajax() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'wp_inventor_post_grid_load_more' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid request.', 'hello-elementor-child' ) ), 403 );
	}

	$settings_json = isset( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ) : '';
	$settings      = json_decode( (string) $settings_json, true );
	if ( ! is_array( $settings ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid settings.', 'hello-elementor-child' ) ), 400 );
	}

	$page = isset( $_POST['page'] ) ? max( 1, absint( wp_unslash( $_POST['page'] ) ) ) : 2;

	if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Elementor is required.', 'hello-elementor-child' ) ), 500 );
	}

	if ( ! class_exists( 'WP_Inventor_Post_Grid_Widget' ) ) {
		require_once get_stylesheet_directory() . '/inc/elementor-widgets/inventor-post-grid.php';
	}

	if ( ! class_exists( 'WP_Inventor_Post_Grid_Widget' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Post Grid widget is unavailable.', 'hello-elementor-child' ) ), 500 );
	}

	$args = WP_Inventor_Post_Grid_Widget::build_query_args_from_settings( $settings );
	$args['paged'] = $page;

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		wp_send_json_success(
			array(
				'html'         => '',
				'has_more'     => false,
				'current_page' => $page,
			)
		);
	}

	$template = isset( $settings['layout_template'] ) ? sanitize_key( $settings['layout_template'] ) : 'vertical';
	if ( ! in_array( $template, array( 'vertical', 'horizontal' ), true ) ) {
		$template = 'vertical';
	}

	$post_type = is_array( $args['post_type'] ) ? (string) reset( $args['post_type'] ) : (string) $args['post_type'];
	$filter_taxonomy = '';

	if ( taxonomy_exists( 'category' ) && is_object_in_taxonomy( $post_type, 'category' ) ) {
		$filter_taxonomy = 'category';
	} else {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( ! empty( $taxonomy->hierarchical ) && ! empty( $taxonomy->public ) ) {
					$filter_taxonomy = (string) $taxonomy->name;
					break;
				}
			}
		}
	}

	$widget = new WP_Inventor_Post_Grid_Widget();
	$html   = '';

	while ( $query->have_posts() ) {
		$query->the_post();
		$html .= $widget->render_current_post_item_html( $template, $settings, $filter_taxonomy );
	}

	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'         => $html,
			'has_more'     => $page < (int) $query->max_num_pages,
			'current_page' => $page,
		)
	);
}

add_action( 'init', 'wp_inventor_hello_elementor_child_tweak_elementor_save', 100 );
function wp_inventor_hello_elementor_child_tweak_elementor_save() {
	if ( ! wp_doing_ajax() || empty( $_REQUEST['action'] ) || 'save_builder' !== sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) {
		return;
	}

	if ( class_exists( '\Elementor\Modules\History\Revisions_Manager' ) ) {
		remove_filter( 'elementor/documents/ajax_save/return_data', array( '\Elementor\Modules\History\Revisions_Manager', 'on_ajax_save_builder_data' ), 10 );
	}
}


add_filter( 'elementor/atomic-widgets/settings/transformers/classes', 'wpml6_resolve_unmapped_global_classes', 20, 1 );
 
function wpml6_resolve_unmapped_global_classes( $classes ) {
    if ( ! is_array( $classes ) || ! class_exists( 'ElementorModulesGlobalClassesGlobal_Class_Post' ) ) {
        return $classes;
    }
 
    foreach ( $classes as $index => $value ) {
        if ( ! is_string( $value ) || ! preg_match( '/^g-[0-9a-f]{7}$/', $value ) ) {
            continue;
        }
 
        $post  = ElementorModulesGlobalClassesGlobal_Class_Post::find_by_class_id( $value );
        $label = $post ? $post->get_label() : '';
 
        if ( $label ) {
            $classes[ $index ] = $label;
        }
    }
 
    return $classes;
}