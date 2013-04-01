<?php
/*
 * Plugin Name: Latest Custom Post Type Updates
 * Plugin URI: http://technicalmastermind.com/wordpress-plugins/latest-custom-post-type-updates/
 * Description: This simple plugin adds a widget that allows for the display of recent posts in any custom post type. It functions almost identically to the built-in WordPress "Recent Posts" widget with the added option of letting you choose which post type it pulls from. Just add the "Latest Custom Post Type" widget to a widget area, give it a title, how many posts to show and what post type you want it to pull from. Also comes with a plethora of advanced settings to explore!
 * Version: 1.3.0
 * Author: David Wood
 * Author URI: http://technicalmastermind.com/about-david-wood/
 * License: GPLv3
 */

define('TM_LCPTU_FILE', __FILE__);
define('TM_LCPTU_VERSION', '1.3.0');

if(!class_exists('tm_latest_cp_widget') && class_exists('WP_Widget')):
    class tm_latest_cp_widget extends WP_Widget {
        function tm_latest_cp_widget() {
            // What our users will see when looking at the widgets to add
            $name = __('Latest Custom Post Type');
            $description = __('Simple display of the latest updates to your custom post type.');
            // Setting up basic stuff for the WP side of widget handling
            $this->WP_Widget($id_base = false,
                $name,
                $widget_options = array('classname' => strtolower(get_class($this)), 'description' => $description),
                $control_options = array());
        }

        function form($instance) {
            // Our options when managing individual widget settings
            include(plugin_dir_path(TM_LCPTU_FILE).'/options.php');
        }

        function update($new_instance, $old_instance) {
            // Process our widgets settings to save them
            $instance = $old_instance;

            /** SAVE BASIC SETTINGS! **/
            $instance['title'] = addslashes(strip_tags($new_instance['title']));
            $instance['numberposts'] = (is_numeric(trim((int)$new_instance['numberposts'])))?trim((int)$new_instance['numberposts']):5;
            $instance['post_type'] = (is_array($new_instance['post_type']))?$new_instance['post_type']:array($new_instance['post_type']);
            /** SAVE ADDITIONAL SETTINGS! **/
            $instance['orderby'] = (in_array($new_instance['orderby'], array('none', 'ID', 'author', 'title', 'name', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'meta_value', 'meta_value_num')))?$new_instance['orderby']:'date';
            $instance['order'] = ($new_instance['order'] == 'ASC')?'ASC':'DESC';
            $instance['output_orderby'] = (in_array($new_instance['output_orderby'], array('same', 'ID', 'author', 'title', 'name', 'date', 'modified', 'parent', 'rand')))?$new_instance['output_orderby']:'same';
            $instance['output_order'] = ($new_instance['output_order'] == 'DESC')?'DESC':'ASC';
            $instance['empty_display'] = addslashes(strip_tags($new_instance['empty_display']));
            $instance['css_class'] = addslashes(str_replace('"', '', str_replace('\'', '', strip_tags($new_instance['css_class']))));
            /** SAVE ADVANCED SETTINGS! **/
            /** Save thumbnail settings **/
            $instance['show_thumbnails'] = ($new_instance['show_thumbnails'] == 'yes')?'yes':'no';
            $instance['thumbnail_format'] = (in_array($new_instance['thumbnail_format'], get_intermediate_image_sizes()))?$new_instance['thumbnail_format']:'0';
            $instance['thumbnail_width'] = (is_numeric(trim((int)$new_instance['thumbnail_width'])))?trim((int)$new_instance['thumbnail_width']):'';
            $instance['thumbnail_height'] = (is_numeric(trim((int)$new_instance['thumbnail_height'])))?trim((int)$new_instance['thumbnail_height']):'';
            $instance['default_image'] = addslashes(strip_tags($new_instance['default_image']));
            /** Save date settings **/
            $instance['show_date'] = ($new_instance['show_date'] == 'yes')?'yes':'no';
            $instance['date_format'] = (in_array(trim($new_instance['date_format']), array('WP', 'CUSTOM', 'n/j/Y', 'm/d/Y', 'M. j, Y')))?trim($new_instance['date_format']):'WP';
            $instance['date_format_custom'] = addslashes(strip_tags($new_instance['date_format_custom']));
            $instance['show_time'] = ($new_instance['show_time'] == 'yes')?'yes':'no';
            /** Save excerpt settings **/
            $instance['show_excerpt'] = ($new_instance['show_excerpt'] == 'yes')?'yes':'no';
            $instance['excerpt_length'] = (is_numeric(trim((int)$new_instance['excerpt_length'])))?trim((int)$new_instance['excerpt_length']):125;
            $instance['excerpt_readmore'] = addslashes(strip_tags($new_instance['excerpt_readmore']));
            /** Save advanced taxonomy filtering settings **/
            $instance['show_advanced'] = ($new_instance['show_advanced'] == 'yes')?'yes':'no';
            $instance['tax_relation'] = ($new_instance['tax_relation'] == 'OR')?'OR':'AND';
            $instance['taxonomies'] = (is_array($new_instance['taxonomies']))?$new_instance['taxonomies']:array();
            $tmp = array();
            foreach($new_instance['tag_list'] as $tax=>$items) {
                if(!is_array($items)) continue;
                if(!$items['post_in'])
                    $tmp[$tax]['post_in'] = 'IN';
                elseif(!in_array($items['post_in'], array('IN', 'NOT IN', 'AND')))
                    $tmp[$tax]['post_in'] = 'IN';
                else
                    $tmp[$tax]['post_in'] = $items['post_in'];
                $tmp[$tax]['term_ids'] = $items['term_ids'];
            }
            $instance['tag_list'] = $tmp; //$new_instance['tag_list'];
            /** DONE SAVING! **/
            return $instance;
        }

        function widget($args, $instance) {
            // Displaying our widget!
            // Get our args, defined by the widget area definition
            $before_title = $before_widget = $after_widget = $after_title = '';
            extract($args, EXTR_IF_EXISTS);
            // Compile our parameters for post retrieval
            $params = array(
                'post_type' => $instance['post_type'],
                'posts_per_page' => $instance['numberposts'],
                'order' => $instance['order'],
                'orderby' => $instance['orderby'],
            );
            // Check for ordering by meta value
            if(($instance['order'] == 'meta_value' || $instance['order'] == 'meta_value_num')
                && $instance['meta_key'] != '') {
                // Add meta key
                $params['meta_key'] = $instance['meta_key'];
            }
            // Check if there are any taxonomy filters requested
            if($instance['show_advanced'] == 'yes') {
                // We have taxonomy filters! Add filter to the query!
                $params['tax_query'] = array();
                if(is_array($instance['taxonomies']) && count($instance['taxonomies']) > 1)
                    $params['tax_query']['relation'] = ($instance['tax_relation'] == 'OR')?'OR':'AND';
                if(is_array($instance['taxonomies']) && count($instance['taxonomies']) > 0) {
                    foreach($instance['taxonomies'] as $taxonomy) {
                        if(is_array($instance['tag_list']) && is_array($instance['tag_list'][$taxonomy])
                            && is_array($instance['tag_list'][$taxonomy]['term_ids'])) {
                            $params['tax_query'][] = array(
                                'taxonomy' => $taxonomy,
                                'field' => 'id',
                                'terms' => $instance['tag_list'][$taxonomy]['term_ids'],
                                'operator' => (in_array($instance['tag_list'][$taxonomy]['post_in'],
                                    array(
                                        'IN', 'NOT IN', 'AND'
                                    )))?$instance['tag_list'][$taxonomy]['post_in']:'IN'
                            );
                        }
                    }
                }
                else {
                    $params['tax_query'][] = array(
                        'taxonomy' => $instance['taxonomies'],
                        'field' => 'id',
                        'terms' => $instance['tag_list'],
                    );
                }
            }
            //echo '<pre>';
            //var_dump($params['tax_query']);
            //echo '</pre>';
            // Make our request
            $lcptu = new WP_Query($params);
            // If nothing to show at all, show nothing!
            if((!$lcptu || !$lcptu->have_posts()) && empty($instance['empty_display']))
                return;
            // Begin our widget display
            echo $before_widget;
            echo $before_title.$instance['title'].$after_title;
            echo '<ul class="tm-latest-updates '.$instance['css_class']
                .(($instance['show_thumbnails'] && $instance['show_thumbnails'] == 'yes')?
                    ' tm_lcptu_with_thumbnails':'')
                .(($instance['show_excerpt'] && $instance['show_excerpt'] == 'yes')?
                    ' tm_lcptu_with_excerpt':'')
                .'">';
            // Start stuff!
            $count = 0;
            if($lcptu && $lcptu->have_posts()) {
                if($instance['output_orderby'] && $instance['output_orderby'] != 'same') {
                    $keys = array();
                    foreach($lcptu->posts as $tmp) {
                        $keys[] = $tmp->ID;
                    }
                    $lcptu = new WP_Query(array(
                        'post_type' => $instance['post_type'],
                        'posts_per_page' => count($keys),
                        'post__in' => $keys,
                        'orderby' => $instance['output_orderby'],
                        'order' => $instance['output_order'],
                    ));
                }
                while($lcptu->have_posts()) {
                    $lcptu->next_post();
                    $count++;
                    $id = $lcptu->post->ID;
                    ?>
                    <li class="post-<?php echo $id; ?> <?php echo ($count%2)?'odd':'even'; ?>">
                        <?php
                        if($instance['show_thumbnails'] && $instance['show_thumbnails'] == 'yes'){
                            if(has_post_thumbnail($id)) {
                                echo '<a class="tm_lcptu_post_thumbnail" href="'.get_permalink($id).'">';
                                if($instance['thumbnail_format']) {
                                    if($instance['thumbnail_format'] != '0')
                                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), $instance['thumbnail_format']);
                                    elseif($instance['thumbnail_width'] > 0 && $instance['thumbnail_height'] > 0)
                                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), array($instance['thumbnail_width'], $instance['thumbnail_height']));
                                    else
                                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($id));
                                    if($instance['thumbnail_width'] > 0)
                                        $image[1] = $instance['thumbnail_width'];
                                    if($instance['thumbnail_height'] > 0)
                                        $image[2] = $instance['thumbnail_height'];
                                    echo '<img src="'.$image[0].'" alt="'.$lcptu->post->post_title.'" width="'.$image[1].'" height="'.$image[2].'" />';
                                }
                                echo '</a>';
                            }
                            elseif($instance['default_image']) {
                                $image = array($instance['default_image']);
                                if($instance['thumbnail_width'] > 0)
                                    $image[1] = $instance['thumbnail_width'];
                                if($instance['thumbnail_height'] > 0)
                                    $image[2] = $instance['thumbnail_height'];
                                echo '<a class="tm_lcptu_post_thumbnail" href="'.get_permalink($id).'">';
                                echo '<img src="'.$image[0].'" alt="'.$lcptu->post->post_title.'" width="'.$image[1].'" height="'.$image[2].'" />';
                                echo '</a>';
                            }
                        } ?>
                        <h4 class="tm_lcptu_post_title"><?php echo '<a href="'.get_permalink($id).'" class="tm_lcptu_post_title_link">'.get_the_title($id).'</a>'; ?></h4>
                        <?php
                        if($instance['show_date'] == 'yes') {
                            // We are showing the date!
                            if($instance['date_format'] == 'CUSTOM' && $instance['date_format_custom'] != '')
                                $format = stripslashes($instance['date_format_custom']);
                            elseif($instance['date_format'] != 'WP' && $instance['date_format'] != 'CUSTOM')
                                $format = $instance['date_format'];
                            else
                                $format = get_option('date_format');
                            echo '<div class="tm_lcptu_post_date">'.date($format.(($instance['show_time'] == 'yes')?', '.get_option('time_format'):''), strtotime(($instance['date_to_show'] == 'publish')?$lcptu->post->post_date:$lcptu->post->post_modified)).'</div>';
                        }
                        if($instance['show_excerpt'] && $instance['show_excerpt'] == 'yes') {
                            echo '<div class="tm_lcptu_excerpt">';
                            if($lcptu->post->post_excerpt != '')
                                $excerpt = $lcptu->post->post_excerpt;
                            else
                                $excerpt = $lcptu->post->post_content;
                            $excerpt = strip_tags($excerpt);
                            if($instance['excerpt_length'] > 0)
                                $charlength = $instance['excerpt_length'] + 1;
                            else
                                $charlength = 126;

                            if ( mb_strlen( $excerpt ) > $charlength ) {
                                $subex = mb_substr( $excerpt, 0, $charlength - 5 );
                                $exwords = explode( ' ', $subex );
                                $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
                                if ( $excut < 0 ) {
                                    echo mb_substr( $subex, 0, $excut );
                                } else {
                                    echo $subex;
                                }
                            } else {
                                echo $excerpt;
                            }
                            if($instance['excerpt_readmore'])
                                echo ' <a href="'.get_permalink($id).'" class="tm_lcptu_read_more_link">'.$instance['excerpt_readmore'].'</a>';
                            echo '</div>';
                        } ?>
                    </li>
                    <?php
                }
            }

            // Finish widget output with our closing tags!
            echo '</ul>';
            echo $after_widget;
        }
    }
    // Register our widget and add AJAX capabilities
    add_action('widgets_init',
        create_function('', 'return register_widget("tm_latest_cp_widget");')
    );
    add_action('wp_ajax_tm_lcptu_get_taxonomies',
        array('tm_latest_cp_widget', 'get_post_taxonomies')
    );
