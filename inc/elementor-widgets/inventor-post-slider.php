<?php
/**
 * Inventor Post Slider Widget
 *
 * Displays posts in a Swiper slider using shared query controls and post template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/traits/trait-post-grid-query-controls.php';
require_once __DIR__ . '/traits/trait-post-slider-controls.php';
require_once __DIR__ . '/traits/trait-post-widget-helpers.php';

class WP_Inventor_Post_Slider_Widget extends \Elementor\Widget_Base {
	use WP_Inventor_Post_Grid_Query_Controls_Trait;
	use WP_Inventor_Post_Slider_Controls_Trait;
	use WP_Inventor_Post_Widget_Helpers_Trait;

	public function get_name() {
		return 'inventor-post-slider';
	}

	public function get_title() {
		return esc_html__( 'Inventor Post Slider', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function get_keywords() {
		return array( 'posts', 'slider', 'swiper', 'news', 'inventor' );
	}

	/**
	 * Ensure Swiper assets are loaded when this widget is used.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'swiper' );
	}

	/**
	 * Ensure Swiper stylesheet is loaded for this widget.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'swiper' );
	}

	protected function register_controls() {
		$this->register_post_grid_query_controls();
		$this->register_post_slider_layout_controls();
		$this->register_post_slider_display_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$query    = new \WP_Query( $this->get_query_args( $settings ) );

		if ( ! $query->have_posts() ) {
			echo '<div class="elementor-alert elementor-alert-info">' . esc_html__( 'No posts found.', 'hello-elementor-child' ) . '</div>';
			return;
		}

		$widget_id = 'inventor-post-slider-' . $this->get_id();
		$config    = $this->get_slider_config( $settings );
		$template  = ! empty( $settings['layout_template'] ) ? sanitize_key( $settings['layout_template'] ) : 'vertical';
		$slider_effect = ! empty( $settings['slider_effect'] ) ? sanitize_key( $settings['slider_effect'] ) : 'slide';
		$item_template_variant = $this->get_post_item_template_variant( $settings );
		$navigation_visibility_map = $this->get_navigation_visibility_map( $settings );
		$navigation_display_desktop = $navigation_visibility_map['desktop'] ? 'flex' : 'none';
		$navigation_display_tablet  = $navigation_visibility_map['tablet'] ? 'flex' : 'none';
		$navigation_display_mobile  = $navigation_visibility_map['mobile'] ? 'flex' : 'none';
		$wrapper_classes = array( 'inventor-post-slider-wrapper' );
		$wrapper_classes[] = 'inventor-post-slider-wrapper--template-' . sanitize_html_class( $item_template_variant );
		$has_navigation = $this->has_navigation_enabled( $settings );

		if ( $has_navigation && 'yes' === ( $settings['navigation_inline'] ?? '' ) ) {
			$wrapper_classes[] = 'inventor-post-slider-wrapper--navigation-inline';
			$wrapper_classes[] = 'inventor-post-slider-wrapper--nav-' . sanitize_html_class( $settings['navigation_inline_position'] ?? 'top-right' );
		}

		$slider_classes = array( 'swiper', 'inventor-post-slider' );
		if ( 'coverflow' === $slider_effect ) {
			$slider_classes[] = 'inventor-post-slider--effect-coverflow';
		}
		?>
		<style>
			#<?php echo esc_attr( $widget_id ); ?> .inventor-slider-navigation { display: <?php echo esc_html( $navigation_display_desktop ); ?> !important; }
			@media (max-width: 1024px) {
				#<?php echo esc_attr( $widget_id ); ?> .inventor-slider-navigation { display: <?php echo esc_html( $navigation_display_tablet ); ?> !important; }
			}
			@media (max-width: 767px) {
				#<?php echo esc_attr( $widget_id ); ?> .inventor-slider-navigation { display: <?php echo esc_html( $navigation_display_mobile ); ?> !important; }
			}
		</style>
		<div id="<?php echo esc_attr( $widget_id ); ?>" class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<div class="<?php echo esc_attr( implode( ' ', $slider_classes ) ); ?>" data-swiper-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
				<div class="swiper-wrapper">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();
						?>
						<div class="swiper-slide">
							<?php $this->render_post_item( $template, $settings ); ?>
						</div>
						<?php
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
			<?php if ( 'yes' === ( $settings['show_pagination'] ?? 'yes' ) ) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
			<?php if ( $has_navigation ) : ?>
				<div class="inventor-slider-navigation">
					<div class="swiper-button-prev" aria-label="<?php echo esc_attr__( 'Previous slide', 'hello-elementor-child' ); ?>">
						<?php echo $this->get_arrow_svg_markup( 'prev' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="swiper-button-next" aria-label="<?php echo esc_attr__( 'Next slide', 'hello-elementor-child' ); ?>">
						<?php echo $this->get_arrow_svg_markup( 'next' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<script>
			(function() {
				var root = document.getElementById('<?php echo esc_js( $widget_id ); ?>');
				if (!root || root.dataset.initialized === '1') {
					return;
				}

				var navigationVisibility = <?php echo wp_json_encode( $navigation_visibility_map ); ?>;
				var navigationContainer = root.querySelector('.inventor-slider-navigation');
				var getBreakpoints = function() {
					var defaults = { tablet: 1024, mobile: 767 };
					try {
						if (!window.elementorFrontend || !window.elementorFrontend.config || !window.elementorFrontend.config.responsive || !window.elementorFrontend.config.responsive.breakpoints) {
							return defaults;
						}

						var map = window.elementorFrontend.config.responsive.breakpoints;
						var tablet = defaults.tablet;
						var mobile = defaults.mobile;

						if (map.lg && typeof map.lg.value === 'number') {
							tablet = map.lg.value;
						} else if (map.tablet && typeof map.tablet.value === 'number') {
							tablet = map.tablet.value;
						}

						if (map.md && typeof map.md.value === 'number') {
							mobile = map.md.value;
						} else if (map.mobile && typeof map.mobile.value === 'number') {
							mobile = map.mobile.value;
						}

						if (mobile > tablet) {
							mobile = tablet;
						}

						return { tablet: tablet, mobile: mobile };
					} catch (e) {
						return defaults;
					}
				};
				var breakpoints = getBreakpoints();
				var applyNavigationVisibility = function() {
					if (!navigationContainer || !navigationVisibility) {
						return;
					}

					var width = window.innerWidth || document.documentElement.clientWidth || 1200;
					var showNavigation = navigationVisibility.desktop;

					if (width <= breakpoints.mobile) {
						showNavigation = navigationVisibility.mobile;
					} else if (width <= breakpoints.tablet) {
						showNavigation = navigationVisibility.tablet;
					}

					navigationContainer.style.display = showNavigation ? '' : 'none';
				};

				applyNavigationVisibility();
				window.addEventListener('resize', applyNavigationVisibility);

				var sliderEl = root.querySelector('.inventor-post-slider');
				if (!sliderEl) {
					return;
				}

				var initSlider = function() {
					if (typeof window.Swiper === 'undefined' || root.dataset.initialized === '1') {
						return false;
					}

					var configText = sliderEl.getAttribute('data-swiper-config') || '{}';
					var config = {};
					try {
						config = JSON.parse(configText);
					} catch (error) {
						config = {};
					}

					var nextButton = root.querySelector('.swiper-button-next');
					var prevButton = root.querySelector('.swiper-button-prev');
					var paginationEl = root.querySelector('.swiper-pagination');
					if (nextButton && prevButton) {
						config.navigation = {
							nextEl: nextButton,
							prevEl: prevButton
						};
					}

					if (paginationEl) {
						config.pagination = {
							el: paginationEl,
							clickable: true
						};
					}

					new window.Swiper(sliderEl, config);
					root.dataset.initialized = '1';
					return true;
				};

				if (initSlider()) {
					return;
				}

				var attempts = 0;
				var maxAttempts = 20;
				var timer = window.setInterval(function() {
					attempts++;
					if (initSlider() || attempts >= maxAttempts) {
						window.clearInterval(timer);
					}
				}, 150);
			})();
		</script>
		<?php
	}

	protected function get_query_args( $settings ) {
		return self::build_shared_post_query_args( $settings );
	}

	protected function has_navigation_enabled( $settings ) {
		$visibility_map = $this->get_navigation_visibility_map( $settings );

		return (bool) ( $visibility_map['desktop'] || $visibility_map['tablet'] || $visibility_map['mobile'] );
	}

	protected function get_navigation_visibility_map( $settings ) {
		$desktop_raw = $settings['show_navigation'] ?? null;
		$tablet_raw  = $settings['show_navigation_tablet'] ?? null;
		$mobile_raw  = $settings['show_navigation_mobile'] ?? null;

		$desktop = $this->normalize_navigation_visibility_value( $desktop_raw, true );
		$tablet  = $this->normalize_navigation_visibility_value( $tablet_raw, null );
		$mobile  = $this->normalize_navigation_visibility_value( $mobile_raw, null );

		if ( null === $tablet ) {
			$tablet = $desktop;
		}

		if ( null === $mobile ) {
			$mobile = $tablet;
		}

		return array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);
	}

	protected function normalize_navigation_visibility_value( $value, $default = true ) {
		if ( null === $value ) {
			return $default;
		}

		if ( '' === $value || 'default' === $value || 'inherit' === $value || 'initial' === $value ) {
			return null;
		}

		if ( 'none' === $value || 'no' === $value ) {
			return false;
		}

		if ( 'flex' === $value || 'yes' === $value ) {
			return true;
		}

		return true;
	}

	protected function get_slider_config( $settings ) {
		$slides_desktop = max( 1, (float) ( $settings['slides_per_view'] ?? 1.2 ) );
		$slides_tablet  = max( 1, (float) ( $settings['slides_per_view_tablet'] ?? 1.1 ) );
		$slides_mobile  = max( 1, (float) ( $settings['slides_per_view_mobile'] ?? 1 ) );
		$space_between  = max( 0, absint( $settings['slider_space_between'] ?? 20 ) );
		$slider_speed   = max( 100, absint( $settings['slider_speed'] ?? 500 ) );
		$effect         = ! empty( $settings['slider_effect'] ) ? sanitize_key( $settings['slider_effect'] ) : 'slide';

		if ( ! in_array( $effect, array( 'slide', 'coverflow' ), true ) ) {
			$effect = 'slide';
		}

		$config = array(
			'slidesPerView' => $slides_mobile,
			'spaceBetween'  => $space_between,
			'speed'         => $slider_speed,
			'loop'          => 'yes' === ( $settings['loop'] ?? '' ),
			'effect'        => $effect,
			'observer'      => true,
			'observeParents'=> true,
			'breakpoints'   => array(
				768  => array(
					'slidesPerView' => $slides_tablet,
				),
				1025 => array(
					'slidesPerView' => $slides_desktop,
				),
			),
		);

		if ( 'yes' === ( $settings['show_pagination'] ?? 'yes' ) ) {
			$config['pagination'] = array(
				'el'        => '.swiper-pagination',
				'clickable' => true,
			);
		}

		if ( $this->has_navigation_enabled( $settings ) ) {
			$config['navigation'] = array(
				'nextEl' => '.swiper-button-next',
				'prevEl' => '.swiper-button-prev',
			);
		}

		if ( 'yes' === ( $settings['autoplay'] ?? '' ) ) {
			$config['autoplay'] = array(
				'delay'                => max( 1000, absint( $settings['autoplay_delay'] ?? 3500 ) ),
				'disableOnInteraction' => false,
			);
		}

		if ( 'coverflow' === $effect ) {
			$config['centeredSlides'] = 'yes' === ( $settings['coverflow_centered_slides'] ?? 'yes' );
			$config['coverflowEffect'] = array(
				'rotate'       => absint( $settings['coverflow_rotate'] ?? 50 ),
				'stretch'      => (int) ( $settings['coverflow_stretch'] ?? 0 ),
				'depth'        => absint( $settings['coverflow_depth'] ?? 100 ),
				'modifier'     => max( 0, (float) ( $settings['coverflow_modifier'] ?? 1 ) ),
				'slideShadows' => 'yes' === ( $settings['coverflow_slide_shadows'] ?? 'yes' ),
			);

			if ( $config['centeredSlides'] ) {
				$config['breakpoints'][768]['slidesPerView']  = max( 1, $slides_tablet );
				$config['breakpoints'][1025]['slidesPerView'] = max( 1, $slides_desktop );
			}
		}

		return $config;
	}

	protected function render_post_item( $template, $settings ) {
		$post_id            = get_the_ID();
		$permalink          = get_permalink();
		$read_more_icon_url = $this->get_post_item_read_more_icon_url();
		$item_type          = $this->get_post_item_type_class( $template );
		$item_template_variant = $this->get_post_item_template_variant( $settings );
		$item_template_class   = $this->get_post_item_template_class( $item_template_variant );
		$reading_time_text  = $this->get_reading_time_text( $post_id );
		$filter_terms_attr  = '';
		$template_file = $this->get_post_item_template_file( $item_template_variant );

		if ( ! file_exists( $template_file ) ) {
			$template_file = get_stylesheet_directory() . '/inc/elementor-widgets/templates/post-grid-item.php';
		}

		if ( file_exists( $template_file ) ) {
			require $template_file;
		}
	}

	/**
	 * Return inline SVG for slider navigation arrows.
	 *
	 * @param string $direction Arrow direction: prev|next.
	 * @return string
	 */
	protected function get_arrow_svg_markup( $direction ) {
		$path_d = 'next' === $direction
			? 'M14.3466 4.1902C14.606 3.9366 15.0267 3.9366 15.2861 4.1902L22.7167 11.4546C22.7304 11.4683 22.741 11.4845 22.7535 11.499C22.9026 11.6181 23 11.7971 23 12C23 12.2026 22.9023 12.3808 22.7535 12.4999C22.7408 12.5147 22.7306 12.5315 22.7167 12.5454L15.2861 19.8098C15.0267 20.0634 14.606 20.0634 14.3466 19.8098C14.0873 19.5562 14.0873 19.1459 14.3466 18.8924L20.7329 12.649H1.66382C1.29696 12.649 1 12.3586 1 12C1 11.6414 1.29696 11.351 1.66382 11.351H20.7329L14.3466 5.10763C14.0873 4.85405 14.0873 4.44379 14.3466 4.1902Z'
			: 'M9.6534 19.8098C9.39399 20.0634 8.9733 20.0634 8.71389 19.8098L1.28326 12.5454C1.26957 12.5317 1.25904 12.5155 1.2465 12.501C1.09744 12.3819 1 12.2029 1 12C1 11.7974 1.09774 11.6192 1.2465 11.5001C1.2592 11.4853 1.26938 11.4685 1.28326 11.4546L8.71389 4.1902C8.9733 3.9366 9.39399 3.9366 9.6534 4.1902C9.91271 4.44379 9.91272 4.85405 9.6534 5.10764L3.26714 11.351L22.3362 11.351C22.703 11.351 23 11.6414 23 12C23 12.3586 22.703 12.649 22.3362 12.649L3.26714 12.649L9.6534 18.8924C9.91272 19.146 9.91272 19.5562 9.6534 19.8098Z';

		return sprintf(
			'<svg class="inventor-slider-arrow-icon" width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="%s"/></svg>',
			esc_attr( $path_d )
		);
	}
}

