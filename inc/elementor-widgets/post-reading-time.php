<?php
/**
 * Inventor Post Reading Time Widget
 *
 * Shows estimated reading time for the current post in single templates and loop templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WP_Inventor_Post_Reading_Time_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name
	 */
	public function get_name() {
		return 'inventor-post-reading-time';
	}

	/**
	 * Get widget title
	 */
	public function get_title() {
		return esc_html__( 'Post Reading Time', 'hello-elementor-child' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return 'eicon-time-line';
	}

	/**
	 * Get widget categories
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get widget keywords
	 */
	public function get_keywords() {
		return array( 'reading', 'read', 'time', 'post', 'minutes', 'inventor' );
	}

	/**
	 * Register widget controls
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Reading Time', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => esc_html__( 'Show Icon', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'Hide', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => esc_html__( 'Icon', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-clock',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'reading_speed',
			array(
				'label'       => esc_html__( 'Words Per Minute', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 200,
				'min'         => 50,
				'max'         => 1000,
				'step'        => 10,
				'description' => esc_html__( 'Average reading speed used to calculate the time.', 'hello-elementor-child' ),
			)
		);

		$this->add_control(
			'singular_label',
			array(
				'label'   => esc_html__( 'Singular Label', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'minut',
			)
		);

		$this->add_control(
			'plural_label',
			array(
				'label'   => esc_html__( 'Plural Label', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'minuts',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Horizontal Alignment', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'hello-elementor-child' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'hello-elementor-child' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'hello-elementor-child' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-reading-time' => 'display: flex; justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'hello-elementor-child' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'hello-elementor-child' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'hello-elementor-child' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-reading-time' => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-reading-time__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .inventor-post-reading-time__text',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'condition' => array(
					'show_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-reading-time__icon' => 'color: {{VALUE}};',
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
						'min' => 8,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 16,
				),
				'condition'  => array(
					'show_icon' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-post-reading-time__icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .inventor-post-reading-time__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .inventor-post-reading-time__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-post-reading-time' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Resolve current post ID for single templates and loop templates.
	 *
	 * @return int
	 */
	private function get_current_post_id() {
		$post_id = get_the_ID();

		if ( $post_id ) {
			return (int) $post_id;
		}

		$post = get_post();
		if ( $post instanceof WP_Post ) {
			return (int) $post->ID;
		}

		$post_id = get_queried_object_id();
		if ( $post_id ) {
			return (int) $post_id;
		}

		return 0;
	}

	/**
	 * Calculate estimated reading time in minutes.
	 *
	 * @param int $post_id Post ID.
	 * @param int $wpm Words per minute.
	 * @return int
	 */
	private function calculate_reading_minutes( $post_id, $wpm ) {
		$content = get_post_field( 'post_content', $post_id );
		if ( empty( $content ) ) {
			return 1;
		}

		$word_count = str_word_count( wp_strip_all_tags( $content ) );
		if ( $word_count < 1 ) {
			return 1;
		}

		return (int) max( 1, ceil( $word_count / max( 1, $wpm ) ) );
	}

	/**
	 * Render widget output on frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$post_id  = $this->get_current_post_id();

		if ( ! $post_id ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="inventor-post-reading-time"><span class="inventor-post-reading-time__text">1 ' . esc_html( $settings['singular_label'] ) . '</span></div>';
			}
			return;
		}

		$wpm      = ! empty( $settings['reading_speed'] ) ? (int) $settings['reading_speed'] : 200;
		$minutes  = $this->calculate_reading_minutes( $post_id, $wpm );
		$label    = ( 1 === $minutes ) ? $settings['singular_label'] : $settings['plural_label'];
		$time_txt = sprintf( '%d %s', $minutes, $label );

		$icon_markup = '';
		if ( 'yes' === $settings['show_icon'] && ! empty( $settings['icon']['value'] ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
			$icon_markup = trim( (string) ob_get_clean() );
		}

		?>
		<div class="inventor-post-reading-time" aria-label="<?php echo esc_attr( $time_txt ); ?>">
			<?php if ( ! empty( $icon_markup ) ) : ?>
				<span class="inventor-post-reading-time__icon" aria-hidden="true">
					<?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			<?php endif; ?>
			<span class="inventor-post-reading-time__text"><?php echo esc_html( $time_txt ); ?></span>
		</div>
		<?php
	}
}

