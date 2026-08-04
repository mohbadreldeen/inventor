<?php
/**
 * Inventor Support Article Grouped Archive Widget
 *
 * Displays support articles grouped by child categories on the current category archive.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WP_Inventor_Support_Article_Grouped_Archive_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'inventor-support-article-grouped-archive';
	}

	public function get_title() {
		return esc_html__( 'Support Article Grouped Archive', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function get_keywords() {
		return array( 'support', 'archive', 'categories', 'articles', 'grouped', 'inventor' );
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
			'empty_text',
			array(
				'label'       => esc_html__( 'Empty State Text', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'No articles found.', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();
	}

	protected function get_current_term() {
		$queried_object = get_queried_object();

		if ( $queried_object instanceof WP_Term && 'support-article-category' === $queried_object->taxonomy ) {
			return $queried_object;
		}

		return null;
	}

	protected function get_child_terms( $term_id ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'support-article-category',
				'parent'     => (int) $term_id,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		return $terms;
	}

	protected function get_term_article_query( $term_id ) {
		return new WP_Query(
			array(
				'post_type'              => 'support-article',
				'post_status'            => 'publish',
				'posts_per_page'         => -1,
				'orderby'                => 'title',
				'order'                  => 'ASC',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'tax_query'              => array(
					array(
						'taxonomy'         => 'support-article-category',
						'field'            => 'term_id',
						'terms'            => array( (int) $term_id ),
						'include_children'  => true,
					),
				),
			)
		);
	}

	protected function render_article_link( $post_id ) {
		$permalink = get_permalink( $post_id );
		$title     = get_the_title( $post_id );
		?>
		<a class="inventor-popular-articles__item-link" href="<?php echo esc_url( $permalink ); ?>">
			<span class="inventor-popular-articles__item-text"><?php echo esc_html( $title ); ?></span>
			<span class="inventor-popular-articles__arrow" aria-hidden="true">›</span>
		</a>
		<?php
	}

	protected function render_term_group( $term ) {
		$query = $this->get_term_article_query( $term->term_id );

		if ( ! $query->have_posts() ) {
			return;
		}
		?>
		<section class="inventor-support-article-grouped-archive__group">
			<h3 class="inventor-support-article-grouped-archive__title"><?php echo esc_html( $term->name ); ?></h3>
			<div class="inventor-support-article-grouped-archive__grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$this->render_article_link( get_the_ID() );
				endwhile;
				?>
			</div>
		</section>
		<?php

		wp_reset_postdata();
	}

	protected function render_single_group( $term, $empty_text ) {
		$query = $this->get_term_article_query( $term->term_id );

		if ( ! $query->have_posts() ) {
			echo '<div class="inventor-support-article-grouped-archive inventor-support-article-grouped-archive--empty">' . esc_html( $empty_text ) . '</div>';
			return;
		}
		?>
		<div class="inventor-support-article-grouped-archive">
			<section class="inventor-support-article-grouped-archive__group">
				<h3 class="inventor-support-article-grouped-archive__title"><?php echo esc_html( $term->name ); ?></h3>
				<div class="inventor-support-article-grouped-archive__grid">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						$this->render_article_link( get_the_ID() );
					endwhile;
					?>
				</div>
			</section>
		</div>
		<?php

		wp_reset_postdata();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$empty_text = ! empty( $settings['empty_text'] ) ? $settings['empty_text'] : esc_html__( 'No articles found.', 'hello-elementor-child' );
		$current_term = $this->get_current_term();

		if ( ! $current_term ) {
			echo '<div class="inventor-support-article-grouped-archive inventor-support-article-grouped-archive--empty">' . esc_html( $empty_text ) . '</div>';
			return;
		}

		$child_terms = $this->get_child_terms( $current_term->term_id );

		if ( ! empty( $child_terms ) ) {
			$has_output = false;
			?>
			<div class="inventor-support-article-grouped-archive">
				<?php
				foreach ( $child_terms as $child_term ) {
					$query = $this->get_term_article_query( $child_term->term_id );

					if ( ! $query->have_posts() ) {
						wp_reset_postdata();
						continue;
					}

					$has_output = true;
					?>
					<section class="inventor-support-article-grouped-archive__group">
						<h3 class="inventor-support-article-grouped-archive__title"><?php echo esc_html( $child_term->name ); ?></h3>
						<div class="inventor-support-article-grouped-archive__grid">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								$this->render_article_link( get_the_ID() );
							endwhile;
							?>
						</div>
					</section>
					<?php

					wp_reset_postdata();
				}
				?>
			</div>
			<?php

			if ( ! $has_output ) {
				echo '<div class="inventor-support-article-grouped-archive inventor-support-article-grouped-archive--empty">' . esc_html( $empty_text ) . '</div>';
			}

			return;
		}

		$this->render_single_group( $current_term, $empty_text );
	}
}

