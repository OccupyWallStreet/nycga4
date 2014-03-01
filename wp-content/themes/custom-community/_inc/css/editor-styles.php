<?php
/**
 * Styles for editor and site content
 */
$loader  = '../../../../../wp-load.php';
if(file_exists($loader)){
    require_once $loader;
}
if(is_admin()){
    header('Content-Type:text/css');
}
global $cap;
$switch_css = cc_switch_css();
extract($switch_css);
?>
body, #tinymce {
    background: none #<?php echo $body_bg_color;?>;
    color:#<?php echo $font_color;?>;
    font-family:Arial,Tahoma,Verdana,sans-serif;
    font-size:12px;
    line-height:170%;
    margin:0 auto;
    max-width:100%;
    min-width:100%;
    padding:0 !important;
    width:100%;
    <?php
    switch ($cap->bg_body_img_pos){
        case __('left','cc'):
            echo 'background-position: left top;';
            break;
        case __('right','cc'):
            echo 'background-position: right top;';
            break;
        case __('center','cc'):
            echo 'background-position: center top;';
            break;
        default:
            echo 'background-position: center top;';
            break;
    }
    ?>
    <?php if($cap->bg_body_img_fixed){?>
    background-attachment: fixed;
    <?php } ?>
}
<?php if($cap->font_style){?>
/** ***
font family  **/

a, div.post p.date a, div.post p.postmetadata a, div.comment-meta a, div.comment-options a, span.highlight, #item-nav a, div.widget ul li a:hover,
body, #tinymce a, #tinymce  div.post p.date a, #tinymce  div.post p.postmetadata a,#tinymce div.comment-meta a, #tinymce div.comment-options a, #tinymce span.highlight, #tinymce #item-nav a, #tinymce div.widget ul li a:hover,
#tinymce body {
    font-family: <?php echo $cap->font_style?>;
}
<?php };
if($cap->font_size){?>
/** ***
standard font size  **/

body, #tinymce, body p, em, a,
div.post,
div.post p.date,
div.post p.postmetadata,
div.comment-meta,
div.comment-options,
div.post p.date a,
div.post p.postmetadata a,
div.comment-meta a,
div.comment-options a,
span.highlight,
#item-nav a,
div#leftsidebar h3.widgettitle,
div#sidebar h3.widgettitle,
div.widgetarea h3.widgettitle,
div.widget ul li a:hover,
#subnav a:hover,
div.widget ul#blog-post-list li a,
div.widget ul#blog-post-list li,
div.widget ul#blog-post-list li p,
div.widget ul#blog-post-list li div,
div.widget ul li.recentcomments a,
div#sidebar div#sidebar-me h4,
div.widgetarea div#sidebar-me h4,
div#item-header div#item-meta,
ul.item-list li div.item-title span,
ul.item-list li div.item-desc,
ul.item-list li div.meta,
div.item-list-tabs ul li span,
span.activity,
div#message p,
div.widget span.activity,
div.pagination,
div#message.updated p,
#subnav a,
div.widget-title ul.item-list li a,
div#item-header span.activity,
div#item-header span.highlight,
form.standard-form input:focus,
form.standard-form textarea:focus,
form.standard-form select:focus,
table tr td.label,
table tr td.thread-info p.thread-excerpt,
table.forum td p.topic-text,
table.forum td.td-freshness,
form#whats-new-form,
form#whats-new-form h5,
form#whats-new-form #whats-new-textarea,
.activity-list li .activity-inreplyto,
.activity-list .activity-content .activity-header,
.activity-list .activity-content .comment-header,
.activity-list .activity-content span.time-since,
.activity-list .activity-content span.activity-header-meta a,
.activity-list .activity-content .activity-inner,
.activity-list .activity-content blockquote,
.activity-list .activity-content .comment-header,
.activity-header a:hover,
div.activity-comments div.acomment-meta,
div.activity-comments form .ac-textarea,
div.activity-comments form textarea,
div.activity-comments form div.ac-reply-content,
li span.unread-count,
tr.unread span.unread-count,
div.item-list-tabs ul li a span.unread-count,
ul#topic-post-list li div.poster-meta,
div.admin-links,
div.poster-name a,
div.object-name a,
div.post p.date a:hover,
div.post p.postmetadata a:hover,
div.comment-meta a:hover,
div.comment-options a:hover,
#footer,
#footer a,
div.widget ul li a,
.widget li.cat-item a,
#item-nav a:hover {
    font-size: <?php echo $cap->font_size?>px;
    line-height: 170%;
}
<?php };
if($cap->font_color != ""):?>
    /** ***
    font colour  **/

    body, p, em, div.post, div.post p.date, div.post p.postmetadata, div.comment-meta, div.comment-options,
    div#item-header div#item-meta, ul.item-list li div.item-title span, ul.item-list li div.item-desc,
    ul.item-list li div.meta, div.item-list-tabs ul li span, span.activity, div#message p, div.widget span.activity,
    div.pagination, div#message.updated p, #subnav a,
    h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, h1 a:focus, h2 a:focus, h3 a:focus, h4 a:focus, h5 a:focus, h6 a:focus,
    div#item-header span.activity, div#item-header h2 span.highlight, div.widget-title ul.item-list li.selected a,
    form.standard-form input:focus, form.standard-form select:focus, table tr td.label,
    table tr td.thread-info p.thread-excerpt, table.forum td p.topic-text, table.forum td.td-freshness, form#whats-new-form,
    form#whats-new-form h5, form#whats-new-form #whats-new-textarea, .activity-list li .activity-inreplyto,
    .activity-list .activity-content .activity-header, .activity-list .activity-content .comment-header,
    .activity-list .activity-content span.time-since,
    .activity-list .activity-content .activity-inner, .activity-list .activity-content blockquote,
    .activity-list .activity-content .comment-header, div.activity-comments div.acomment-meta,
    div.activity-comments form div.ac-reply-content, li span.unread-count, tr.unread span.unread-count, div.item-list-tabs ul li a span.unread-count, ul#topic-post-list li div.poster-meta,
    div.admin-links, #comments h3, #trackbacks h3, #respond h3, #footer, div#item-header span.activity, div#item-header h2 span.highlight, #item-nav a:hover {
        color:#<?php echo $cap->font_color?>;
    }
    div#item-header h2 span.highlight, div.item-list-tabs ul li.selected a, div.item-list-tabs ul li.current a {
        color:#<?php echo $cap->font_color?> !important;
    }

