<?php
/**
 * Display links to active plugins/extensions settings' pages: Quick Cache 2013/Pro.
 *
 * @package    Multisite Toolbar Additions
 * @subpackage Plugin/Extension Support
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/multisite-toolbar-additions/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.6.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.6.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Quick Cache 2013/Pro (free & premium, by WebSharks, Inc.)
 *
 * @since 1.6.0
 *
 * @uses  is_multisite()
 */
/** For Multisite display stuff in 'network_admin' */
if ( is_multisite() ) {

	$mstba_tb_items[ 'networkext_quickcache' ] = array(
		'parent' => $networkextgroup,
		'title'  => __( 'Quick Cache Options', 'multisite-toolbar-additions' ),
		'href'   => network_admin_url( 'admin.php?page=quick_cache' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Quick Cache Options', 'multisite-toolbar-additions' ) )
	);

}

	/** Otherwise, display stuff in a sub site admin */
else {

	$mstba_tb_items[ 'siteext_quickcache' ] = array(
		'parent' => $siteextgroup,
		'title'  => __( 'Quick Cache Options', 'multisite-toolbar-additions' ),
		'href'   => admin_url( 'admin.php?page=quick_cache' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Quick Cache Options', 'multisite-toolbar-additions' ) )
	);

}  // end-if multisite check