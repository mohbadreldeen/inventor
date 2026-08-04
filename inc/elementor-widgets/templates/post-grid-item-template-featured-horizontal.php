<?php
/**
 * Reusable featured horizontal post item template for Inventor Post Grid/Slider.
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

$item_template_class = isset( $item_template_class ) ? (string) $item_template_class : 'inventor-post-item-featured-horizontal';
$summary_length      = ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 28;

require __DIR__ . '/post-grid-item-template-featured-bootstrap.php';
?>
<article id="post-<?php echo esc_attr( $post_id ); ?>" data-filter-terms="<?php echo esc_attr( $filter_terms_attr ); ?>" <?php post_class( 'inventor-post-item ' . $item_type . ' ' . $item_template_class ); ?>>
	<?php if ( 'yes' === ( $settings['show_image'] ?? 'yes' ) && has_post_thumbnail() ) : ?>
		<div class="inventor-post-image">
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php the_post_thumbnail( $settings['image_size'] ?? 'large', array( 'alt' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="inventor-post-content">
		<?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
			<h3 class="inventor-post-title">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a>
			</h3>
		<?php endif; ?>

		<?php if ( $show_summary && '' !== trim( $summary_text ) ) : ?>
			<p class="inventor-post-summary"><?php echo esc_html( $summary_text ); ?></p>
		<?php endif; ?>

		<?php if ( 'yes' === ( $settings['show_date'] ?? 'yes' ) ) : ?>
			<div class="inventor-post-meta">
				<span class="inventor-post-reading-time"><?php echo esc_html( $reading_time_text ); ?></span>
				<span class="inventor-post-date"><?php echo esc_html( get_the_date() ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( 'yes' === ( $settings['show_read_more'] ?? 'yes' ) ) : ?>
			<?php require __DIR__ . '/post-grid-item-template-featured-read-more.php'; ?>
		<?php endif; ?>
	</div>
</article>