/** ***
buttons and widgets that want some adapting to the font colour  **/

a.comment-edit-link, a.comment-reply-link, a.button, input[type="submit"], input[type="button"], ul.button-nav li a, div.generic-button a,
.activity-list div.activity-meta a  {
    background:#<?php echo $cap->font_color?>;
}

div#leftsidebar h3.widgettitle, div#sidebar h3.widgettitle, div.widgetarea h3.widgettitle {
    color:#<?php echo $cap->font_color?>;
}
<?php endif;?>


<?php if($cap->link_color){?>
    /** ***
    link colour  **/

    body a,
    span.highlight, #item-nav a,
    div.widget ul#blog-post-list li a,
    div.widget ul li.recentcomments a,
    .widget li.current-cat a,
    div.widget ul li.current_page_item a,
    #footer .widget li.current-cat a,#header .widget li.current-cat a ,
    #footer div.widget ul li.current_page_item a,
    #header div.widget ul li.current_page_item a,
    #subnav a:hover  {
        color:#<?php echo $cap->link_color?>;
    }

    /** ***
    buttons and widgets that want some adapting to the link colour  **/

    a.comment-edit-link:hover,
    a.comment-edit-link:focus,
    a.comment-reply-link:hover,
    a.comment-reply-link:focus,
    a.button:focus,
    a.button:hover,
    input[type="submit"]:hover,
    input[type="button"]:hover,
    ul.button-nav li a:hover,
    div.generic-button a:hover,
    ul.button-nav li a:focus,
    div.generic-button a:focus,
    .activity-list div.activity-meta a.acomment-reply,
    div.activity-meta a.fav:hover,
    a.unfav:hover,
    div#item-header h2 span.highlight span {
        background-color:#<?php echo $cap->link_color?> !important;
    }
<?php } ?>

<?php if($cap->link_color_hover != ""):?>
    /** ***
    link colour hover  **/

    a:hover,
    a:focus,
    div#sidebar div.item-options a.selected:hover,
    div#leftsidebar div.item-options a.selected:hover,
    form.standard-form input:focus,
    form.standard-form select:focus,
    .activity-header a:hover,
    div.post p.date a:hover,
    div.post p.postmetadata a:hover,
    div.comment-meta a:hover,
    div.comment-options a:hover,
    div.widget ul li a:hover,
    div.widget ul li.recentcomments a:hover,
    div.widget-title ul.item-list li a:hover {
        color:#<?php echo $cap->link_color_hover ?>;
    }

    <?php if ( $cap->link_color_subnav_adapt == __("link colour and hover colour",'cc') ) {?>
        #subnav a:hover, #subnav a:focus, div.item-list-tabs ul li a:hover, div.item-list-tabs ul li a:focus {
            color:#<?php echo $cap->link_color_hover ?>;
        }
    <?php } ?>