endif; // if !class_exists('tm_latest_cp_widget') && class_exists('WP_Widget')

if(!function_exists('tm_lcptu_enqueue_admin_scripts')) {
    add_action('admin_enqueue_scripts', 'tm_lcptu_enqueue_admin_scripts');
    function tm_lcptu_enqueue_admin_scripts($hook) {
        if($hook != 'widgets.php') return;
        wp_enqueue_script('tm-lcptu-advanced', plugins_url('/js/tm-lcptu-options-1.7.js', __FILE__), array('jquery'));
        wp_enqueue_style('tm-lcptu-admin', plugins_url('/css/tm_lcptu_admin.css', TM_LCPTU_FILE));
    }
}

if(!function_exists('tm_lcptu_enqueue_styles')) {
    add_action('wp_enqueue_scripts', 'tm_lcptu_enqueue_styles');
    function tm_lcptu_enqueue_styles() {
        wp_enqueue_style('tm-lcptu-styles', plugins_url('/css/tm_lcptu_basic_styles.css', TM_LCPTU_FILE), array(), TM_LCPTU_VERSION);
    }
}

if(!function_exists('tm_lcptu_get_taxonomies')) {
    function tm_lcptu_get_taxonomies($post_type = 'post', $field_name = '', $checked = array()) {
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $a = array();
        if($taxonomies) {
            foreach($taxonomies as $key=>$taxonomy) {
                if(!$taxonomy->show_ui) continue;
                $tmp = '<input type="checkbox" name="'.$field_name.'[]" value="'.$key.'" ';
                $tmp .= 'class="tm_lcptu_taxonomy_checkbox"';
                $tmp .= (in_array($taxonomy->name, $checked)?' checked="checked"':'').' />';
                $tmp .= ' '.$taxonomy->labels->name.' (';
                $tmp .= implode(', ', $taxonomy->object_type).')';
                $a[] = $tmp;
            }
        }
        if(count($a) > 0)
            return implode('<br/>', $a);
        else
            return __('No taxonomies match the selected post types!');
    }
}

