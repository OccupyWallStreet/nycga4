<?php
/**
 * Display links to active plugins/extensions settings' pages: Smart CRON Tools.
 *
 * @package    Multisite Toolbar Additions
 * @subpackage Plugin/Extension Support
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2013-2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/multisite-toolbar-additions/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.4.1
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.4.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Smart CRON Tools (premium, by Smart Plugins/ Milan Petrovic)
 *
 * @since 1.4.1
 *
 * @uses  is_multisite()
 * @uses  current_user_can()
 */
/** Multisite check */
if ( is_multisite() && current_user_can( 'manage_network' ) ) {

	/** List the network menu items */
	$mstba_tb_items[ 'networkext_smartcrontools' ] = array(
		'parent' => $networkextgroup,
		'title'  => __( 'Smart CRON Tools', 'multisite-toolbar-additions' ),
		'href'   => network_admin_url( 'settings.php?page=smart-cron-tools' ),
		'meta'   => array( 'target' => '', 'title' => _x( 'Smart CRON Tools - List of Jobs', 'Translators: For the tooltip', 'multisite-toolbar-additions' ) )
	);

		$mstba_tb_items[ 'networkext_smartcrontools_schedules' ] = array(
			'parent' => $networkext_smartcrontools,
			'title'  => __( 'Schedules', 'multisite-toolbar-additions' ),
			'href'   => network_admin_url( 'settings.php?page=smart-cron-tools&tab=schedules' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Schedules', 'multisite-toolbar-additions' ) )
		);

		$mstba_tb_items[ 'networkext_smartcrontools_about' ] = array(
			'parent' => $networkext_smartcrontools,
			'title'  => __( 'About', 'multisite-toolbar-additions' ),
			'href'   => network_admin_url( 'settings.php?page=smart-cron-tools&tab=about' ),
			'meta'   => array( 'target' => '', 'title' => __( 'About', 'multisite-toolbar-additions' ) )
		);

		$mstba_tb_items[ 'networkext_smartcrontools_support' ] = array(
			'parent' => $networkext_smartcrontools,
			'title'  => __( 'Support Forum', 'multisite-toolbar-additions' ),
			'href'   => esc_url( 'http://www.millan.rs/forums/forum/smart/smart-cron-tools/' ),
			'meta'   => array( 'title' => __( 'Support Forum', 'multisite-toolbar-additions' ) )
		);

}  // end-if is_multisite() & cap check

if ( current_user_can( 'activate_plugins' ) ) {

	/** List the (site) menu items */
	$mstba_tb_items[ 'siteext_smartcrontools' ] = array(
		'parent' => $siteextgroup,
		'title'  => __( 'Smart CRON Tools', 'multisite-toolbar-additions' ),
		'href'   => admin_url( 'options-general.php?page=smart-cron-tools&tab=jobs' ),
		'meta'   => array( 'target' => '', 'title' => _x( 'Smart CRON Tools - List of Jobs', 'Translators: For the tooltip', 'multisite-toolbar-additions' ) )
	);

		$mstba_tb_items[ 'siteext_smartcrontools_schedules' ] = array(
			'parent' => $siteext_smartcrontools,
			'title'  => __( 'Schedules', 'multisite-toolbar-additions' ),
			'href'   => admin_url( 'options-general.php?page=smart-cron-tools&tab=schedules' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Schedules', 'multisite-toolbar-additions' ) )
		);

		$mstba_tb_items[ 'siteext_smartcrontools_about' ] = array(
			'parent' => $siteext_smartcrontools,
			'title'  => __( 'About', 'multisite-toolbar-additions' ),
			'href'   => admin_url( 'options-general.php?page=smart-cron-tools&tab=about' ),
			'meta'   => array( 'target' => '', 'title' => __( 'About', 'multisite-toolbar-additions' ) )
		);

		$mstba_tb_items[ 'siteext_smartcrontools_support' ] = array(
			'parent' => $siteext_smartcrontools,
			'title'  => __( 'Support Forum', 'multisite-toolbar-additions' ),
			'href'   => esc_url( 'http://www.millan.rs/forums/forum/smart/smart-cron-tools/' ),
			'meta'   => array( 'title' => __( 'Support Forum', 'multisite-toolbar-additions' ) )
		);

}  // end-if cap check