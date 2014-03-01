<?php
/*
Plugin Name: Disable XMLRPC
Description: Disables xmlrpc.php for Wordpress
Author: Pea, Tech Ops
Version: 1.0
Author URI: http://tech.nycga.net
*/

/*
You can let your webserver handle denied requests to xmlrpc.php by adding the following to your .htacess file:

<IfModule mod_alias.c>
RedirectMatch 403 /xmlrpc.php
</IfModule>

or

<Files xmlrpc.php>
Order Deny,Allow
Deny from all
</Files>
*/

/* Turn off XML-RPC functionality */
add_filter( 'xmlrpc_enabled', '__return_false' );

/* Hide xmlrpc.php in HTTP response */
add_filter( 'wp_headers', 'nycga_remove_x_pingback' );
function nycga_remove_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}

?>