<?php endif;?>

<?php if($cap->link_underline != __("never",'cc') && $cap->link_underline != "never" && $cap->link_underline != "" ): ?>

    <?php if($cap->link_underline == __("just for mouse over",'cc') || $cap->link_underline == "just for mouse over"){
        $stylethis = 'body a:hover, body a:focus';
    } else {
        if($cap->link_underline == __("always",'cc') || $cap->link_underline == "always") {
          $stylethis = 'body a, body a:hover, body a:focus';
        } else {
          $stylethis = 'body a:hover, body a:focus {text-decoration: none} a';
        }
    } ?>

    /** ***
    link underline  **/

    <?php echo $stylethis ?> {
        text-decoration: underline;
    }

<?php endif;?>

<?php if($cap->link_bg_color != ""):?>
    /** ***
    link BACKGROUND colour  **/

    body a {
        background-color: <?php if ( $cap->link_bg_color != __('transparent','cc') && $cap->link_bg_color != 'transparent' ) {echo '#', $cap->link_bg_color; } else { echo 'transparent'; }?>;
    }
<?php endif;?>

<?php if($cap->link_bg_color_hover != ""):?>
    /** ***
    link BACKGROUND colour hover  **/

    body a:hover,body  a:focus {
        background-color: <?php if ( $cap->link_bg_color_hover != __('transparent','cc') && $cap->link_bg_color_hover != 'transparent' ) {echo '#', $cap->link_bg_color_hover; } else { echo 'transparent'; }?>;
    }
<?php endif;?>

