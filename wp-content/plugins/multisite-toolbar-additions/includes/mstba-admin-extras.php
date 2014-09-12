<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    Multisite Toolbar Additions
 * @subpackage Admin
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
 * @since 1.4.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting internal plugin helper values.
 *
 * @since 1.7.0
 *
 * @uses  get_locale()
 */
function ddw_mstba_info_values() {

	$mstba_info = array(

		'url_translate'     => 'http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/multisite-toolbar-additions',
		'url_wporg_faq'     => 'http://wordpress.org/plugins/multisite-toolbar-additions/faq/',
		'url_wporg_forum'   => 'http://wordpress.org/support/plugin/multisite-toolbar-additions',
		'url_wporg_profile' => 'http://profiles.wordpress.org/daveshine/',
		'url_wporg_more'    => 'http://wordpress.org/plugins/search.php?q=toolbar+multisite',
		'url_ddw_series'    => 'http://wordpress.org/plugins/tags/ddwtoolbar',
		'url_snippets'      => 'https://gist.github.com/deckerweb/3498510',
		'license'           => 'GPL-2.0+',
		'url_license'       => 'http://www.opensource.org/licenses/gpl-license.php',
		'first_release'     => absint( '2012' ),
		'url_donate'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/spenden/' : 'http://genesisthemes.de/en/donate/',
		'url_plugin'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/plugins/multisite-toolbar-additions/' : 'http://genesisthemes.de/en/wp-plugins/multisite-toolbar-additions/'

	);  // end of array

	return $mstba_info;

}  // end of function ddw_mstba_info_values


/**
 * Add "Custom Menu" link to plugin page.
 *
 * @since  1.0.0
 *
 * @param  $mstba_links
 * @param  $mstba_menu_link
 *
 * @return strings Menu Admin link.
 */
function ddw_mstba_custom_menu_link( $mstba_links ) {

	/** Add link only if user can edit theme options */
	if ( current_user_can( 'edit_theme_options' ) ) {

		/** Settings Page link */
		$mstba_menu_link = sprintf(
			'<a href="%s" title="%s">%s</a>',
			admin_url( 'nav-menus.php' ),
			esc_html__( 'Setup a custom toolbar menu', 'multisite-toolbar-additions' ),
			esc_attr__( 'Custom Menu', 'multisite-toolbar-additions' )
		);
	
		/** Set the order of the links */
		array_unshift( $mstba_links, $mstba_menu_link );

		/** Display plugin settings links */
		return apply_filters( 'mstba_filter_settings_page_link', $mstba_links );

	}  // end if cap check

}  // end of function ddw_mstba_custom_menu_link


