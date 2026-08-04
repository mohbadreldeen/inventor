<?php
/**
 * Slider controls trait for Inventor Post Slider widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait WP_Inventor_Post_Slider_Controls_Trait {

	/**
	 * Register slider-specific layout controls.
	 */
	protected function register_post_slider_layout_controls() {
		$this->start_controls_section(
			'slider_layout_section',
			array(
				'label' => esc_html__( 'Slider Settings', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'label'          => esc_html__( 'Slides Per View', 'hello-elementor-child' ),
				'type'           => \Elementor\Controls_Manager::NUMBER,
				'default'        => 1.2,
				'tablet_default' => 1.1,
				'mobile_default' => '1',
				'min'            => 1,
				'max'            => 5,
				'step'           => 0.1,
			)
		);

		$this->add_control(
			'coverflow_side_fill_color',
			array(
				'label'     => esc_html__( 'Coverflow Side Fill Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f1f5ff',
				'condition' => array(
					'slider_effect' => 'coverflow',
				),
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-slider' => '--inventor-coverflow-side-fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'slider_effect',
			array(
				'label'   => esc_html__( 'Slider Effect', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => array(
					'slide'     => esc_html__( 'Slide', 'hello-elementor-child' ),
					'coverflow' => esc_html__( 'Coverflow', 'hello-elementor-child' ),
				),
			)
		);

		$this->add_control(
			'coverflow_rotate',
			array(
				'label'     => esc_html__( 'Coverflow Rotate', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 50,
				'min'       => 0,
				'max'       => 180,
				'condition' => array(
					'slider_effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_stretch',
			array(
				'label'     => esc_html__( 'Coverflow Stretch', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => -200,
				'max'       => 200,
				'condition' => array(
					'slider_effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_depth',
			array(
				'label'     => esc_html__( 'Coverflow Depth', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 100,
				'min'       => 0,
				'max'       => 1000,
				'condition' => array(
					'slider_effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_modifier',
			array(
				'label'     => esc_html__( 'Coverflow Modifier', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 0,
				'max'       => 10,
				'step'      => 0.1,
				'condition' => array(
					'slider_effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_slide_shadows',
			array(
				'label'        => esc_html__( 'Coverflow Slide Shadows', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'slider_effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_centered_slides',
			array(
				'label'        => esc_html__( 'Coverflow Centered Slides', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'slider_effect' => 'coverflow',
				),
			)
		);

		$this->add_control(
			'coverflow_active_slide_width',
			array(
				'label'     => esc_html__( 'Coverflow Active Slide Width (%)', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 100,
				'min'       => 80,
				'max'       => 200,
				'step'      => 1,
				'condition' => array(
					'slider_effect' => 'coverflow',
				),
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-slider' => '--inventor-coverflow-active-width: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'slider_space_between',
			array(
				'label'   => esc_html__( 'Space Between', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 20,
				'min'     => 0,
				'max'     => 1000,
			)
		);

		$this->add_responsive_control(
			'show_navigation',
			array(
				'label'          => esc_html__( 'Show Navigation', 'hello-elementor-child' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'default'        => 'flex',
				'tablet_default' => 'flex',
				'mobile_default' => 'flex',
				'options'        => array(
					'flex' => esc_html__( 'Show', 'hello-elementor-child' ),
					'none' => esc_html__( 'Hide', 'hello-elementor-child' ),
				),
				'selectors'      => array(
					'{{WRAPPER}} .inventor-slider-navigation' => 'display: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'navigation_inline',
			array(
				'label'        => esc_html__( 'Place Arrows Next To Each Other', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'navigation_inline_position',
			array(
				'label'     => esc_html__( 'Arrow Position', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'top-right',
				'options'   => array(
					'top-right'    => esc_html__( 'Top Right', 'hello-elementor-child' ),
					'top-left'     => esc_html__( 'Top Left', 'hello-elementor-child' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'hello-elementor-child' ),
					'bottom-left'  => esc_html__( 'Bottom Left', 'hello-elementor-child' ),
				),
				'condition' => array(
					'navigation_inline' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_pagination',
			array(
				'label'        => esc_html__( 'Show Pagination', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'autoplay_delay',
			array(
				'label'     => esc_html__( 'Autoplay Delay (ms)', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 3500,
				'min'       => 1000,
				'max'       => 15000,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'slider_speed',
			array(
				'label'   => esc_html__( 'Animation Speed (ms)', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'min'     => 100,
				'max'     => 5000,
				'step'    => 50,
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => esc_html__( 'Loop', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register display controls used by shared post item template.
	 */
	protected function register_post_slider_display_controls() {
		$this->start_controls_section(
			'slider_display_section',
			array(
				'label' => esc_html__( 'Display Options', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout_template',
			array(
				'label'   => esc_html__( 'Layout Template', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => array(
					'vertical'   => esc_html__( 'Vertical (Image Top)', 'hello-elementor-child' ),
					'horizontal' => esc_html__( 'Horizontal (Image Left)', 'hello-elementor-child' ),
					'horizontal-right' => esc_html__( 'Horizontal (Image Right)', 'hello-elementor-child' ),
				),
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => esc_html__( 'Show Featured Image', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'item_template_variant',
			array(
				'label'   => esc_html__( 'Item Template', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'template_1',
				'options' => array(
					'template_1' => esc_html__( 'Template 1 (Current)', 'hello-elementor-child' ),
					'featured-horizontal' => esc_html__( 'Featured Horizontal', 'hello-elementor-child' ),
					'product-news-featured' => esc_html__( 'Product News Featured', 'hello-elementor-child' ),
				),
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Image Size', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'medium_large',
				'options'   => array(
					'thumbnail'    => esc_html__( 'Thumbnail', 'hello-elementor-child' ),
					'medium'       => esc_html__( 'Medium', 'hello-elementor-child' ),
					'medium_large' => esc_html__( 'Medium Large', 'hello-elementor-child' ),
					'large'        => esc_html__( 'Large', 'hello-elementor-child' ),
					'full'         => esc_html__( 'Full', 'hello-elementor-child' ),
				),
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'featured_image_height',
			array(
				'label'          => esc_html__( 'Featured Image Height', 'hello-elementor-child' ),
				'type'           => \Elementor\Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'default'        => array(
					'unit' => 'px',
					'size' => 190,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 190,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 190,
				),
				'range'          => array(
					'px' => array(
						'min' => 80,
						'max' => 600,
					),
				),
				'selectors'      => array(),
				'condition'      => array(
					'show_image'             => 'yes',
					'item_template_variant!' => 'featured-horizontal',
				),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Title', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => esc_html__( 'Show Date', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_read_more',
			array(
				'label'        => esc_html__( 'Show Read More', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label'     => esc_html__( 'Read More Text', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read More', 'hello-elementor-child' ),
				'condition' => array(
					'show_read_more' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}
}

