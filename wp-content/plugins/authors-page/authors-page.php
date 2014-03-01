<?php
	/*
	Plugin Name: Authors Page
	Description: Displays all users of a certain role on one page in a table showing their avatars and usernames and also linking to their detailed information.
	Author: Aimbox
	Author URI: http://aimbox.com
	Depends: WP Biographia
	Version: 1.2
	*/

	register_activation_hook(__FILE__, 'uvd_aup_install'); 
	register_deactivation_hook( __FILE__, 'uvd_aup_remove' );

	function uvd_aup_install()
	{	
		$html = CreatePageContent();

		$the_page_title = 'Authors Page';

		// Create post object
		$_p = array();
		$_p['post_title'] = $the_page_title;
		$_p['post_content'] = $html;
		$_p['post_status'] = 'publish';
		$_p['post_type'] = 'page';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';
		$_p['post_category'] = array(1); // the default 'Uncatrgorised'

		// Insert the post into the database
		$the_page_id = wp_insert_post($_p);

		add_option('uvd_aup_page_id', $the_page_id);
	}

	function CreatePageContent() {
		$Contents = array();
		$Contents[] = "<h1>All</h1>\r\n[authors_page]";
		$UserRoles = get_roles();
		foreach( $UserRoles as $RoleKey =>$RoleName ) {
			$Contents[] = "<h1>$RoleName</h1>\r\n[authors_page role=$RoleKey]";
		}
		$Content = join("\r\n\r\n",$Contents);
		return $Content;
	}

	function uvd_aup_remove()
	{
		//$the_page_id = get_option('uvd_aup_page_id');
		//wp_delete_post($the_page_id);
		delete_option('uvd_aup_page_id');
	}

	function uvd_aup_render_authors_page( $role = null )
	{
		global $wp_query;

		//set valid role
		$roles = get_roles_keys();
		$role_lower = strtolower( $role );
		$roles_lower = array_map('strtolower',$roles);
		if ( !in_array( $role_lower, $roles_lower ) ) $role = '';

		$Permalink = get_permalink(); 
		if (isset($wp_query->query_vars['author_login']) && $author = get_user_by('login',urldecode($wp_query->query_vars['author_login'])))
		{
			$ShowAuthorBiography = true;
			if( !empty( $role ) )  {
				$AuthorRoles = $author->roles;
				$AuthorRolesLower = array_map('strtolower',$AuthorRoles);
				if( !in_array($role_lower, $AuthorRolesLower )) $ShowAuthorBiography = false;
			}
			
			if( $ShowAuthorBiography ) {
					$Back .= '<a href="'.$Permalink.'">&lt;&lt;Back</a>';
					$html = $Back;
					$html .= do_shortcode( '[wp_biographia user='.urldecode($wp_query->query_vars['author_login']).']' );
					return $html;
			}
		}


		$args = array(
			'blog_id'		=> $GLOBALS['blog_id'],
			'role'			=> $role,
			'orderby'		=> 'post_count',
			'order'			=> 'DESC',
		);

		$authors = get_users( $args );
		$clear_css = 'border:none; background:transparent; border:none; margin:0; padding:0;';

		$html = '<div style="'.$clear_css.'  display:block;" ><table style="'.$clear_css.' width:100%;" ><tbody style="'.$clear_css.'">';

		$max_column_count = 4;
		$columns_count = 0;

		foreach ( $authors as $author) {
			$columns_count++;
			if ( $columns_count % $max_column_count == 1 ) {
				$html .= '<tr style="'.$clear_css.'" >';
			}

			$html .= '<td style="'.$clear_css.' width:25%; text-align:center; padding-bottom:1em;">';
			$html .= '<a href="'.$Permalink.'?author_login='.$author->user_login.'" >' .get_avatar($author->ID).'<br>'.$author->display_name.'</a></td>';
			//$html .= '<a href="'.get_author_posts_url( $author->ID ).'">' .get_avatar($author->ID).'<br>'.$author->display_name.'</a></td>';

			if ( $columns_count % $max_column_count == 0 || $columns_count == sizeof($authors) ) {
				$html .= '</tr>';
			}
		}

		$html .= '</tbody></table></div>';
		return $html;
	}

	add_filter('query_vars', 'my_add_query_vars');


	function my_add_query_vars($public_query_vars ) {
		$public_query_vars[] = 'author_login';
		return $public_query_vars;
	} 

	add_shortcode( 'authors_page', 'uvd_aup_shortcode' );
	function uvd_aup_shortcode( $atts )
	{
		$role = isset( $atts['role']) ? $atts['role'] : null;
		return uvd_aup_render_authors_page( $role );
	}

	function get_roles_keys() {
		$Roles = get_roles();
		$RoleKeys = array_keys( $Roles );
		return $RoleKeys;
	}

	function get_roles() {
		/**
		* @var WP_Roles
		*/
		global $wp_roles;
		if ( !isset( $wp_roles ) ) $wp_roles = new WP_Roles();
		$Roles = $wp_roles->get_names();
		return $Roles;
	}
