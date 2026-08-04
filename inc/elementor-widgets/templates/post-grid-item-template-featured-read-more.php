<?php
/**
 * Shared read-more block for featured-style post item templates.
 *
 * Expected incoming variables:
 * - string $permalink
 * - array  $settings
 */

$permalink = isset( $permalink ) ? (string) $permalink : '';
$settings  = isset( $settings ) && is_array( $settings ) ? $settings : array();
?>
<div class="inventor-post-read-more">
	<a href="<?php echo esc_url( $permalink ); ?>" class="inventor-read-more-btn">
		<?php echo esc_html( ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : __( 'Read Article', 'hello-elementor-child' ) ); ?>
		<svg class="inventor-read-more-icon" width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
			<path fill="currentColor" d="M23 19.9607C22.9999 20.7915 22.3262 21.4651 21.4952 21.4651C20.6642 21.465 19.9905 20.7914 19.9904 19.9607V7.00094L3.53964 22.5875C2.93639 23.1588 1.98337 23.1337 1.41178 22.5307C0.840143 21.9276 0.867315 20.9749 1.47056 20.4034L17.7215 5.00879H3.90017C3.1384 5.00874 2.50873 4.44238 2.40909 3.70811L2.39538 3.50439C2.39538 2.67358 3.06914 2.00006 3.90017 2H23V19.9607Z"/>
		</svg>
	</a>
</div>
