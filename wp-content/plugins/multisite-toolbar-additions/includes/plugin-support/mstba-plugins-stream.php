<?php
/**
 * Display links to active plugins/extensions settings' pages: Stream.
 *
 * @package    Multisite Toolbar Additions
 * @subpackage Plugin/Extension Support
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/multisite-toolbar-additions/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.7.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.7.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Stream (free, by X-Team)
 *
 * @since 1.7.0
 */
$mstba_tb_items[ 'siteext_stream' ] = array(
	'parent' => $siteextgroup,
	'title'  => __( 'Activity Streams', 'multisite-toolbar-additions' ),
	'href'   => admin_url( 'admin.php?page=wp_stream' ),
	'meta'   => array(
		'target' => '',
		'title'  => _x( 'Site Streams - Logged Activity', 'Translators: For the tooltip', 'multisite-toolbar-additions' )
	)
);

	$mstba_tb_items[ 'siteext_stream_view' ] = array(
		'parent' => $siteext_stream,
		'title'  => __( 'View Streams', 'multisite-toolbar-additions' ),
		'href'   => admin_url( 'admin.php?page=wp_stream' ),
		'meta'   => array(
			'target' => '',
			'title'  => __( 'View Streams', 'multisite-toolbar-additions' )
		)
	);

	$mstba_tb_items[ 'siteext_stream_settings' ] = array(
		'parent' => $siteext_stream,
		'title'  => __( 'Settings', 'multisite-toolbar-additions' ),
		'href'   => admin_url( 'admin.php?page=wp_stream_settings' ),
		'meta'   => array(
			'target' => '',
			'title'  => __( 'Settings', 'multisite-toolbar-additions' )
		)
	);

	$mstba_tb_items[ 'siteext_stream_connectors' ] = array(
		'parent' => $siteext_stream,
		'title'  => __( 'Connectors', 'multisite-toolbar-additions' ),
		'href'   => admin_url( 'admin.php?page=wp_stream_settings&tab=connectors' ),
		'meta'   => array(
			'target' => '',
			'title'  => __( 'Connectors', 'multisite-toolbar-additions' )
		)
	);

	$mstba_tb_items[ 'siteext_stream_development' ] = array(
		'parent' => $siteext_stream,
		'title'  => __( 'Development', 'multisite-toolbar-additions' ),
		'href'   => 'https://github.com/x-team/wp-stream',
		'meta'   => array(
			'title'  => __( 'Development', 'multisite-toolbar-additions' )
		)
	);

	$mstba_tb_items[ 'siteext_stream_support' ] = array(
		'parent' => $siteext_stream,
		'title'  => __( 'Support Forum', 'multisite-toolbar-additions' ),
		'href'   => 'http://wordpress.org/support/plugin/stream',
		'meta'   => array(
			'title' => __( 'Support Forum', 'multisite-toolbar-additions' )
		)
	);