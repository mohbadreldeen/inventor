<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class WP_Inventor_Before_After_Image_Widget extends Widget_Base {
	public function get_name() {
		return 'inventor-before-after-image';
	}

	public function get_title() {
		return __( 'Before / After Image', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-image-before-after';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'hello-elementor-child' ),
			)
		);

		$this->add_control(
			'before_image',
			array(
				'label'   => __( 'Before Image', 'hello-elementor-child' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'after_image',
			array(
				'label'   => __( 'After Image', 'hello-elementor-child' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'height',
			array(
				'label' => __( 'Height', 'hello-elementor-child' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 200,
						'max' => 1000,
					),
				),
				'default' => array(
					'size' => 500,
				),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'   => __( 'Initial Position', 'hello-elementor-child' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 50,
				),
			)
		);

		$this->end_controls_section();
	}

	private function render_image_markup( array $image, string $class_name, string $fallback_text ) {
		$image_id = isset( $image['id'] ) ? (int) $image['id'] : 0;
		$image_url = isset( $image['url'] ) ? $image['url'] : '';

		if ( $image_id ) {
			echo wp_get_attachment_image(
				$image_id,
				'full',
				false,
				array(
					'class' => $class_name,
					'alt'   => esc_attr( $fallback_text ),
				)
			);
			return;
		}

		if ( $image_url ) {
			echo '<img class="' . esc_attr( $class_name ) . '" src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $fallback_text ) . '" loading="lazy" />';
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$before_image = isset( $settings['before_image'] ) && is_array( $settings['before_image'] ) ? $settings['before_image'] : array();
		$after_image = isset( $settings['after_image'] ) && is_array( $settings['after_image'] ) ? $settings['after_image'] : array();
		$height = isset( $settings['height']['size'] ) ? (int) $settings['height']['size'] : 500;
		$height = max( 200, min( 1000, $height ) );
		$position = isset( $settings['position']['size'] ) ? (float) $settings['position']['size'] : 50;
		$position = max( 0, min( 100, $position ) );

		if ( empty( $before_image['id'] ) && empty( $before_image['url'] ) && empty( $after_image['id'] ) && empty( $after_image['url'] ) ) {
			echo '<div class="wp-before-after-image__empty">' . esc_html__( 'Please choose two images.', 'hello-elementor-child' ) . '</div>';
			return;
		}

		?>
		<div class="wp-before-after-image" data-before-after-image style="--wp-before-after-position: <?php echo esc_attr( $position . '%' ); ?>; --wp-before-after-height: <?php echo esc_attr( $height . 'px' ); ?>;">
			<div class="wp-before-after-image__frame">
				<div class="wp-before-after-image__layer wp-before-after-image__layer--after">
					<?php $this->render_image_markup( $after_image, 'wp-before-after-image__image', __( 'After image', 'hello-elementor-child' ) ); ?>
				</div>

				<div class="wp-before-after-image__layer wp-before-after-image__layer--before">
					<?php $this->render_image_markup( $before_image, 'wp-before-after-image__image', __( 'Before image', 'hello-elementor-child' ) ); ?>
				</div>

				<div class="wp-before-after-image__divider" aria-hidden="true"></div>
				<input class="wp-before-after-image__range" type="range" min="0" max="100" value="<?php echo esc_attr( $position ); ?>" aria-label="<?php echo esc_attr__( 'Compare before and after images', 'hello-elementor-child' ); ?>" />
			</div>
		</div>
		<?php
	}
}