<?php if($cap->link_styling_title_adapt != "just the hover effect" && $cap->link_styling_title_adapt != __("just the hover effect",'cc')):?>
/** ***
    link styling titles adapt**/

    <?php if ($cap->link_hover_color != '') {
    // use the link hover colour anyway - if one is selected ?>
                body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus {
                    color: #<?php echo $cap->link_hover_color;?>;
                }
    <?php } ?>


    <?php switch ($cap->link_styling_title_adapt) {
        case __('link colour and hover colour','cc'):
        case 'link colour and hover colour': ?>

            body h1 a, body h2 a, body h3 a, body h4 a, body h5 a, body h6 a {
                color: #<?php echo $cap->link_color;?>;
            }

        <?php break;
        case 'no, only the link colour!':
        case __('no, only the link colour!','cc'): ?>

            <?php if ($cap->link_bg_color_hover || $cap->link_bg_color_hover) {?>
                body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus {
                    color: #<?php if (!$cap->font_color) {echo $font_color;} else {echo $cap->font_color; } ?>;
                }
            <?php } ?>

        <?php break;
        case 'link colour and hover colour':
        case __('link colour and hover colour','cc'):?>

            <?php if($cap->link_underline != "never" && $cap->link_underline != __("never",'cc')): ?>

                <?php if($cap->link_underline == "just for mouse over" || $cap->link_underline == __("just for mouse over",'cc')){
                    $stylethis = 'body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                    body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus';
                } else {
                    if($cap->link_underline == "always" || $cap->link_underline == __("always",'cc')) {
                        $stylethis =    'body h1 a, body h2 a, body h3 a, body h4 a, body h5 a, body h6 a, body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                                        body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus';
                    } else {
                        $stylethis =    'body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                                        body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus {
                                        text-decoration: none;
                                        }
                                        body h1 a, body h2 a, body h3 a, body h4 a, body h5 a, body h6 a';
                    }
                } ?>

                /** ***
                title links underline  **/

                <?php echo $stylethis ?> {
                    text-decoration: underline;
                }

            <?php endif;?>

        <?php break;
        case 'adapt all link styles':
        case __('adapt all link styles','cc'):?>

            <?php if($cap->link_underline != "never" && $cap->link_underline != __("never",'cc')): ?>

                <?php if($cap->link_underline == "just for mouse over" || $cap->link_underline == __("just for mouse over",'cc')){
                    $stylethis = 'body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus';
                } else {
                    if($cap->link_underline == "always" && $cap->link_underline == __("always",'cc')) {
                        $stylethis =    'body h1 a, body h2 a, body h3 a, body h4 a, body h5 a, body h6 a, body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                                        body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus';
                    } else {
                        $stylethis =    'body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                                        body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus {
                                        text-decoration: none;
                                        }
                                        body h1 a, body h2 a, body h3 a, body h4 a, body h5 a, body h6 a';
                    }
                } ?>

                /** ***
                title links underline  **/

                <?php echo $stylethis ?> {
                    text-decoration: underline;
                }

            <?php endif;?>

            <?php if($cap->link_bg_color != ""):?>
                /** ***
                title links BACKGROUND colour  **/

                body h1 a, body h2 a, body h3 a, body h4 a, body h5 a, body h6 a {
                    background-color: <?php if ( $cap->link_bg_color != 'transparent' && $cap->link_bg_color != __('transparent','cc') ) {echo '#', $cap->link_bg_color; } else { echo 'transparent';}?>;
                }
            <?php endif;?>

            <?php if($cap->link_bg_color_hover != ""):?>
                /** ***
                title links BACKGROUND colour hover  **/

                body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus {
                    background-color: <?php if ( $cap->link_bg_color_hover != 'transparent' && $cap->link_bg_color_hover != __('transparent','cc') ) {echo '#', $cap->link_bg_color_hover; } else { echo 'transparent';}?>;
                }
            <?php endif;?>


        <?php break;
        case 'the background colours too':
        case __('the background colours too','cc'): ?>

            <?php if($cap->link_bg_color != ""):?>
                /** ***
                title links BACKGROUND colour  **/

                body h1 a, body h2 a, body h3 a, body h4 a, body h5 a, body h6 a {
                    background-color: <?php if ( $cap->link_bg_color != 'transparent' && $cap->link_bg_color != __('transparent','cc')) {echo '#', $cap->link_bg_color; } else { echo 'transparent';}?>;
                }
            <?php endif;?>

            <?php if($cap->link_bg_color_hover != ""):?>
                /** ***
                title links BACKGROUND colour hover  **/

                body h1 a:hover, body h2 a:hover, body h3 a:hover, body h4 a:hover, body h5 a:hover, body h6 a:hover,
                body h1 a:focus, body h2 a:focus, body h3 a:focus, body h4 a:focus, body h5 a:focus, body h6 a:focus {
                    background-color: <?php if ( $cap->link_bg_color_hover != 'transparent' && $cap->link_bg_color_hover != __('transparent','cc') ) {echo '#', $cap->link_bg_color_hover; } else { echo 'transparent';} ?>;
                }
            <?php endif;?>

        <?php break;
        ?>

      <?php } ?>




<?php endif;?>
                
div.post p, #tinymce p {margin: 0 0 20px 0}

<?php if($cap->subtitle_font_style != "" || $cap->subtitle_color != "" || $cap->subtitle_weight != ""):?>
/** ***
subtitle font style, weight and colour  **/

body h3,body h4,body h5,body h6,body h3 a,body h4 a,body h5 a,body h6 a {
<?php if($cap->subtitle_font_style){?>
    font-family: <?php echo $cap->subtitle_font_style?>;
<?php };?>
<?php if($cap->subtitle_color){?>
    color:#<?php echo $cap->subtitle_color?>;
<?php };?>
<?php if($cap->subtitle_weight == __('bold','cc') || $cap->subtitle_weight == 'bold'){?>
    font-weight:bold;
<?php } else {?>
    font-weight:normal;
<?php };?>
}
<?php endif;?>



<?php if($cap->title_font_style != "" || $cap->title_size != "" || $cap->title_color != "" || $cap->title_weight != ""):?>
/** ***
title font style, size, weight and colour  **/

body h1,body h2,body h1 a,body h2 a,body h1 a:hover,body h1 a:focus,body h2 a:hover,body h2 a:focus {
<?php if($cap->title_font_style){?>
    font-family: <?php echo $cap->title_font_style?>;
<?php };?>
<?php if($cap->title_size){?>
    font-size: <?php echo $cap->title_size?>px;
<?php };?>
<?php if($cap->title_weight == __('bold','cc')){?>
    font-weight:bold;
<?php } elseif( $cap->title_weight == __('normal','cc')){?>
    font-weight:normal;
<?php } ;?>
}

body h1,body h2,body h1 a,body h2 a {
<?php if($cap->title_color){?>
    color:#<?php echo $cap->title_color?>;
<?php };?>
}

<?php endif;?>



