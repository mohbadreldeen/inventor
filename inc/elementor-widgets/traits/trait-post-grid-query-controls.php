<?php
/**
 * Query controls trait for Inventor Post Grid widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait WP_Inventor_Post_Grid_Query_Controls_Trait {

	/**
	 * Register query controls and return category options for reuse.
	 *
	 * @return array
	 */
	protected function register_post_grid_query_controls() {
		$this->start_controls_section(
			'query_section',
			array(
				'label' => esc_html__( 'Query Settings', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => esc_html__( 'Post Type', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_type_options(),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Posts Per Page', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 100,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'          => esc_html__( 'Date', 'hello-elementor-child' ),
					'title'         => esc_html__( 'Title', 'hello-elementor-child' ),
					'modified'      => esc_html__( 'Modified', 'hello-elementor-child' ),
					'rand'          => esc_html__( 'Random', 'hello-elementor-child' ),
					'comment_count' => esc_html__( 'Comment Count', 'hello-elementor-child' ),
					'menu_order'    => esc_html__( 'Menu Order', 'hello-elementor-child' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending', 'hello-elementor-child' ),
					'DESC' => esc_html__( 'Descending', 'hello-elementor-child' ),
				),
			)
		);

		$categories       = get_categories( array( 'hide_empty' => false ) );
		$category_options = array();
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$category_options[ $category->slug ] = $category->name;
			}
		}

		$this->add_control(
			'query_category_mode',
			array(
				'label'     => esc_html__( 'Categories', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'all',
				'options'   => array(
					'all'      => esc_html__( 'All Categories', 'hello-elementor-child' ),
					'selected' => esc_html__( 'Selected Categories', 'hello-elementor-child' ),
				),
				'condition' => array(
					'post_type' => 'post',
				),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'     => esc_html__( 'Select Categories', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::SELECT2,
				'options'   => $category_options,
				'condition' => array(
					'post_type'           => 'post',
					'query_category_mode' => 'selected',
				),
				'multiple'  => true,
				'description' => esc_html__( 'Choose one or more categories to include in the query.', 'hello-elementor-child' ),
			)
		);

		$tags        = get_tags( array( 'hide_empty' => false ) );
		$tag_options = array( '' => esc_html__( 'All Tags', 'hello-elementor-child' ) );
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$tag_options[ $tag->slug ] = $tag->name;
			}
		}

		$this->add_control(
			'tags',
			array(
				'label'     => esc_html__( 'Tags', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::SELECT2,
				'options'   => $tag_options,
				'condition' => array(
					'post_type' => 'post',
				),
				'multiple'  => true,
			)
		);

		$this->add_control(
			'exclude_posts',
			array(
				'label'       => esc_html__( 'Exclude Posts', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Post IDs (comma separated)', 'hello-elementor-child' ),
				'description' => esc_html__( 'Enter post IDs to exclude, separated by commas', 'hello-elementor-child' ),
			)
		);

		$this->add_control(
			'include_posts',
			array(
				'label'       => esc_html__( 'Include Posts', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Post IDs (comma separated)', 'hello-elementor-child' ),
				'description' => esc_html__( 'Enter specific post IDs to show, separated by commas', 'hello-elementor-child' ),
			)
		);

		$this->end_controls_section();

		return $category_options;
	}
}
