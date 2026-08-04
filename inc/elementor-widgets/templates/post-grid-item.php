<?php
/**
 * Reusable post item template for Inventor Post Grid.
 *
 * Expected variables:
 * - int    $post_id
 * - string $permalink
 * - array  $settings
 * - string $item_type
 * - string $item_template_class
 * - string $filter_terms_attr
 * - string $read_more_icon_url
 * - string $reading_time_text
 */

$post_id            = isset( $post_id ) ? (int) $post_id : 0;
$permalink          = isset( $permalink ) ? (string) $permalink : '';
$settings           = isset( $settings ) && is_array( $settings ) ? $settings : array();
$item_type          = isset( $item_type ) ? (string) $item_type : 'inventor-post-item-vertical';
$item_template_class = isset( $item_template_class ) ? (string) $item_template_class : 'inventor-post-item-template-1';
$filter_terms_attr  = isset( $filter_terms_attr ) ? (string) $filter_terms_attr : '';
$read_more_icon_url = isset( $read_more_icon_url ) ? (string) $read_more_icon_url : '';
$reading_time_text  = isset( $reading_time_text ) ? (string) $reading_time_text : '';
?>
<article id="post-<?php echo esc_attr( $post_id ); ?>" data-filter-terms="<?php echo esc_attr( $filter_terms_attr ); ?>" <?php post_class( 'inventor-post-item ' . $item_type . ' ' . $item_template_class ); ?>>
	<?php if ( 'yes' === $settings['show_image'] && has_post_thumbnail() ) : ?>
		<div class="inventor-post-image">
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php the_post_thumbnail( $settings['image_size'], array( 'alt' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="inventor-post-content">
		<?php if ( 'yes' === $settings['show_date'] ) : ?>
			<div class="inventor-post-meta">
				<span class="inventor-post-date">
					<?php echo esc_html( get_the_date() ); ?>
				</span>
				<span class="inventor-post-reading-time">
					<?php echo esc_html( $reading_time_text ); ?>
				</span>
			</div>
		<?php endif; ?>

		<?php if ( 'yes' === $settings['show_title'] ) : ?>
			<h3 class="inventor-post-title">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a>
			</h3>
		<?php endif; ?>

		<?php if ( 'yes' === $settings['show_read_more'] ) : ?>
			<div class="inventor-post-read-more">
				<a href="<?php echo esc_url( $permalink ); ?>" class="inventor-read-more-btn">
					<?php echo esc_html( ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : __( 'Read More', 'hello-elementor-child' ) ); ?>
					<svg class="inventor-read-more-icon" width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path fill="currentColor" d="M23 19.9607C22.9999 20.7915 22.3262 21.4651 21.4952 21.4651C20.6642 21.465 19.9905 20.7914 19.9904 19.9607V7.00094L3.53964 22.5875C2.93639 23.1588 1.98337 23.1337 1.41178 22.5307C0.840143 21.9276 0.867315 20.9749 1.47056 20.4034L17.7215 5.00879H3.90017C3.1384 5.00874 2.50873 4.44238 2.40909 3.70811L2.39538 3.50439C2.39538 2.67358 3.06914 2.00006 3.90017 2H23V19.9607Z"/>
					</svg>
				</a>
			</div>
		<?php endif; ?>
	</div>
</article>