if(!function_exists('tm_lcptu_get_terms')) {
    function tm_lcptu_get_terms($taxonomy, $field_name = '', $checked = array('post_in' => 'IN', 'term_ids' => array())) {
        $tax = get_taxonomy($taxonomy);
        $terms = get_terms($taxonomy, array('hide_empty' => false));
        $a = array();
        $selected = ' selected="selected"';
        $before = '<div class="tm_lcptu_terms-'.$taxonomy.'">';
        $before .= '<h4 style="margin-bottom:0;">'.$tax->labels->name.'</h4>';
        $before .= 'Only show posts that are <select name="'.$field_name.'['.$taxonomy.'][post_in]">';
        $before .= '<option value="IN"'.(($checked['post_in'] == 'IN')?$selected:'').'>in any of</option>';
        $before .= '<option value="NOT IN"'.(($checked['post_in'] == 'NOT IN')?$selected:'').'>not in</option>';
        $before .= '<option value="AND"'.(($checked['post_in'] == 'AND')?$selected:'').'>in all</option>';
        $before .= '</select> the following terms:<br/>';
        if($terms) {
            foreach($terms as $term) {
                //if(!$term->show_ui) continue;
                $tmp = '<input type="checkbox" name="'.$field_name.'['.$taxonomy.'][term_ids][]" ';
                $tmp .= 'value="'.$term->term_id.'" id="'.$term->slug.'"';
                $tmp .= (in_array($term->term_id, $checked['term_ids'])?' checked="checked"':'').' />';
                $tmp .= ' <label for="'.$term->slug.'">'.$term->name.'</label>';
                $a[] = $tmp;
            }
        }
        if(count($a) > 0)
            return $before.implode('<br/>', $a).'</div>';
        else
            return '<div class="tm_lcptu_terms-'.$taxonomy.'"><h4>'.__('No terms found for '.$tax->labels->name.' ('.implode(', ', $tax->object_type).')').'</h4></div>';
    }
}

if(!function_exists('ajax_tm_lcptu_tax')):
    // Getting our taxonomies AJAX call
    add_action('wp_ajax_tm_lcptu_tax', 'ajax_tm_lcptu_tax');
    function ajax_tm_lcptu_tax() {
        if(isset($_POST['posttype']) && is_array($_POST['posttype']) && !empty($_POST['fieldName'])) {
            echo tm_lcptu_get_taxonomies($_POST['posttype'], $_POST['fieldName']);
            echo '<br/>NOTE: Due to jQuery limitations, you must save at this time to be able to see the remaining options.';
        }
        else _e('No post types selected!');
        die();
    }
endif;

if(!function_exists('ajax_tm_lcptu_terms')):
    add_action('wp_ajax_tm_lcptu_terms', 'ajax_tm_lcptu_terms');
    function ajax_tm_lcptu_terms() {
        if(!empty($_POST['taxonomy']) && !empty($_POST['fieldName'])) {
            echo tm_lcptu_get_terms($_POST['taxonomy'], $_POST['fieldName']);
        }
        die();
    }
endif;
