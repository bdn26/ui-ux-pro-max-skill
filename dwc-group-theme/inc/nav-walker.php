<?php
/**
 * Accessible nav menu walker -- adds ARIA attributes and submenu toggles
 * without any JS-framework dependency.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DWC_Group_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"sub-menu\" role=\"menu\">\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		$class_names = 'menu-item ' . implode( ' ', array_filter( $classes ) );

		$output .= '<li class="' . esc_attr( $class_names ) . '">';

		$atts = array(
			'href'  => ! empty( $item->url ) ? $item->url : '',
			'class' => 'menu-link',
		);
		if ( is_object( $args ) && ! empty( $args->current_menu_item_id ) && (int) $args->current_menu_item_id === (int) $item->ID ) {
			$atts['aria-current'] = 'page';
		} elseif ( in_array( 'current-menu-item', $classes, true ) ) {
			$atts['aria-current'] = 'page';
		}

		if ( $has_children ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( '' !== $value ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$output .= '<a' . $attributes . '>' . esc_html( $title ) . '</a>';

		if ( $has_children ) {
			$output .= '<button class="submenu-toggle" aria-label="' . esc_attr__( 'Show submenu', 'dwc-group' ) . '" aria-expanded="false"><span aria-hidden="true">+</span></button>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
