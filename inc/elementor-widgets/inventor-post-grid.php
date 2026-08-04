<?php
/**
 * Inventor Post Grid Widget
 *
 * Displays posts in a grid with customizable query, layout, and style controls.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/traits/trait-post-grid-query-controls.php';
require_once __DIR__ . '/traits/trait-post-widget-helpers.php';

class WP_Inventor_Post_Grid_Widget extends \Elementor\Widget_Base {
	use WP_Inventor_Post_Grid_Query_Controls_Trait;
	use WP_Inventor_Post_Widget_Helpers_Trait;

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'inventor-post-grid';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'Inventor Post Grid', 'hello-elementor-child' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get widget keywords.
	 */
	public function get_keywords() {
		return array( 'posts', 'blog', 'grid', 'news', 'inventor' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		$category_options = $this->register_post_grid_query_controls();

		// Content Tab - Layout Settings.
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => esc_html__( 'Layout Settings', 'hello-elementor-child' ),
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
			'show_category_filter',
			array(
				'label'        => esc_html__( 'Show Category Filter', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'filter_all_text',
			array(
				'label'     => esc_html__( 'All Filter Label', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'All', 'hello-elementor-child' ),
				'condition' => array(
					'show_category_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_categories_mode',
			array(
				'label'     => esc_html__( 'Filter Categories Source', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'all',
				'options'   => array(
					'all'      => esc_html__( 'Show All Categories', 'hello-elementor-child' ),
					'selected' => esc_html__( 'Show Selected Categories', 'hello-elementor-child' ),
				),
				'condition' => array(
					'show_category_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_categories_selected',
			array(
				'label'       => esc_html__( 'Select Categories', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $category_options,
				'condition'   => array(
					'show_category_filter'   => 'yes',
					'filter_categories_mode' => 'selected',
				),
				'description' => esc_html__( 'Choose which categories appear as filter buttons.', 'hello-elementor-child' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'hello-elementor-child' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}} .inventor-post-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'      => esc_html__( 'Column Gap', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-post-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'      => esc_html__( 'Row Gap', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-post-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Content Tab - Display Options.
		$this->start_controls_section(
			'display_section',
			array(
				'label' => esc_html__( 'Display Options', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
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
					'show_image' => 'yes',
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
			'show_excerpt',
			array(
				'label'        => esc_html__( 'Show Excerpt', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => esc_html__( 'Excerpt Length', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 20,
				'min'       => 5,
				'max'       => 100,
				'condition' => array(
					'show_excerpt' => 'yes',
				),
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
			'show_author',
			array(
				'label'        => esc_html__( 'Show Author', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'        => esc_html__( 'Show Category', 'hello-elementor-child' ),
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

		$this->add_control(
			'show_load_more',
			array(
				'label'        => esc_html__( 'Show Load More', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'load_more_text',
			array(
				'label'     => esc_html__( 'Load More Text', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Load More', 'hello-elementor-child' ),
				'condition' => array(
					'show_load_more' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Post Item.
		$this->start_controls_section(
			'style_post_section',
			array(
				'label' => esc_html__( 'Post Item', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'post_background',
			array(
				'label'     => esc_html__( 'Background Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'post_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-post-item'      => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .inventor-post-image'     => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .inventor-post-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_box_shadow',
				'selector' => '{{WRAPPER}} .inventor-post-item',
			)
		);

		$this->end_controls_section();

		// Style Tab - Post Title.
		$this->start_controls_section(
			'style_title_section',
			array(
				'label' => esc_html__( 'Post Title', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .inventor-post-title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Post Excerpt.
		$this->start_controls_section(
			'style_excerpt_section',
			array(
				'label' => esc_html__( 'Post Excerpt', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .inventor-post-excerpt',
			)
		);

		$this->end_controls_section();

		// Style Tab - Post Meta.
		$this->start_controls_section(
			'style_meta_section',
			array(
				'label' => esc_html__( 'Post Meta', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-post-meta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .inventor-post-meta',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$args     = $this->get_query_args();
		$query    = new \WP_Query( $args );
		$post_type = is_array( $args['post_type'] ) ? (string) reset( $args['post_type'] ) : (string) $args['post_type'];
		$filter_taxonomy = $this->get_filter_taxonomy( $post_type );
		$show_category_filter = 'yes' === ( $settings['show_category_filter'] ?? '' ) && ! empty( $filter_taxonomy );
		$show_load_more = 'yes' === ( $settings['show_load_more'] ?? '' );
		$current_page = 1;
		$max_pages = (int) $query->max_num_pages;
		$selected_filter_slugs = array();

		if ( $show_category_filter && 'selected' === ( $settings['filter_categories_mode'] ?? 'all' ) && 'category' === $filter_taxonomy && ! empty( $settings['filter_categories_selected'] ) && is_array( $settings['filter_categories_selected'] ) ) {
			$selected_filter_slugs = array_values( array_filter( array_map( 'sanitize_title', $settings['filter_categories_selected'] ) ) );
		}

		$filter_terms = $show_category_filter ? $this->get_filter_terms_for_query( $query, $filter_taxonomy, $selected_filter_slugs ) : array();
		$widget_id = 'inventor-post-grid-' . $this->get_id();
		$ajax_settings = $this->get_ajax_settings_payload( $settings );

		if ( ! $query->have_posts() ) {
			echo '<div class="elementor-alert elementor-alert-info">' . esc_html__( 'No posts found.', 'hello-elementor-child' ) . '</div>';
			return;
		}

		$template = $settings['layout_template'];
		?>
		<div id="<?php echo esc_attr( $widget_id ); ?>" class="inventor-post-grid-wrapper" data-current-page="<?php echo esc_attr( (string) $current_page ); ?>" data-max-pages="<?php echo esc_attr( (string) $max_pages ); ?>" data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-ajax-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_inventor_post_grid_load_more' ) ); ?>" data-settings="<?php echo esc_attr( wp_json_encode( $ajax_settings ) ); ?>">
			<?php if ( ! empty( $filter_terms ) ) : ?>
				<div class="inventor-post-grid-filters" role="group" aria-label="<?php echo esc_attr__( 'Post categories filter', 'hello-elementor-child' ); ?>">
					<button type="button" class="inventor-filter-btn is-active" data-filter="all"><?php echo esc_html( ! empty( $settings['filter_all_text'] ) ? $settings['filter_all_text'] : __( 'All', 'hello-elementor-child' ) ); ?></button>
					<?php foreach ( $filter_terms as $term ) : ?>
						<button type="button" class="inventor-filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="inventor-post-grid">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					$this->render_post_item( $template, $settings, $filter_taxonomy );
				}
				wp_reset_postdata();
				?>
			</div>

			<?php if ( $show_load_more && $max_pages > 1 ) : ?>
				<div class="inventor-post-grid-load-more-wrap">
					<button type="button" class="inventor-post-grid-load-more" data-load-more><?php echo esc_html( ! empty( $settings['load_more_text'] ) ? $settings['load_more_text'] : __( 'Load More', 'hello-elementor-child' ) ); ?></button>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( ! empty( $filter_terms ) || ( $show_load_more && $max_pages > 1 ) ) : ?>
			<script>
				(function() {
					var root = document.getElementById('<?php echo esc_js( $widget_id ); ?>');
					if (!root) {
						return;
					}

					if (root.dataset.initialized === '1') {
						return;
					}
					root.dataset.initialized = '1';

					var activeFilter = 'all';

					var applyFilter = function() {
						var items = root.querySelectorAll('.inventor-post-item');
						items.forEach(function(item) {
							if (activeFilter === 'all') {
								item.classList.remove('is-filter-hidden');
								return;
							}

							var terms = (item.getAttribute('data-filter-terms') || '').split(' ');
							item.classList.toggle('is-filter-hidden', terms.indexOf(activeFilter) === -1);
						});
					};

					var buttons = root.querySelectorAll('.inventor-filter-btn');
					if (buttons.length) {
						buttons.forEach(function(button) {
							button.addEventListener('click', function() {
								activeFilter = button.getAttribute('data-filter') || 'all';

								buttons.forEach(function(btn) {
									btn.classList.toggle('is-active', btn === button);
								});

								applyFilter();
							});
						});
					}

					var loadMoreButton = root.querySelector('[data-load-more]');
					if (!loadMoreButton) {
						return;
					}

					var maxPages = parseInt(root.getAttribute('data-max-pages') || '1', 10);
					var currentPage = parseInt(root.getAttribute('data-current-page') || '1', 10);
					var ajaxUrl = root.getAttribute('data-ajax-url') || '';
					var nonce = root.getAttribute('data-ajax-nonce') || '';
					var settings = root.getAttribute('data-settings') || '';
					var grid = root.querySelector('.inventor-post-grid');
					var defaultText = loadMoreButton.textContent;

					loadMoreButton.addEventListener('click', function() {
						if (!ajaxUrl || !nonce || !settings || !grid || currentPage >= maxPages || loadMoreButton.disabled) {
							return;
						}

						loadMoreButton.disabled = true;
						loadMoreButton.classList.add('is-loading');
						loadMoreButton.textContent = '<?php echo esc_js( __( 'Loading...', 'hello-elementor-child' ) ); ?>';

						var formData = new URLSearchParams();
						formData.append('action', 'wp_inventor_post_grid_load_more');
						formData.append('nonce', nonce);
						formData.append('page', String(currentPage + 1));
						formData.append('settings', settings);

						fetch(ajaxUrl, {
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
							},
							body: formData.toString()
						}).then(function(response) {
							return response.json();
						}).then(function(response) {
							if (!response || !response.success || !response.data || !response.data.html) {
								return;
							}

							grid.insertAdjacentHTML('beforeend', response.data.html);
							currentPage = parseInt(response.data.current_page || String(currentPage + 1), 10);
							root.setAttribute('data-current-page', String(currentPage));
							if (!response.data.has_more || currentPage >= maxPages) {
								loadMoreButton.remove();
							}

							applyFilter();
						}).catch(function() {
							// Keep button visible on transient AJAX failures.
						}).finally(function() {
							if (document.body.contains(loadMoreButton)) {
								loadMoreButton.disabled = false;
								loadMoreButton.classList.remove('is-loading');
								loadMoreButton.textContent = defaultText;
							}
						});
					});
				})();
			</script>
		<?php endif; ?>
		<?php
	}

	/**
	 * Build minimal settings payload for AJAX load more.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	protected function get_ajax_settings_payload( $settings ) {
		return array(
			'post_type'                  => $settings['post_type'] ?? 'post',
			'posts_per_page'             => absint( $settings['posts_per_page'] ?? 6 ),
			'orderby'                    => $settings['orderby'] ?? 'date',
			'order'                      => $settings['order'] ?? 'DESC',
			'query_category_mode'        => $settings['query_category_mode'] ?? 'all',
			'category'                   => is_array( $settings['category'] ?? null ) ? $settings['category'] : array(),
			'tags'                       => is_array( $settings['tags'] ?? null ) ? $settings['tags'] : array(),
			'exclude_posts'              => $settings['exclude_posts'] ?? '',
			'include_posts'              => $settings['include_posts'] ?? '',
			'layout_template'            => $settings['layout_template'] ?? 'vertical',
			'item_template_variant'      => $settings['item_template_variant'] ?? 'template_1',
			'show_image'                 => $settings['show_image'] ?? 'yes',
			'image_size'                 => $settings['image_size'] ?? 'medium_large',
			'show_category'              => $settings['show_category'] ?? 'yes',
			'show_date'                  => $settings['show_date'] ?? 'yes',
			'show_title'                 => $settings['show_title'] ?? 'yes',
			'show_read_more'             => $settings['show_read_more'] ?? 'yes',
			'read_more_text'             => $settings['read_more_text'] ?? esc_html__( 'Read More', 'hello-elementor-child' ),
			'show_category_filter'       => $settings['show_category_filter'] ?? '',
			'filter_categories_mode'     => $settings['filter_categories_mode'] ?? 'all',
			'filter_categories_selected' => is_array( $settings['filter_categories_selected'] ?? null ) ? $settings['filter_categories_selected'] : array(),
		);
	}

	/**
	 * Get taxonomy used for filter buttons.
	 *
	 * @param string $post_type Current post type.
	 * @return string
	 */
	protected function get_filter_taxonomy( $post_type ) {
		if ( 'post' === $post_type ) {
			return 'category';
		}

		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		if ( empty( $taxonomies ) ) {
			return '';
		}

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! empty( $taxonomy->hierarchical ) && ! empty( $taxonomy->public ) ) {
				return (string) $taxonomy->name;
			}
		}

		return '';
	}

	/**
	 * Build filter terms list from posts in query.
	 *
	 * @param \WP_Query $query Query object.
	 * @param string    $taxonomy Taxonomy name.
	 * @param array     $allowed_slugs Optional allowed term slugs.
	 * @return array
	 */
	protected function get_filter_terms_for_query( $query, $taxonomy, $allowed_slugs = array() ) {
		$post_ids = wp_list_pluck( $query->posts, 'ID' );
		if ( empty( $post_ids ) ) {
			return array();
		}

		$terms = wp_get_object_terms(
			$post_ids,
			$taxonomy,
			array(
				'orderby' => 'name',
				'order'   => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		if ( ! empty( $allowed_slugs ) ) {
			$allowed_lookup = array_flip( $allowed_slugs );
			$terms = array_values(
				array_filter(
					$terms,
					static function( $term ) use ( $allowed_lookup ) {
						return isset( $allowed_lookup[ $term->slug ] );
					}
				)
			);
		}

		return $terms;
	}

	/**
	 * Get query arguments.
	 *
	 * @return array
	 */
	protected function get_query_args() {
		$settings = $this->get_settings_for_display();
		return self::build_query_args_from_settings( $settings );
	}

	/**
	 * Build query args from widget settings.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	public static function build_query_args_from_settings( $settings ) {
		return self::build_shared_post_query_args( $settings );
	}

	/**
	 * Render current loop post as HTML for AJAX requests.
	 *
	 * @param string $template Template type.
	 * @param array  $settings Widget settings.
	 * @param string $filter_taxonomy Taxonomy used for filtering.
	 * @return string
	 */
	public function render_current_post_item_html( $template, $settings, $filter_taxonomy = '' ) {
		ob_start();
		$this->render_post_item( $template, $settings, $filter_taxonomy );
		return (string) ob_get_clean();
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
	 * Render a single post item.
	 *
	 * @param string $template Template type.
	 * @param array  $settings Widget settings.
	 * @param string $filter_taxonomy Taxonomy used for filtering.
	 */
	protected function render_post_item( $template, $settings, $filter_taxonomy = '' ) {
		$post_id   = get_the_ID();
		$permalink = get_permalink();
		$read_more_icon_url = $this->get_post_item_read_more_icon_url();
		$item_type          = $this->get_post_item_type_class( $template );
		$item_template_variant = $this->get_post_item_template_variant( $settings );
		$item_template_class   = $this->get_post_item_template_class( $item_template_variant );
		$reading_time_text = $this->get_reading_time_text( $post_id );
		$filter_slugs = array();

		if ( ! empty( $filter_taxonomy ) ) {
			$post_terms = get_the_terms( $post_id, $filter_taxonomy );
			if ( ! is_wp_error( $post_terms ) && ! empty( $post_terms ) ) {
				$filter_slugs = wp_list_pluck( $post_terms, 'slug' );
			}
		}

		$filter_terms_attr = implode( ' ', array_map( 'sanitize_title', $filter_slugs ) );

		$template_file = $this->get_post_item_template_file( $item_template_variant );

		if ( ! file_exists( $template_file ) ) {
			$template_file = get_stylesheet_directory() . '/inc/elementor-widgets/templates/post-grid-item.php';
		}

		if ( file_exists( $template_file ) ) {
			require $template_file;
		}
	}

}

