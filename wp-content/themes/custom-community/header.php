<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php do_action('favicon') ?>

		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>

		<?php do_action( 'bp_head' ) ?>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<?php wp_head(); ?>
	</head>

    <body <?php body_class(get_responcive_class()) ?> id="cc">
 <div id="outerrim">

 	<?php do_action( 'bp_before_header' ) ?>

     <div id="header"<?php echo (get_responcive_class() === 'not-responsive' && (is_active_sidebar('headerleft') || is_active_sidebar('headerright') || is_active_sidebar('headercenter')))? ' style="height: 220px;"':''; ?>>

    	<?php wp_nav_menu( array( 'container_class' => 'menu menu-top', 'theme_location' => 'menu_top','container' => 'div', 'fallback_cb' => false ) ); ?>
        <div class="row-fluid header-widgets">
            <div class="span12">
                <?php if( ! dynamic_sidebar( 'headerfullwidth' )) :?>
                <?php endif; ?>

            <?php if (is_active_sidebar('headerleft') ){ ?>
                <div class="widgetarea cc-widget span4">
                    <?php dynamic_sidebar( 'headerleft' )?>
                </div>
            <?php } ?>

            <?php if (is_active_sidebar('headercenter') ){ ?>
                <div class="<?php if(!is_active_sidebar('headerleft')) { echo (get_responcive_class() === 'not-responsive')? 'widgets_imp_350':'widgets_imp_410'; } ?> widgetarea cc-widget cc-widget-center span4">
                    <?php dynamic_sidebar( 'headercenter' ) ?>
                </div>
            <?php } ?>

            <?php if (is_active_sidebar('headerright') ){ ?>
                <div class="widgetarea cc-widget cc-widget-right span4">
                    <?php dynamic_sidebar( 'headerright' ) ?>
                </div>
            <?php } ?>
            </div>
        </div>
		<?php do_action( 'bp_before_access')?>
		<div class="stratcher"></div>
        <div id="access" class="span12">
    		<div class="menu">

				<?php do_action('bp_menu') ?>

				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary','container' => '') ); ?>
			</div>
		</div>

		<?php do_action( 'bp_after_header_nav' ) ?>

		<div class="clear"></div>

	</div><!-- #header -->

	<?php do_action( 'bp_after_header' ) ?>
	<?php do_action( 'bp_before_container' ) ?>

    <div id="container" class="container-fluid">
        <div class="row-fluid <?php echo cc_get_class_by_sidebar_position(); ?>">
            <?php do_action('sidebar_left');?>