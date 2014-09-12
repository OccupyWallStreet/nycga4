<?php
/**
 * Various helper functions needed throughout the plugin.
 *
 * @package    Multisite Toolbar Additions
 * @subpackage Functions
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/multisite-toolbar-additions/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
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
 * Check for active plugin "WP German Formal" plus German locale based install.
 *
 * @since 1.7.0
 *
 * @uses  get_locale()
 *
 * @return bool TRUE if plugin "WP German Formal" is active and we are in a
 *              German locale based install, otherwise FALSE.
 */
function ddw_mstba_is_wpgermanformal() {
	
	if ( defined( 'WPGF_PLUGIN_BASEDIR' )
		&& in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) )
	) {

		return TRUE;

	}  // end if

	return FALSE;

}  // end of function ddw_mstba_is_wpgermanformal


/**
 * String for "Dashboard" - re-useable and filterable.
 *
 * @since  1.7.0
 *
 * @uses   ddw_mstba_is_wpgermanformal()
 *
 * @return array Array of varios string for "Dashboard" contexts - filterable.
 */
function ddw_mstba_string_dashboard() {

	/** Array of "Dashboard" strings */	
	$dashboard_string = apply_filters(
		'mstba_filter_string_dashboard',
		array(
			'dashboard'           => ddw_mstba_is_wpgermanformal() ? __( 'Guide', 'multisite-toolbar-additions' ) : __( 'Dashboard', 'multisite-toolbar-additions' ),
			'dashboard_main_site' => ddw_mstba_is_wpgermanformal() ? _x( 'Guide (Main Site)', 'Translators: For the tooltip', 'multisite-toolbar-additions' ) : _x( 'Dashboard (Main Site)', 'Translators: For the tooltip', 'multisite-toolbar-additions' ),
		)
	);

	/** Return the array */
	return (array) $dashboard_string;

}  // end of function ddw_mstba_string_dashboard


/**
 * String for Super Admin menu location.
 *
 * @since   1.0.0
 * @version 1.7.0
 *
 * @uses    is_multisite()
 */
function ddw_mstba_string_super_admin_menu_location() {

	/** Menu location string */
	$mstba_menu_string = sprintf(
		'<span title="%s: %s">' . esc_attr__( '%s Toolbar Menu', 'multisite-toolbar-additions' ) . '</span>',
		esc_html__( 'via Plugin', 'multisite-toolbar-additions' ),
		esc_html__( 'Multisite Toolbar Additions', 'multisite-toolbar-additions' ),
		( is_multisite() ) ? __( 'Multisite', 'multisite-toolbar-additions' ) : __( 'Site', 'multisite-toolbar-additions' )
	);

	/** Output */
	return $mstba_menu_string;
	
}  // end of function ddw_mstba_super_admin_menu_location_string


/**
 * String for restricted Site Admin menu location.
 *
 * @since 1.7.0
 */
function ddw_mstba_string_restricted_admin_menu_location() {

	/** Menu location string */
	$mstba_menu_string = sprintf(
		'<span title="%s: %s">%s (%s)</span>',
		esc_html__( 'via Plugin', 'multisite-toolbar-additions' ),
		esc_html__( 'Multisite Toolbar Additions', 'multisite-toolbar-additions' ),
		__( 'Restricted Site Admin Menu', 'multisite-toolbar-additions' ),
		__( 'Toolbar', 'multisite-toolbar-additions' )
	);

	/** Output */
	return $mstba_menu_string;

}  // end of function ddw_mstba_string_restricted_admin_menu_location


/**
 * Filterable capability for custom site admin menus.
 *    Default: 'edit_theme_options'
 *
 * @since  1.7.0
 *
 * @return string String of capability ID.
 */
function ddw_mstba_restricted_admin_menu_cap() {

	/** Set filterable cap */
	$admin_cap = apply_filters(
		'mstba_filter_restricted_admin_menu_cap',
		'edit_theme_options'
	);

	/** Return the cap */
	return esc_attr( strtolower( $admin_cap ) );

}  // end of function ddw_mstba_restricted_admin_menu_cap


