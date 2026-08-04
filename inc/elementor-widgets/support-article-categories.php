<?php
/**
 * Inventor Support Article Categories Widget
 *
 * Displays top-level support article categories as cards.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WP_Inventor_Support_Article_Categories_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'inventor-support-article-categories';
	}

	public function get_title() {
		return esc_html__( 'Support Article Categories', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-taxonomy-filter';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function get_keywords() {
		return array( 'support', 'categories', 'taxonomy', 'articles', 'inventor' );
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
			'include_empty',
			array(
				'label'        => esc_html__( 'Include Empty Categories', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => esc_html__( 'Show Category Image', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'Hide', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'        => esc_html__( 'Show Description', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'Hide', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Article Count', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'Hide', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'link_cards',
			array(
				'label'        => esc_html__( 'Link Cards To Category', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => esc_html__( 'Empty State Text', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'No categories found.', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_layout',
			array(
				'label' => esc_html__( 'Layout', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'      => esc_html__( 'Columns', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SELECT,
				'default'    => '2',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'    => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-categories' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => esc_html__( 'Gap', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-categories' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => esc_html__( 'Card', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-categories__card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .inventor-support-article-categories__card',
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-categories__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-support-article-categories__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Text', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-categories__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .inventor-support-article-categories__title',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Description Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-categories__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .inventor-support-article-categories__description',
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => esc_html__( 'Count Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-support-article-categories__count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .inventor-support-article-categories__count',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get article count for a term including all descendant terms.
	 *
	 * @param int $term_id Term ID.
	 * @return int
	 */
	protected function get_term_count_with_children( $term_id ) {
		$taxonomy = 'support-article-category';
		$term_ids = array_merge( array( (int) $term_id ), get_term_children( (int) $term_id, $taxonomy ) );
		$term_ids = array_map( 'intval', $term_ids );

		if ( empty( $term_ids ) ) {
			return 0;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'include'    => $term_ids,
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return 0;
		}

		$total = 0;
		foreach ( $terms as $term ) {
			$total += (int) $term->count;
		}

		return $total;
	}

	/**
	 * Render a category image, inlining SVG attachments when available.
	 *
	 * @param int $image_id Attachment ID.
	 * @return string
	 */
	protected function get_category_image_markup( $image_id ) {
		$image_id = (int) $image_id;

		if ( $image_id <= 0 ) {
			return '';
		}

		$mime_type = get_post_mime_type( $image_id );

		if ( in_array( $mime_type, array( 'image/svg+xml', 'application/svg+xml' ), true ) ) {
			$svg_path = get_attached_file( $image_id );

			if ( $svg_path && file_exists( $svg_path ) && is_readable( $svg_path ) ) {
				$svg = file_get_contents( $svg_path );

				if ( false !== $svg && '' !== $svg ) {
					return $svg;
				}
			}
		}

		return wp_get_attachment_image(
			$image_id,
			'thumbnail',
			false,
			array(
				'class' => 'inventor-support-article-categories__image',
			)
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$include_empty    = isset( $settings['include_empty'] ) && 'yes' === $settings['include_empty'];
		$show_image       = isset( $settings['show_image'] ) && 'yes' === $settings['show_image'];
		$show_description = isset( $settings['show_description'] ) && 'yes' === $settings['show_description'];
		$show_count       = isset( $settings['show_count'] ) && 'yes' === $settings['show_count'];
		$link_cards       = isset( $settings['link_cards'] ) && 'yes' === $settings['link_cards'];
		$empty_text       = ! empty( $settings['empty_text'] ) ? $settings['empty_text'] : esc_html__( 'No categories found.', 'hello-elementor-child' );

		$terms = get_terms(
			array(
				'taxonomy'   => 'support-article-category',
				'hide_empty' => ! $include_empty,
				'parent'     => 0,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			echo '<div class="inventor-support-article-categories inventor-support-article-categories--empty">' . esc_html( $empty_text ) . '</div>';
			return;
		}
		?>
		<div class="inventor-support-article-categories">
			<?php foreach ( $terms as $term ) : ?>
				<?php
				$tag = $link_cards ? 'a' : 'div';
				$term_link = get_term_link( $term );
				$image_id = (int) get_term_meta( $term->term_id, '_wp_inventor_support_article_category_image_id', true );
				$description = wp_strip_all_tags( term_description( $term->term_id, 'support-article-category' ) );
				$article_count = $this->get_term_count_with_children( (int) $term->term_id );
				$article_label = sprintf(
					/* translators: %d: number of articles */
					_n( '%d article', '%d articles', $article_count, 'hello-elementor-child' ),
					$article_count
				);
				?>
				<<?php echo esc_html( $tag ); ?>
					class="inventor-support-article-categories__card"
					<?php if ( $link_cards && ! is_wp_error( $term_link ) ) : ?>href="<?php echo esc_url( $term_link ); ?>"<?php endif; ?>
				>
					<?php if ( $show_image ) : ?>
						<div class="inventor-support-article-categories__image-wrap">
							<?php if ( $image_id > 0 ) : ?>
								<?php echo $this->get_category_image_markup( $image_id ); ?>
							<?php else : ?>
								<span class="inventor-support-article-categories__image-fallback" aria-hidden="true">?</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<h3 class="inventor-support-article-categories__title"><?php echo esc_html( $term->name ); ?></h3>

					<?php if ( $show_description && '' !== $description ) : ?>
						<p class="inventor-support-article-categories__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<?php if ( $show_count ) : ?>
						<div class="inventor-support-article-categories__count"><?php echo esc_html( $article_label ); ?></div>
					<?php endif; ?>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
