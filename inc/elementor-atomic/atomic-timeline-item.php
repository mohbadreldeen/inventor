<?php
/**
 * Inventor Atomic Timeline Item Element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/elementor-atomic/atomic-timeline-content.php';

use Elementor\Modules\AtomicWidgets\Controls\Section;
use Elementor\Modules\AtomicWidgets\Elements\Atomic_Svg\Atomic_Svg;
use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Element_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Element_Template;
use Elementor\Modules\AtomicWidgets\PropTypes\Attributes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Classes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Key_Value_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Style_Definition;
use Elementor\Modules\AtomicWidgets\Styles\Style_Variant;
use Elementor\Modules\Components\PropTypes\Overridable_Prop_Type;

class WP_Inventor_Atomic_Timeline_Item extends Atomic_Element_Base {
	use Has_Element_Template;

	const BASE_STYLE_KEY = 'base';

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		$this->meta( 'is_container', true );
	}

	public static function get_type() {
		return 'e-inventor-timeline-item';
	}

	public static function get_element_type(): string {
		return 'e-inventor-timeline-item';
	}

	public function get_title() {
		return esc_html__( 'Timeline Item', 'hello-elementor-child' );
	}

	public function get_keywords() {
		return array( 'atomic', 'timeline', 'item', 'milestone' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	protected static function define_props_schema(): array {
		return array(
			'classes' => Classes_Prop_Type::make()->default( array() ),
			'attributes' => Attributes_Prop_Type::make()->meta( Overridable_Prop_Type::ignore() ),
		);
	}

	protected function define_atomic_controls(): array {
		return array(
			Section::make()
				->set_label( __( 'Settings', 'hello-elementor-child' ) )
				->set_id( 'settings' )
				->set_items( array() ),
		);
	}

	protected function define_base_styles(): array {
		return array(
			self::BASE_STYLE_KEY => Style_Definition::make()
				->add_variant(
					Style_Variant::make()
						->add_prop( 'display', String_Prop_Type::generate( 'grid' ) )
				),
		);
	}

	protected function define_default_children() {
		return array(
			Atomic_Svg::generate()
				->settings(
					array(
						'attributes' => Attributes_Prop_Type::generate(
							array(
								Key_Value_Prop_Type::generate(
									array(
										'key' => String_Prop_Type::generate( 'data-inventor-timeline-icon' ),
										'value' => String_Prop_Type::generate( '1' ),
									)
								),
							)
						),
					)
				)
				->build(),
			WP_Inventor_Atomic_Timeline_Content::generate()->build(),
		);
	}

	protected function define_allowed_child_types() {
		return array( 'e-svg', 'e-inventor-timeline-content', 'container' );
	}

	protected function get_templates(): array {
		return array(
			'wp-inventor/elements/atomic-timeline-item' => get_stylesheet_directory() . '/inc/elementor-atomic/templates/atomic-timeline-item.html.twig',
		);
	}
}
