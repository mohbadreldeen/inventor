<?php
/**
 * Inventor Data Table Widget
 *
 * Adds a customizable table widget for Elementor with editable headers and rows.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WP_Inventor_Data_Table_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'inventor-data-table';
	}

	public function get_title() {
		return esc_html__( 'Inventor Data Table', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function get_keywords() {
		return array( 'table', 'rows', 'columns', 'grid', 'inventor' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_table_content',
			array(
				'label' => esc_html__( 'Table', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$header_repeater = new \Elementor\Repeater();
		$header_repeater->add_control(
			'header_text',
			array(
				'label'       => esc_html__( 'Header Label', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Column', 'hello-elementor-child' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'headers',
			array(
				'label'       => esc_html__( 'Headers', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $header_repeater->get_controls(),
				'default'     => array(
					array( 'header_text' => esc_html__( 'Feature', 'hello-elementor-child' ) ),
					array( 'header_text' => esc_html__( 'Standard', 'hello-elementor-child' ) ),
					array( 'header_text' => esc_html__( 'Premium', 'hello-elementor-child' ) ),
				),
				'title_field' => '{{{ header_text }}}',
			)
		);

		$this->add_control(
			'column_delimiter',
			array(
				'label'       => esc_html__( 'Column Delimiter', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '|',
				'maxlength'   => 3,
				'description' => esc_html__( 'Used to split each row into cells. Example: Value 1 | Value 2 | Value 3', 'hello-elementor-child' ),
			)
		);

		$row_repeater = new \Elementor\Repeater();
		$row_repeater->add_control(
			'row_values',
			array(
				'label'       => esc_html__( 'Row Values', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Item | Included | Included', 'hello-elementor-child' ),
				'rows'        => 2,
				'label_block' => true,
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => esc_html__( 'Rows', 'hello-elementor-child' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $row_repeater->get_controls(),
				'default'     => array(
					array( 'row_values' => esc_html__( 'Auto Labeling | Yes | Yes', 'hello-elementor-child' ) ),
					array( 'row_values' => esc_html__( 'Nutrition Facts | No | Yes', 'hello-elementor-child' ) ),
					array( 'row_values' => esc_html__( 'Live Data | No | Yes', 'hello-elementor-child' ) ),
				),
				'title_field' => '{{{ row_values }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => esc_html__( 'Header', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__head-cell' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'header_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__head-cell' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .inventor-data-table .inventor-data-table__head-cell',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__head-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_rows_style',
			array(
				'label' => esc_html__( 'Rows', 'hello-elementor-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_bg_color',
			array(
				'label'     => esc_html__( 'Row Background', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__cell' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__cell' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'row_typography',
				'selector' => '{{WRAPPER}} .inventor-data-table .inventor-data-table__cell',
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => esc_html__( 'Cell Padding', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'row_border_style',
			array(
				'label'     => esc_html__( 'Horizontal Border Style', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'none'   => esc_html__( 'None', 'hello-elementor-child' ),
					'solid'  => esc_html__( 'Solid', 'hello-elementor-child' ),
					'dashed' => esc_html__( 'Dashed', 'hello-elementor-child' ),
					'dotted' => esc_html__( 'Dotted', 'hello-elementor-child' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__head-cell, {{WRAPPER}} .inventor-data-table .inventor-data-table__cell' => 'border-left-style: none; border-right-style: none; border-top-style: none; border-bottom-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_border_width',
			array(
				'label'      => esc_html__( 'Horizontal Border Width', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__head-cell, {{WRAPPER}} .inventor-data-table .inventor-data-table__cell' => 'border-left-width: 0; border-right-width: 0; border-top-width: 0; border-bottom-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'row_border_color',
			array(
				'label'     => esc_html__( 'Horizontal Border Color', 'hello-elementor-child' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#d9d9d9',
				'selectors' => array(
					'{{WRAPPER}} .inventor-data-table .inventor-data-table__head-cell, {{WRAPPER}} .inventor-data-table .inventor-data-table__cell' => 'border-top-color: transparent; border-bottom-color: {{VALUE}}; border-left-color: transparent; border-right-color: transparent;',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'table_outer_border',
				'label'    => esc_html__( 'Table Outer Border', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .inventor-data-table',
			)
		);

		$this->add_responsive_control(
			'table_outer_border_radius',
			array(
				'label'      => esc_html__( 'Table Border Radius', 'hello-elementor-child' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inventor-data-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->end_controls_section();
	}

	private function parse_row_values( $raw_row_values, $delimiter ) {
		if ( '' === trim( (string) $raw_row_values ) ) {
			return array();
		}

		$delimiter = ( '' !== trim( (string) $delimiter ) ) ? $delimiter : '|';
		$cells     = explode( $delimiter, (string) $raw_row_values );

		return array_map( 'trim', $cells );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$headers   = isset( $settings['headers'] ) && is_array( $settings['headers'] ) ? $settings['headers'] : array();
		$rows      = isset( $settings['rows'] ) && is_array( $settings['rows'] ) ? $settings['rows'] : array();
		$delimiter = isset( $settings['column_delimiter'] ) ? (string) $settings['column_delimiter'] : '|';

		if ( empty( $headers ) && empty( $rows ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="inventor-data-table__empty">' . esc_html__( 'Add headers and rows to display the table.', 'hello-elementor-child' ) . '</div>';
			}
			return;
		}

		$column_count = 0;
		if ( ! empty( $headers ) ) {
			$column_count = count( $headers );
		}

		$parsed_rows = array();
		foreach ( $rows as $row ) {
			$row_values   = isset( $row['row_values'] ) ? $row['row_values'] : '';
			$parsed_cells = $this->parse_row_values( $row_values, $delimiter );

			if ( count( $parsed_cells ) > $column_count ) {
				$column_count = count( $parsed_cells );
			}

			$parsed_rows[] = $parsed_cells;
		}

		if ( $column_count < 1 ) {
			$column_count = 1;
		}

		echo '<div class="inventor-data-table" role="table">';

		if ( ! empty( $headers ) ) {
			echo '<div class="inventor-data-table__head" role="rowgroup"><div class="inventor-data-table__row inventor-data-table__row--head" role="row">';
			for ( $i = 0; $i < $column_count; $i++ ) {
				$header_label = isset( $headers[ $i ]['header_text'] ) ? $headers[ $i ]['header_text'] : '';
				echo '<div class="inventor-data-table__head-cell" role="columnheader">' . esc_html( $header_label ) . '</div>';
			}
			echo '</div></div>';
		}

		echo '<div class="inventor-data-table__body" role="rowgroup">';
		foreach ( $parsed_rows as $cells ) {
			echo '<div class="inventor-data-table__row" role="row">';
			for ( $i = 0; $i < $column_count; $i++ ) {
				$cell_value = isset( $cells[ $i ] ) ? $cells[ $i ] : '';
				echo '<div class="inventor-data-table__cell" role="cell">' . wp_kses_post( $cell_value ) . '</div>';
			}
			echo '</div>';
		}
		echo '</div>';

		echo '</div>';
	}
}

