<?php
/**
 * Handle the Foundation Integration.
 * Filter mesh_allowed_html to include Foundation data attributes, filters
 * should be applied based on what version of Foundation CSS is loaded.
 *
 * @package    Mesh
 * @subpackage Integrations
 * @since 1.2.2
 */

namespace Mesh\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Foundation
 *
 * @package Mesh\Integrations
 */
class Foundation {

	/**
	 * Foundation constructor.
	 */
	public function __construct() {
		// Allow more attributes in Mesh
		add_filter( 'mesh_allowed_html', array( $this, 'mesh_allowed_html' ) );
	}

	/**
	 * @param $mesh_allowed_html
	 * @return array
	 */
	public function mesh_allowed_html( $mesh_allowed_html ) {
		// check foundation version

		if ( 5 ) {
			$foundation_allowed_html = $this->get_foundation_5_allowed_html();
		} else {
			$foundation_allowed_html = $this->get_foundation_6_allowed_html();
		}

		$mesh_allowed_html = array_merge( $mesh_allowed_html, $foundation_allowed_html );

		return $mesh_allowed_html;
	}

	/**
	 * @return array
	 */
	public function get_foundation_5_allowed_html() {
		$foundation_5_allowed_html = array();

		$foundation_5_allowed_html = apply_filters( 'mesh_foundation_5_allowed_html', $foundation_5_allowed_html );

		return $foundation_5_allowed_html;
	}

	/**
	 * @return array
	 */
	public function get_foundation_6_allowed_html() {
		$foundation_6_allowed_html = array();

		// Buttons
		$foundation_6_allowed_html['button']['data-toggle'] = array();
		$foundation_6_allowed_html['button']['data-close'] = array();

		// Accordion
		$foundation_6_allowed_html['ul']['data-accordion'] = array();
		$foundation_6_allowed_html['ul']['data-multi-expand'] = array();
		$foundation_6_allowed_html['ul']['data-allow-all-closed'] = array();
		$foundation_6_allowed_html['ul']['data-deep-link'] = array();
		$foundation_6_allowed_html['ul']['data-update-history'] = array();
		$foundation_6_allowed_html['ul']['data-deep-link-smudge'] = array();
		$foundation_6_allowed_html['ul']['data-deep-link-smudge-delay'] = array();
		$foundation_6_allowed_html['ul']['data-slide-speed'] = array();
		$foundation_6_allowed_html['li']['data-accordion-item'] = array();
		$foundation_6_allowed_html['div']['data-tab-content'] = array();

		// Callout
		$foundation_6_allowed_html['div']['data-closable'] = array();

		// Dropdown
		$foundation_6_allowed_html['div']['data-dropdown'] = array();
		$foundation_6_allowed_html['div']['data-hover'] = array();
		$foundation_6_allowed_html['div']['data-hover-pane'] = array();
		$foundation_6_allowed_html['div']['data-position'] = array();
		$foundation_6_allowed_html['div']['data-alignment'] = array();
		$foundation_6_allowed_html['div']['data-auto-focus'] = array();

		// Reveal
		$foundation_6_allowed_html['button']['data-open'] = array();
		$foundation_6_allowed_html['div']['data-reveal'] = array();
		$foundation_6_allowed_html['div']['data-overlay'] = array();
		$foundation_6_allowed_html['div']['data-animation-in'] = array();
		$foundation_6_allowed_html['div']['data-animation-out'] = array();

		// Tabs
		$foundation_6_allowed_html['div']['data-tabs-content'] = array();
		$foundation_6_allowed_html['ul']['data-tabs'] = array();
		$foundation_6_allowed_html['ul']['data-active-collapse'] = array();
		$foundation_6_allowed_html['ul']['data-update-history'] = array();
		$foundation_6_allowed_html['a']['data-tabs-target'] = array();

		// Responsive Accordion Tabs
		$foundation_6_allowed_html['ul']['data-responsive-accordion-tabs'] = array();

		// Tooltip
		$foundation_6_allowed_html['span']['data-tooltip'] = array();
		$foundation_6_allowed_html['span']['data-clock-open'] = array();
		$foundation_6_allowed_html['span']['data-disable-hover'] = array();
		$foundation_6_allowed_html['span']['data-position'] = array();
		$foundation_6_allowed_html['span']['data-alignment'] = array();
		$foundation_6_allowed_html['button']['data-tooltip'] = array();
		$foundation_6_allowed_html['button']['data-clock-open'] = array();
		$foundation_6_allowed_html['button']['data-disable-hover'] = array();
		$foundation_6_allowed_html['button']['data-position'] = array();
		$foundation_6_allowed_html['button']['data-alignment'] = array();

		// Equalizer
		$foundation_6_allowed_html['div']['data-equalizer'] = array();
		$foundation_6_allowed_html['div']['data-equalizer-on'] = array();
		$foundation_6_allowed_html['div']['data-equalizer-on-stack'] = array();
		$foundation_6_allowed_html['div']['data-equalizer-watch'] = array();
		$foundation_6_allowed_html['div']['data-equalizer-by-row'] = array();

		// Interchange
		$foundation_6_allowed_html['img']['data-interchange'] = array();
		$foundation_6_allowed_html['div']['data-interchange'] = array();

		// Toggler
		$foundation_6_allowed_html['ul']['data-toggler'] = array();
		$foundation_6_allowed_html['div']['data-toggler'] = array();
		$foundation_6_allowed_html['div']['data-animate'] = array();
		$foundation_6_allowed_html['div']['data-closable'] = array();

		// Sticky
		$foundation_6_allowed_html['div']['data-sticky-container'] = array();
		$foundation_6_allowed_html['div']['data-sticky'] = array();
		$foundation_6_allowed_html['div']['data-stick-to'] = array();
		$foundation_6_allowed_html['div']['data-anchor'] = array();
		$foundation_6_allowed_html['div']['data-top-anchor'] = array();
		$foundation_6_allowed_html['div']['data-btm-anchor'] = array();
		$foundation_6_allowed_html['div']['data-options'] = array();
		$foundation_6_allowed_html['div']['data-margin-top'] = array();
		$foundation_6_allowed_html['div']['data-margin-bottom'] = array();
		$foundation_6_allowed_html['div']['data-sticky-class'] = array();
		$foundation_6_allowed_html['div']['data-container-class'] = array();
		$foundation_6_allowed_html['div']['data-check-every'] = array();

		$foundation_6_allowed_html = apply_filters( 'mesh_foundation_6_allowed_html', $foundation_6_allowed_html );

		return $foundation_6_allowed_html;
	}
}

$foundation = new Foundation();
