<?php
/**
 * Inventor Atomic Timeline Content Element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Modules\AtomicWidgets\Controls\Section;
use Elementor\Modules\AtomicWidgets\Elements\Atomic_Heading\Atomic_Heading;
use Elementor\Modules\AtomicWidgets\Elements\Atomic_Paragraph\Atomic_Paragraph;
use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Element_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Element_Template;
use Elementor\Modules\AtomicWidgets\PropTypes\Attributes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Classes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Html_V3_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Style_Definition;
use Elementor\Modules\AtomicWidgets\Styles\Style_Variant;
use Elementor\Modules\Components\PropTypes\Overridable_Prop_Type;

class WP_Inventor_Atomic_Timeline_Content extends Atomic_Element_Base {
	use Has_Element_Template;

	const BASE_STYLE_KEY = 'base';

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		$this->meta( 'is_container', true );
	}

	public static function get_type() {
		return 'e-inventor-timeline-content';
	}

	public static function get_element_type(): string {
		return 'e-inventor-timeline-content';
	}

	public function get_title() {
		return esc_html__( 'Timeline Content', 'hello-elementor-child' );
	}

	public function get_keywords() {
		return array( 'atomic', 'timeline', 'content' );
	}

	public function get_icon() {
		return 'eicon-text';
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
						->add_prop( 'display', String_Prop_Type::generate( 'block' ) )
				),
		);
	}

	protected function define_default_children() {
		return array(
			Atomic_Heading::generate()
				->settings(
					array(
						'tag' => String_Prop_Type::generate( 'h3' ),
						'title' => Html_V3_Prop_Type::generate(
							array(
								'content' => String_Prop_Type::generate( __( 'Milestone title', 'hello-elementor-child' ) ),
								'children' => array(),
							)
						),
					)
				)
				->build(),
			Atomic_Paragraph::generate()
				->settings(
					array(
						'paragraph' => Html_V3_Prop_Type::generate(
							array(
								'content' => String_Prop_Type::generate( __( 'Add the milestone details here.', 'hello-elementor-child' ) ),
								'children' => array(),
							)
						),
						'tag' => String_Prop_Type::generate( 'p' ),
					)
				)
				->build(),
		);
	}

	protected function get_templates(): array {
		return array(
			'wp-inventor/elements/atomic-timeline-content' => get_stylesheet_directory() . '/inc/elementor-atomic/templates/atomic-timeline-content.html.twig',
		);
	}
}
