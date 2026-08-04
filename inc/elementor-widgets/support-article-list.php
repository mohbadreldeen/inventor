<?php
/**
 * Inventor Support Article Popular Widget
 *
 * Displays popular support articles in a two-column list.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WP_Inventor_Support_Article_Popular_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'inventor-support-article-popular';
	}

	public function get_title() {
		return esc_html__( 'Support Article List', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function get_keywords() {
		return array( 'support', 'popular', 'articles', 'list', 'inventor' );
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
			'section_title',
			array(
				'label'       => esc_html__( 'Title', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Popular articles', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'articles_type',
			array(
				'label'   => esc_html__( 'Articles Type', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'popular',
				'options' => array(
					'popular' => esc_html__( 'Popular Articles', 'hello-elementor-child' ),
					'related' => esc_html__( 'Related Articles', 'hello-elementor-child' ),
				),
			)
		);

		$this->add_control(
			'posts_limit',
			array(
				'label'   => esc_html__( 'Articles Count', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 20,
				'step'    => 1,
			)
		);

		$this->add_responsive_control(
			'list_columns',
			array(
				'label'          => esc_html__( 'List Columns', 'hello-elementor-child' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'default'        => '2',
				'tablet_default' => '1',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .inventor-popular-articles__grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => esc_html__( 'Order By', 'hello-elementor-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'comment_count',
				'condition' => array(
					'articles_type' => 'popular',
				),
				'options' => array(
					'comment_count' => esc_html__( 'Most Commented', 'hello-elementor-child' ),
					'date'          => esc_html__( 'Newest', 'hello-elementor-child' ),
					'title'         => esc_html__( 'Title', 'hello-elementor-child' ),
				),
			)
		);

		$this->add_control(
			'select_category',
			array(
				'label'       => esc_html__( 'Filter Category', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'options'     => $this->get_support_article_category_options(),
				'multiple'    => false,
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_arrow',
			array(
				'label'        => esc_html__( 'Show Arrow', 'hello-elementor-child' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'Hide', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => esc_html__( 'Empty State Text', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'No articles found.', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => esc_html__( 'Box', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'box_bg',
			array(
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-popular-articles' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'selector' => '{{WRAPPER}} .inventor-popular-articles',
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-popular-articles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-popular-articles' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .inventor-popular-articles__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .inventor-popular-articles__title',
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label'     => esc_html__( 'Item Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-popular-articles__item-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'selector' => '{{WRAPPER}} .inventor-popular-articles__item-link',
			)
		);

		$this->add_control(
			'arrow_color',
			array(
				'label'     => esc_html__( 'Arrow Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-popular-articles__arrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function get_support_article_category_options() {
		$options = array();
		$terms   = get_terms(
			array(
				'taxonomy'   => 'support-article-category',
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}

		return $options;
	}

	/**
	 * Resolve related category terms from control override or current context.
	 *
	 * @param array $settings Widget settings.
	 * @return int[]
	 */
	protected function get_related_term_ids( $settings ) {
		if ( ! empty( $settings['select_category'] ) ) {
			return array( (int) $settings['select_category'] );
		}

		$queried_object = get_queried_object();

		if ( $queried_object instanceof WP_Term && 'support-article-category' === $queried_object->taxonomy ) {
			return array( (int) $queried_object->term_id );
		}

		if ( is_singular( 'support-article' ) ) {
			$terms = get_the_terms( get_the_ID(), 'support-article-category' );

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				return array_values( array_unique( array_map( 'intval', wp_list_pluck( $terms, 'term_id' ) ) ) );
			}
		}

		return array();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$title      = ! empty( $settings['section_title'] ) ? $settings['section_title'] : esc_html__( 'Popular articles', 'hello-elementor-child' );
		$limit      = isset( $settings['posts_limit'] ) ? max( 1, min( 20, (int) $settings['posts_limit'] ) ) : 6;
		$articles_type = ! empty( $settings['articles_type'] ) ? $settings['articles_type'] : 'popular';
		$order_by   = ! empty( $settings['order_by'] ) ? $settings['order_by'] : 'comment_count';
		$show_arrow = isset( $settings['show_arrow'] ) && 'yes' === $settings['show_arrow'];
		$empty_text = ! empty( $settings['empty_text'] ) ? $settings['empty_text'] : esc_html__( 'No articles found.', 'hello-elementor-child' );

		$query_args = array(
			'post_type'              => 'support-article',
			'post_status'            => 'publish',
			'posts_per_page'         => $limit,
			'orderby'                => $order_by,
			'order'                  => 'DESC',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		if ( 'related' === $articles_type ) {
			$query_args['orderby'] = 'date';

			if ( is_singular( 'support-article' ) ) {
				$query_args['post__not_in'] = array( (int) get_the_ID() );
			}

			$related_term_ids = $this->get_related_term_ids( $settings );

			if ( ! empty( $related_term_ids ) ) {
				$query_args['tax_query'] = array(
					array(
						'taxonomy'         => 'support-article-category',
						'field'            => 'term_id',
						'terms'            => $related_term_ids,
						'include_children' => true,
					),
				);
			}
		} else {
			if ( 'title' === $order_by ) {
				$query_args['order'] = 'ASC';
			}

			if ( ! empty( $settings['select_category'] ) ) {
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => 'support-article-category',
						'field'    => 'term_id',
						'terms'    => (int) $settings['select_category'],
					),
				);
			}
		}

		$query = new WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			echo '<div class="inventor-popular-articles inventor-popular-articles--empty">' . esc_html( $empty_text ) . '</div>';
			return;
		}
		?>
		<div class="inventor-popular-articles">
			<h3 class="inventor-popular-articles__title"><?php echo esc_html( $title ); ?></h3>
			<div class="inventor-popular-articles__grid">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<a class="inventor-popular-articles__item-link" href="<?php the_permalink(); ?>">
						<span class="inventor-popular-articles__item-text"><?php the_title(); ?></span>
						<?php if ( $show_arrow ) : ?>
							<span class="inventor-popular-articles__arrow" aria-hidden="true">›</span>
						<?php endif; ?>
					</a>
				<?php endwhile; ?>
			</div>
		</div>
		<?php

		wp_reset_postdata();
	}
}