add_filter( 'plugin_row_meta', 'ddw_mstba_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page.
 *
 * @since  1.0.0
 *
 * @uses   ddw_mstba_info_values()
 *
 * @param  $mstba_links
 * @param  $mstba_file
 *
 * @return strings plugin links
 */
function ddw_mstba_plugin_links( $mstba_links, $mstba_file ) {

	/** Capability check */
	if ( ! current_user_can( 'install_plugins' ) ) {

		return $mstba_links;

	}  // end if cap check

	/** List additional links only for this plugin */
	if ( $mstba_file == MSTBA_PLUGIN_BASEDIR . 'multisite-toolbar-additions.php' ) {

		$mstba_info = (array) ddw_mstba_info_values();

		$mstba_links[] = '<a href="' . esc_url( $mstba_info[ 'url_wporg_faq' ] ) . '" target="_new" title="' . esc_html_x( 'FAQ', 'Translators: plugin page listing', 'multisite-toolbar-additions' ) . '">' . _x( 'FAQ', 'Translators: plugin page listing', 'multisite-toolbar-additions' ) . '</a>';

		$mstba_links[] = '<a href="' . esc_url( $mstba_info[ 'url_wporg_forum' ] ) . '" target="_new" title="' . esc_html_x( 'Support', 'Translators: plugin page listing', 'multisite-toolbar-additions' ) . '">' . _x( 'Support', 'Translators: plugin page listing', 'multisite-toolbar-additions' ) . '</a>';

		$mstba_links[] = '<a href="' . esc_url( $mstba_info[ 'url_snippets' ] ) . '" target="_new" title="' . esc_html__( 'Code Snippets for Customization', 'multisite-toolbar-additions' ) . '">' . __( 'Code Snippets', 'multisite-toolbar-additions' ) . '</a>';

		$mstba_links[] = '<a href="' . esc_url( $mstba_info[ 'url_translate' ] ) . '" target="_new" title="' . esc_html__( 'Translations', 'multisite-toolbar-additions' ) . '">' . __( 'Translations', 'multisite-toolbar-additions' ) . '</a>';

		$mstba_links[] = '<a href="' . esc_url( $mstba_info[ 'url_donate' ] ) . '" target="_new" title="' . esc_html__( 'Donate', 'multisite-toolbar-additions' ) . '"><strong>' . __( 'Donate', 'multisite-toolbar-additions' ) . '</strong></a>';

	}  // end if plugin links

	/** Output the links */
	return apply_filters( 'mstba_filter_plugin_links', $mstba_links );

}  // end of function ddw_mstba_plugin_links


add_action( 'admin_head-nav-menus.php', 'ddw_mstba_widgets_help_content', 15 );
/**
 * Create and display plugin help tab content.
 *
 * Load it after core help tabs on Menus admin page.
 * Some plugin menu instructions for super_admins plus general plugin info.
 *
 * @since  1.0.0
 *
 * @uses   get_current_screen()
 * @uses   is_super_admin()
 * @uses   ddw_mstba_plugin_get_data()
 * @uses   WP_Screen::add_help_tab()
 *
 * @param  $mstba_menu_area_help
 *
 * @global mixed $mstba_widgets_screen
 */
function ddw_mstba_widgets_help_content() {

	global $mstba_widgets_screen;

	$mstba_widgets_screen = get_current_screen();

	/** Display help tabs only for WordPress 3.3 or higher */
	if ( ! class_exists( 'WP_Screen' )
		|| ! $mstba_widgets_screen
		|| ! is_super_admin()
	) {

		return;

	}  // end if

	/** Add the new help tab */
	$mstba_widgets_screen->add_help_tab( array(
		'id'       => 'mstba-menus-help',
		'title'    => __( 'Multisite Toolbar Additions', 'multisite-toolbar-additions' ),
		'callback' => apply_filters( 'mstba_help_tab', 'ddw_mstba_help_tab_content' ),
	) );

}  // end of function ddw_mstba_menu_help_content


/**
 * Create and display plugin help tab content.
 *
 * @since 1.0.0
 *
 * @uses  ddw_mstba_info_values() To get some strings of info values.
 * @uses  ddw_mstba_plugin_get_data()
 */
function ddw_mstba_help_tab_content() {

	$mstba_info = (array) ddw_mstba_info_values();

	$mstba_space_helper = '<div style="height: 10px;"></div>';

	/** Content: Multisite Toolbar Additions plugin */
	echo '<h3>' . __( 'Plugin', 'multisite-toolbar-additions' ) . ': ' . __( 'Multisite Toolbar Additions', 'multisite-toolbar-additions' ) . ' <small>v' . esc_attr( ddw_mstba_plugin_get_data( 'Version' ) ) . '</small></h3>';

	/** Super Admin menu help */
	if ( defined( 'MSTBA_SUPER_ADMIN_NAV_MENU' ) && MSTBA_SUPER_ADMIN_NAV_MENU ) {

		ddw_mstba_help_content_super_admin_menu();

	}  // end if
	
	/** Restricted Site Admin menu help */
	if ( is_multisite() && ( defined( 'MSTBA_RESRICTED_ADMIN_NAV_MENU' ) && MSTBA_RESRICTED_ADMIN_NAV_MENU ) ) {

		echo $mstba_space_helper;
		
		ddw_mstba_help_content_resctricted_admin_menu();

	}  // end if

	/** Further help content */
	echo $mstba_space_helper . '<p><strong>' . __( 'Other, recommended Multisite &amp; Toolbar plugins:', 'multisite-toolbar-additions' ) . '</strong></strong>' .
		'<blockquote><p>&raquo; <a href="' . esc_url( $mstba_info[ 'url_ddw_series' ] ) . '" target="_new" title="David Decker ' . __( 'Toolbar plugin series', 'multisite-toolbar-additions' ) . ' ...">' . __( 'My Toolbar plugin series', 'multisite-toolbar-additions' ) . ' (David Decker, DECKERWEB.de)</a>' .
		'<br />&raquo; <a href="' . esc_url( $mstba_info[ 'url_wporg_more' ] ) . '" target="_new" title="' . __( 'More plugins at WordPress.org', 'multisite-toolbar-additions' ) . ' ...">' . __( 'More plugins at WordPress.org', 'multisite-toolbar-additions' ) . '</a></p></blockquote>';

	echo $mstba_space_helper . '<p><h4 style="font-size: 1.1em;">' . __( 'Important plugin links:', 'multisite-toolbar-additions' ) . '</h4>' .

		'<a class="button" href="' . esc_url( $mstba_info[ 'url_plugin' ] ) . '" target="_new" title="' . esc_html__( 'Plugin website', 'multisite-toolbar-additions' ) . '">' . __( 'Plugin website', 'multisite-toolbar-additions' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $mstba_info[ 'url_wporg_faq' ] ) . '" target="_new" title="' . esc_html_x( 'FAQ', 'Translators: help tab info', 'multisite-toolbar-additions' ) . '">' . _x( 'FAQ', 'Translators: help tab info', 'multisite-toolbar-additions' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $mstba_info[ 'url_wporg_forum' ] ) . '" target="_new" title="' . esc_html_x( 'Support', 'Translators: help tab info', 'multisite-toolbar-additions' ) . '">' . _x( 'Support', 'Translators: help tab info', 'multisite-toolbar-additions' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $mstba_info[ 'url_snippets' ] ) . '" target="_new" title="' . esc_html__( 'Code Snippets for Customization', 'multisite-toolbar-additions' ) . '">' . __( 'Code Snippets', 'multisite-toolbar-additions' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $mstba_info[ 'url_translate' ] ) . '" target="_new" title="' . esc_html__( 'Translations', 'multisite-toolbar-additions' ) . '">' . __( 'Translations', 'multisite-toolbar-additions' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $mstba_info[ 'url_donate' ] ) . '" target="_new" title="' . esc_html__( 'Donate', 'multisite-toolbar-additions' ) . '"><strong>' . __( 'Donate', 'multisite-toolbar-additions' ) . '</strong></a></p>';

	/** Set first release year */
	$release_first_year = ( '' != $mstba_info[ 'first_release' ] && date( 'Y' ) != $mstba_info[ 'first_release' ] ) ? $mstba_info[ 'first_release' ] . '&#x02013;' : '';

	echo '<p><a href="' . esc_url( $mstba_info[ 'url_license' ] ) . '" target="_new" title="' . esc_attr( $mstba_info[ 'license' ] ). '">' . esc_attr( $mstba_info[ 'license' ] ). '</a> &#x000A9; ' . $release_first_year . date( 'Y' ) . ' <a href="' . esc_url( ddw_mstba_plugin_get_data( 'AuthorURI' ) ) . '" target="_new" title="' . esc_attr__( ddw_mstba_plugin_get_data( 'Author' ) ) . '">' . esc_attr__( ddw_mstba_plugin_get_data( 'Author' ) ) . '</a></p>';

}  // end of function ddw_mstba_help_tab_content


/**
 * Help content part: for super admin menu.
 *
 * @since 1.0.0
 */
function ddw_mstba_help_content_super_admin_menu() {

	echo '<h4 style="font-size: 1.1em;">' . sprintf(
			__( 'Special custom menu for %s', 'multisite-toolbar-additions' ),
			'<em>' . __( 'Super Admins', 'multisite-toolbar-additions' ) . '</em>'
		) . ':</h4>';

	echo '<blockquote><p>' . __( 'All menu items via a Custom Menu here - and at all other places in the Toolbar (a.k.a. Admin Bar) - are only visible and accessable for Super Admins. That means in a Multisite Environment all Admins who can manage the Network. In regular WordPress (single) installs these are users with the Administrator user role.', 'multisite-toolbar-additions' ) . '</p></blockquote>' .
	'<blockquote><p><strong>' . __( 'Added Menu Location by the plugin - only for Super Admins:', 'multisite-toolbar-additions' ) . ' "' . __( 'Multisite Toolbar Menu', 'multisite-toolbar-additions' ) . '" &mdash; <em>' . __( 'How to use it?', 'multisite-toolbar-additions' ) . '</em></strong></p></blockquote>' .
	'<blockquote><ul>' . 
		'<li>' . sprintf( __( 'Create a new menu, set a name like %s', 'multisite-toolbar-additions' ), '<code>Super Admin Toolbar</code>' ) . '</li>' .
		'<li>' . __( 'Setup your links, might mostly be custom links, or any other...', 'multisite-toolbar-additions' ) . '</li>' .
		'<li>' . __( 'Save the new menu to the plugin\'s menu location. That\'s it :)', 'multisite-toolbar-additions' ) . '</li>' .
	'<ul></blockquote>' .
	'<blockquote><p><em>' . __( 'Please note:', 'multisite-toolbar-additions' ) . '</em> ' . __( 'Every parent item = one main toolbar entry! So best would be to only use one parent item and set all other items as children.', 'multisite-toolbar-additions' ) . ' (<a href="https://www.dropbox.com/s/7u83c0g5ehk4ozq/screenshot-5.png" target="_new">' . __( 'See also this screenshot.', 'multisite-toolbar-additions' ) . '</a>)' .
		'<br />' . __( 'Also, only Super Admins can edit this menu, all other users/ roles will be blocked!', 'multisite-toolbar-additions' ) . '</p></blockquote>';

}  // end of function ddw_mstba_help_content_super_admin_menu


/**
 * Help content part: for restricted site admin menu.
 *
 * @since 1.0.0
 */
function ddw_mstba_help_content_resctricted_admin_menu() {

	echo '<h4 style="font-size: 1.1em;">' . sprintf(
			__( 'In Multisite: Special restricted custom menu for %s', 'multisite-toolbar-additions' ),
			'<em>' . __( 'Site Admins', 'multisite-toolbar-additions' ) . '</em>'
		) . ':</h4>';

	echo '<blockquote><p>' . __( 'This custom menu is almost identical to the custom menu for Super Admins (see above), with two differences', 'multisite-toolbar-additions' ) . ':</p></blockquote>';

	echo '<blockquote><ul>' .
			'<li>' . __( 'Only Super Admins can setup and edit this menu', 'multisite-toolbar-additions' ) . '</li>' .
			'<li>' . __( 'Site Admins can see this menu but are not able to edit it', 'multisite-toolbar-additions' ) . '</li>' .
		'<ul></blockquote>';

}  // end of function ddw_mstba_help_content_resctricted_admin_menu


add_action( 'after_menu_locations_table', 'ddw_mstba_help_info_menu_locations' );
/**
 * Help info content on "Menu Locations" tab on nav-menus.php.
 *
 * @since 1.7.0
 *
 * @uses  is_super_admin()
 * @uses  is_multisite()
 * @uses  ddw_mstba_string_super_admin_menu_location()
 * @uses  ddw_mstba_string_restricted_admin_menu_location()
 */
function ddw_mstba_help_info_menu_locations() {

	/** Bail early if no Super Admin */
	if ( ! is_super_admin()
		|| (
			( defined( 'MSTBA_SUPER_ADMIN_NAV_MENU' ) && ! MSTBA_SUPER_ADMIN_NAV_MENU )
				&& ( defined( 'MSTBA_RESRICTED_ADMIN_NAV_MENU' ) && ! MSTBA_RESRICTED_ADMIN_NAV_MENU )
			)
	) {

		return;

	}  // end if

	$super_menu = ( defined( 'MSTBA_SUPER_ADMIN_NAV_MENU' ) && MSTBA_SUPER_ADMIN_NAV_MENU ) ? TRUE : FALSE;
	$admin_menu = ( is_multisite() && ( defined( 'MSTBA_RESRICTED_ADMIN_NAV_MENU' ) && MSTBA_RESRICTED_ADMIN_NAV_MENU ) ) ? TRUE : FALSE;

	$output = sprintf(
		'<br />&nbsp;<p>' . __( '%s only for Super Admins.', 'multisite-toolbar-additions' ) . ' ' . __( 'This is provided by the plugin %s.', 'multisite-toolbar-additions' ) . '</p>',
		( $super_menu && $admin_menu ) ? _x( 'The following menu locations above are', 'plural (in Multisite)', 'multisite-toolbar-additions' ) : _x( 'The following menu location above is', 'singular (non-Multisite)', 'multisite-toolbar-additions' ),
		'<em>' . __( 'Multisite Toolbar Additions', 'multisite-toolbar-additions' ) . '</em>'
	);

	$output .= sprintf(
		'<p>%s%s</p>',
		'&rarr; ' . ddw_mstba_string_super_admin_menu_location(),
		( $admin_menu ) ? '<br />&rarr; ' . ddw_mstba_string_restricted_admin_menu_location() : ''
	);

	$output .= sprintf(
		'<p class="description">%s</p>',
		__( 'See help tab on top right corner for more usage instructions.', 'multisite-toolbar-additions' )
	);


	echo $output;

}  // end of function ddw_mstba_help_info_menu_locations