/**
 * Restrict editing access of special custom "Super Admin Admin" toolbar menu.
 *
 * @since 1.7.0
 *
 * @uses  ddw_mstba_restrict_nav_menu_edit_access()
 */
function ddw_mstba_restrict_super_admin_menu_access() {

	ddw_mstba_restrict_nav_menu_edit_access(
		'mstba_menu',
		'edit_theme_options'
	);

}  // end of function ddw_mstba_restrict_super_admin_menu_access


/**
 * Restrict editing access of special custom "Restricted Admin" toolbar menu.
 *
 * @since 1.7.0
 *
 * @uses  ddw_mstba_restrict_nav_menu_edit_access()
 * @uses  ddw_mstba_restricted_admin_menu_cap()
 */
function ddw_mstba_restrict_admin_menu_access() {

	ddw_mstba_restrict_nav_menu_edit_access(
		'mstba_restricted_admin_menu',
		ddw_mstba_restricted_admin_menu_cap()
	);

}  // end of function ddw_mstba_restrict_admin_menu_access


/**
 * Get the ID of a nav menu that is set to one of our special menu locations.
 *
 * @since  1.7.0
 *
 * @uses   get_nav_menu_locations()
 *
 * @param  string $single_menu_location
 *
 * @return string String of nav menu ID if menu set to menu location, 
 *                otherwise empty string.
 */
function ddw_mstba_get_menu_id_from_menu_location( $single_menu_location ) {

	$menu_id = '';

	/** Get menu locations */
	$menu_locations = get_nav_menu_locations();

	/** Check our special location */
	if ( isset( $menu_locations[ esc_attr( $single_menu_location ) ] ) ) {

		/** Get ID of nav menu */
		$menu_id = $menu_locations[ esc_attr( $single_menu_location ) ];

	} // end if

	/** Return ID of nav menu */
	return $menu_id;

}  // end of function ddw_mstba_get_menu_id_from_menu_location


/**
 * Keep 'administrator' users from editing this special admin menu.
 *
 * NOTE I:  Eventually, the real blocking depends on (filterable)
 *          'edit_theme_options' cap.
 * NOTE II: Super admins have full access, of course! :)
 *
 * @since  1.7.0
 *
 * @uses   is_super_admin()
 * @uses   ddw_mstba_get_menu_id_from_menu_location()
 * @uses   current_user_can()
 * @uses   wp_die()
 *
 * @param  string $single_menu_location
 * @param  string $checked_capability
 *
 * @global obj $GLOBALS[ 'pagenow' ]
 */
function ddw_mstba_restrict_nav_menu_edit_access( $single_menu_location, $checked_capability ) {

	/** Bail early if current user is Super Admin */
	if ( is_super_admin() ) {

		return;

	}  // end if

	$menu_id = ddw_mstba_get_menu_id_from_menu_location( $single_menu_location );

	/**
	 * Only for admin users remove edit access to the appended restricted admin menu.
	 *  - only in edit menu context for nav-menus.php
	 *  - only for the ID of the menu appended to our menu location.
	 */
	if ( ( current_user_can( esc_attr( $checked_capability ) ) /*|| current_user_can( 'administrator' )*/ )
		&& 'nav-menus.php' === $GLOBALS[ 'pagenow' ]
		&& (
				isset( $_GET[ 'action' ] )
				&& 'edit' == $_GET[ 'action' ]
			)
		&& (
				isset( $_GET[ 'menu' ] )
				&& $menu_id == $_GET[ 'menu' ]
			)
	) {

		$mstba_deactivation_message = __( 'You have no sufficient permission to edit this special menu.', 'multisite-toolbar-additions' );

		wp_die(
			$mstba_deactivation_message,
			__( 'Plugin', 'multisite-toolbar-additions' ) . ': ' . __( 'Multisite Toolbar Additions', 'multisite-toolbar-additions' ),
			array( 'back_link' => TRUE )
		);

	}  // end if

}  // end of function ddw_mstba_restrict_edit_admin_menu