<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;

class WP_Inventor_WPML_Language_Switcher_Widget extends Widget_Base {
	public function get_name() {
		return 'inventor-wpml-language-switcher';
	}

	public function get_title() {
		return __( 'WPML Language Switcher', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-globe';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	protected function get_globe_icon_markup() {
		return '<svg class="wpml-language-switcher__icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1ZM3.05664 13C3.46977 16.7365 6.16895 19.7827 9.72559 20.709C8.19934 18.4146 7.27497 15.7628 7.05078 13H3.05664ZM16.9492 13C16.725 15.763 15.7999 18.4145 14.2734 20.709C17.8305 19.783 20.5302 16.7369 20.9434 13H16.9492ZM9.06152 13C9.31266 15.7118 10.3318 18.2939 12 20.4443C13.6682 18.2939 14.6873 15.7118 14.9385 13H9.06152ZM14.2734 3.29004C15.8001 5.58467 16.725 8.23673 16.9492 11H20.9434C20.5302 7.26306 17.8306 4.21593 14.2734 3.29004ZM12 3.55469C10.3315 5.70525 9.31268 8.28798 9.06152 11H14.9385C14.6873 8.28798 13.6685 5.70525 12 3.55469ZM9.72559 3.29004C6.16883 4.21622 3.46978 7.26338 3.05664 11H7.05078C7.27499 8.23691 8.19907 5.58457 9.72559 3.29004Z"/></svg>';
	}

	protected function get_language_label( $language_code ) {
		$language_code = strtolower( (string) $language_code );

		if ( 'ar' === $language_code ) {
			return 'العربية';
		}

		return strtoupper( substr( $language_code, 0, 2 ) );
	}

	protected function render() {
		$globe_icon_markup = $this->get_globe_icon_markup();

		if ( ! function_exists( 'icl_get_languages' ) ) {
			echo '<div class="wpml-language-switcher wpml-language-switcher--disabled">';
			echo $globe_icon_markup;
			echo '<span class="wpml-language-switcher__message">' . esc_html__( 'WPML must be active to use this widget.', 'hello-elementor-child' ) . '</span>';
			echo '</div>';
			return;
		}

		$languages = icl_get_languages( 'skip_missing=0' );

		if ( empty( $languages ) || ! is_array( $languages ) ) {
			return;
		}

		$select_id = 'wpml-language-switcher-' . $this->get_id();
		$current_language_label = '';
		$options = array();

		foreach ( $languages as $code => $language ) {
			$language_code = isset( $language['language_code'] ) ? (string) $language['language_code'] : (string) $code;
			$display_text  = $this->get_language_label( $language_code );
			$url           = isset( $language['url'] ) ? (string) $language['url'] : '';
			$is_active     = ! empty( $language['active'] );

			if ( $is_active ) {
				$current_language_label = $display_text;
			}

			$options[] = array(
				'label'   => $display_text,
				'url'     => $url,
				'active'  => $is_active,
			);
		}

		if ( empty( $options ) ) {
			return;
		}

		if ( '' === $current_language_label && ! empty( $options ) ) {
			$current_language_label = $options[0]['label'];
		}
		?>
		<div class="wpml-language-switcher">
			<details class="wpml-language-switcher__dropdown">
				<summary class="wpml-language-switcher__control">
				<?php echo $globe_icon_markup; ?>
					<span class="wpml-language-switcher__current"><?php echo esc_html( $current_language_label ); ?></span>
				</summary>
				<div class="wpml-language-switcher__menu">
					<?php foreach ( $options as $option ) : ?>
						<a class="wpml-language-switcher__option<?php echo ! empty( $option['active'] ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( $option['url'] ); ?>"<?php echo ! empty( $option['active'] ) ? ' aria-current="true"' : ''; ?>>
							<span class="wpml-language-switcher__option-code"><?php echo esc_html( $option['label'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</details>
		</div>
		<?php
	}
}
