<?php
/**
 * Inventor Atomic Timeline Element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/elementor-atomic/atomic-timeline-item.php';

use Elementor\Modules\AtomicWidgets\Controls\Section;
use Elementor\Modules\AtomicWidgets\Controls\Types\Number_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Switch_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Text_Control;
use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Element_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Element_Template;
use Elementor\Modules\AtomicWidgets\PropTypes\Attributes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Classes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Boolean_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Number_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Style_Definition;
use Elementor\Modules\AtomicWidgets\Styles\Style_Variant;
use Elementor\Modules\Components\PropTypes\Overridable_Prop_Type;

class WP_Inventor_Atomic_Timeline extends Atomic_Element_Base {
	use Has_Element_Template;

	const BASE_STYLE_KEY = 'base';
	const ELEMENT_TYPE_TIMELINE_ITEM = 'e-inventor-timeline-item';

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		$this->meta( 'is_container', true );
	}

	public static function get_type() {
		return 'e-inventor-timeline';
	}

	public static function get_element_type(): string {
		return 'e-inventor-timeline';
	}

	public function get_title() {
		return esc_html__( 'Inventor Atomic Timeline', 'hello-elementor-child' );
	}

	public function get_keywords() {
		return array( 'ato', 'atom', 'atomic', 'timeline', 'milestone', 'roadmap' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	protected static function define_props_schema(): array {
		return array(
			'classes' => Classes_Prop_Type::make()->default( array() ),
			'default-active-item' => Number_Prop_Type::make()
				->default( 0 )
				->meta( Overridable_Prop_Type::ignore() ),
			'start-from-left' => Boolean_Prop_Type::make()->default( false ),
			'icon-content-gap' => Number_Prop_Type::make()->default( 72 ),
			'content-vertical-offset' => Number_Prop_Type::make()->default( 0 ),
			'attributes' => Attributes_Prop_Type::make()->meta( Overridable_Prop_Type::ignore() ),
		);
	}

	protected function define_atomic_controls(): array {
		return array(
			Section::make()
				->set_label( __( 'Layout', 'hello-elementor-child' ) )
				->set_id( 'layout' )
				->set_items( array(
					Switch_Control::bind_to( 'start-from-left' )
						->set_label( __( 'Start From Left', 'hello-elementor-child' ) )
						->set_meta( array( 'layout' => 'two-columns' ) ),
					Number_Control::bind_to( 'icon-content-gap' )
						->set_label( __( 'Icon to Content Gap (px)', 'hello-elementor-child' ) )
						->set_meta( array( 'layout' => 'two-columns' ) ),
					Number_Control::bind_to( 'content-vertical-offset' )
						->set_label( __( 'Content Vertical Offset (px)', 'hello-elementor-child' ) )
						->set_meta( array( 'layout' => 'two-columns' ) ),
				) ),
			Section::make()
				->set_label( __( 'Settings', 'hello-elementor-child' ) )
				->set_id( 'settings' )
				->set_items( array(
					Number_Control::bind_to( 'default-active-item' )
						->set_label( __( 'Default Active Milestone', 'hello-elementor-child' ) ),
					Text_Control::bind_to( '_cssid' )
						->set_label( __( 'ID', 'hello-elementor-child' ) )
						->set_meta( $this->get_css_id_control_meta() ),
				) ),
		);
	}

	protected function define_base_styles(): array {
		$styles = array(
			'display' => String_Prop_Type::generate( 'block' ),
		);

		return array(
			self::BASE_STYLE_KEY => Style_Definition::make()
				->add_variant(
					Style_Variant::make()->add_props( $styles )
				),
		);
	}

	protected function define_default_children() {
		$default_items = 3;
		$timeline_items = array();

		foreach ( range( 1, $default_items ) as $index ) {
			$timeline_items[] = WP_Inventor_Atomic_Timeline_Item::generate()
				->editor_settings(
					array(
						'title' => sprintf( 'Timeline item %d', $index ),
						'initial_position' => $index,
					)
				)
				->build();
		}

		return $timeline_items;
	}

	protected function get_templates(): array {
		return array(
			'wp-inventor/elements/atomic-timeline' => get_stylesheet_directory() . '/inc/elementor-atomic/templates/atomic-timeline.html.twig',
		);
	}

	protected function define_allowed_child_types() {
		return array( self::ELEMENT_TYPE_TIMELINE_ITEM, 'container' );
	}

	protected function define_render_context(): array {
		return array(
			array(
				'context' => array(
					'default-active-item' => $this->get_atomic_setting( 'default-active-item' ),
				),
			),
		);
	}
}
