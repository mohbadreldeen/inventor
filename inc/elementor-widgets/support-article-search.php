<?php
/**
 * Inventor Support Article Search Widget
 *
 * Displays a large search field that performs AJAX search on support articles.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WP_Inventor_Support_Article_Search_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'inventor-support-article-search';
	}

	public function get_title() {
		return esc_html__( 'Support Article Search', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function get_keywords() {
		return array( 'search', 'support', 'article', 'ajax', 'inventor' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'placeholder_text',
			array(
				'label'       => esc_html__( 'Placeholder', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Search support articles...', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'button_aria_label',
			array(
				'label'       => esc_html__( 'Search Button Label (Accessibility)', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Search', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'min_chars',
			array(
				'label'       => esc_html__( 'Minimum Characters', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 2,
				'min'         => 1,
				'max'         => 10,
				'step'        => 1,
			)
		);

		$this->add_control(
			'results_limit',
			array(
				'label'       => esc_html__( 'Results Limit', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 8,
				'min'         => 1,
				'max'         => 20,
				'step'        => 1,
			)
		);

		$this->add_control(
			'no_results_text',
			array(
				'label'       => esc_html__( 'No Results Text', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'No support articles found.', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_input',
			array(
				'label' => esc_html__( 'Search Field', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'field_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-search__field' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'field_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-search__input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'field_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-search__input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'field_typography',
				'selector' => '{{WRAPPER}} .inventor-support-article-search__input',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'field_border',
				'selector' => '{{WRAPPER}} .inventor-support-article-search__field',
			)
		);

		$this->add_responsive_control(
			'field_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-search__field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => esc_html__( 'Field Padding', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-search__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			array(
				'label' => esc_html__( 'Search Icon', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-search__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-search__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 48,
					),
				),
				'default'    => array(
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-search__button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => esc_html__( 'Button Padding', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-search__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_radius',
			array(
				'label'      => esc_html__( 'Button Radius', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-search__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$placeholder_text = ! empty( $settings['placeholder_text'] ) ? $settings['placeholder_text'] : esc_html__( 'Search support articles...', 'hello-elementor-child' );
		$button_aria      = ! empty( $settings['button_aria_label'] ) ? $settings['button_aria_label'] : esc_html__( 'Search', 'hello-elementor-child' );
		$min_chars        = isset( $settings['min_chars'] ) ? max( 1, (int) $settings['min_chars'] ) : 2;
		$results_limit    = isset( $settings['results_limit'] ) ? max( 1, min( 20, (int) $settings['results_limit'] ) ) : 8;
		$no_results_text  = ! empty( $settings['no_results_text'] ) ? $settings['no_results_text'] : esc_html__( 'No support articles found.', 'hello-elementor-child' );
		$results_id       = 'inventor-support-search-results-' . $this->get_id();
		?>
		<div class="inventor-support-article-search" data-min-chars="<?php echo esc_attr( $min_chars ); ?>" data-limit="<?php echo esc_attr( $results_limit ); ?>" data-no-results="<?php echo esc_attr( $no_results_text ); ?>">
			<form class="inventor-support-article-search__field" action="#" method="get" novalidate>
				<button type="submit" class="inventor-support-article-search__button" aria-label="<?php echo esc_attr( $button_aria ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path fill="currentColor" d="M10 4a6 6 0 1 0 3.77 10.66l4.28 4.28a1 1 0 0 0 1.41-1.41l-4.28-4.28A6 6 0 0 0 10 4zm0 2a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/>
					</svg>
				</button>
				<input
					type="search"
					class="inventor-support-article-search__input"
					placeholder="<?php echo esc_attr( $placeholder_text ); ?>"
					aria-label="<?php echo esc_attr( $placeholder_text ); ?>"
					autocomplete="off"
					aria-controls="<?php echo esc_attr( $results_id ); ?>"
				/>
				
			</form>
			<div id="<?php echo esc_attr( $results_id ); ?>" class="inventor-support-article-search__results" role="status" aria-live="polite" hidden></div>
		</div>
		<?php
	}
}
