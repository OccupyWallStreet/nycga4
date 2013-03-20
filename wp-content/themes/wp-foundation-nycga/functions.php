<?php
/*
Author: OWS
URL: htp://nycga.net

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images, 
sidebars, comments, ect.
*/


/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function nycga_widgets_init() {

    register_sidebar(array(
        'id' => 'header1',
        'name' => 'Header Right Sidebar',
        'description' => 'Used in the header.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'id' => 'homecontent1',
        'name' => 'Homepage Left Content Widget',
        'description' => 'Used only on the homepage page template.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'id' => 'homecontent2',
        'name' => 'Homepage Right Content Widget',
        'description' => 'Used only on the homepage page template.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'id' => 'footer1',
        'name' => 'Footer Left Sidebar',
        'description' => 'Used in the footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'id' => 'footer2',
        'name' => 'Footer Middle Sidebar',
        'description' => 'Used in the footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'id' => 'footer3',
        'name' => 'Footer Right Sidebar',
        'description' => 'Used in the footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
    
} // don't remove this bracket!
add_action( 'widgets_init', 'nycga_widgets_init' );

// Initialize custom post type and taxonomy registration
add_action( 'init', 'register_group_documents_post_type' );

function register_group_documents_post_type() {
// Register Group Documents Custom Post Type
register_post_type('group-documents', 
    array(    
    'label' => 'Group Documents',
    'description' => 'Migrated documents from groups',
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => array(
        'slug' => ''
        ),
    'query_var' => true,
    'has_archive' => true,
    'exclude_from_search' => false,
    'supports' => array(
        'title',
        'editor',
        'excerpt',
        'custom-fields',
        'comments',
        'revisions',
        'thumbnail',
        'author',
        'page-attributes',
        ),
    'taxonomies' => array(
        'post_tag',
        'group-document-categories',
        ),
    'labels' => array(
        'name' => 'Group Documents',
        'singular_name' => 'Group Document',
        'menu_name' => 'Group Documents',
        'add_new' => 'Add Group Document',
        'add_new_item' => 'Add New Group Document',
        'edit' => 'Edit',
        'edit_item' => 'Edit Group Document',
        'new_item' => 'New Group Document',
        'view' => 'View Group Document',
        'view_item' => 'View Group Document',
        'search_items' => 'Search Group Documents',
        'not_found' => 'No Group Documents Found',
        'not_found_in_trash' => 'No Group Documents Found in Trash',
        'parent' => 'Parent Group Document',
        ),
    ) 
);

register_taxonomy(
    'group-document-categories', 
    array(
        0 => 'group-documents',
        ), 
    array( 
        'hierarchical' => true, 
        'label' => 'Document Categories',
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'group-documents'
            ),
        'singular_label' => 'Document Category') );

}

?>