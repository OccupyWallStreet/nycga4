<?php
// Set form defaults/get existing settings
$instance = wp_parse_args(
    (array) $instance,
    array(
        // Basic Settings
        'title' => 'Latest Updates',
        'numberposts' => 5,
        'post_type' => array('post'),
        // Additional Settings
        'orderby' => 'date',
        'meta_key' => '',
        'order' => 'DESC',
        'output_orderby' => 'same',
        'output_order' => 'ASC',
        'empty_display' => '',
        'css_class' => '',
        // Advanced Settings
        'show_thumbnails' => 'no',
            'thumbnail_format' => 'thumbnail',
            'thumbnail_width' => '',
            'thumbnail_height' => '',
            'default_image' => '', // TODO! Integrate with media library?
        'show_date' => 'no',
            'date_to_show' => 'publish', // PUBLISH OR MODIFIED!
            'date_format' => 'WP',
            'date_format_custom' => '',
            'show_time' => 'no',
        'show_excerpt' => 'no',
            'excerpt_length' => 125,
            'excerpt_readmore' => 'read more &raquo;',
        'show_advanced' => 'no',
            'tax_relation' => 'AND',
            'taxonomies' => array(),
            'tag_list' => ''
    )
);
// Shortcut variables
$selected = ' selected="selected"';
$checked = ' checked="checked"';
// Our form...
?>
<h3 class="tm_lcptu_heading"><?php _e('Basic Settings'); ?></h3>
<a href="" class="tm_lcptu_show-hide" id="tm_lcptu_basic_settings"><?php _e('Show/hide'); ?></a>

<div class="tm_lcptu_basic_settings">
    <p> <!-- TITLE FOR WIDGET -->
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input  id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                type="text"
                value="<?php echo $instance['title']; ?>" />
    </p>

    <p> <!-- NUMBER POSTS FOR WIDGET -->
        <label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of posts to show:'); ?></label>
        <input  id="<?php echo $this->get_field_id('numberposts'); ?>"
                name="<?php echo $this->get_field_name('numberposts'); ?>"
                type="text"
                value="<?php echo $instance['numberposts']; ?>"
                size="3" />
    </p>

    <p> <!-- POST TYPE(S) FOR WIDGET -->
        <label><?php _e('Post type(s) to use:'); ?></label>
        <?php
        // Get only the post types that we should be allowed to work with...
        $post_types = get_post_types(array('show_ui' => true), 'objects');
        if($post_types) {
            foreach($post_types as $post_type=>$vars) {
                echo '<br/><input type="checkbox" name="'.$this->get_field_name('post_type').'[]" value="'.$post_type.'" class="tm_lcptu_post_type type-'.$post_type.'" id="'.$post_type.'"';
                if(is_array($instance['post_type']))
                    if(in_array($post_type, $instance['post_type'])) echo $checked;
                elseif($instance['post_type'] == $post_type) echo $checked;
                echo ' /> '.$vars->labels->name;
            }
        }
        ?>
    </p>
</div>

<h3 class="tm_lcptu_heading"><?php _e('Additional Settings'); ?></h3>
<a href="" class="tm_lcptu_show-hide" id="tm_lcptu_additional_settings"><?php _e('Show/hide'); ?></a>

<div class="tm_lcptu_additional_settings">
    <p> <!-- ORDER POSTS -->
        <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Retrieve posts by:'); ?></label>
        <select name="<?php echo $this->get_field_name('orderby'); ?>"
                id="<?php echo $this->get_field_id('orderby'); ?>"
                class="tm_lcptu_dropdown_custom">
            <?php
            $order_options = array('none' => '-- None --', 'ID' => 'Post ID', 'author' => 'Author', 'title' => 'Title', 'name' => 'Name', 'date' => 'Publish Date', 'modified' => 'Modified Date', 'parent' => 'Post/Page Parent', 'rand' => 'Random', 'comment_count' => '# of Comments', 'menu_order' => 'Menu Order', 'meta_value' => 'Meta Key Value (Advanced)', 'meta_value_num' => 'Numeric Meta Key Value (Advanced)');
            foreach($order_options as $value=>$title) {
                echo '<option value="'.$value.'"'.(($instance['orderby'] == $value)?$selected:'').'>';
                echo $title.'</option>';
            }
            ?>
        </select>
    </p>

    <div class="<?php echo $this->get_field_id('orderby'); ?>" style="display: <?php echo ($instance['orderby'] == 'meta_value' || $instance['orderby'] == 'meta_value_num')?' block':'none'; ?>;">
        <p>
            <label for="<?php echo $this->get_field_id('meta_key'); ?>"><?php _e('Meta key:'); ?></label>
            <input type="text"
                   id="<?php echo $this->get_field_id('meta_key'); ?>"
                   name="<?php echo $this->get_field_name('meta_key'); ?>"
                   value="<?php echo $instance['meta_key']; ?>" />
        </p>
    </div>

    <p> <!-- ORDER DIRECTION -->
        <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Retrieve order direction'); ?></label>
        <select name="<?php echo $this->get_field_name('order'); ?>"
                id="<?php echo $this->get_field_id('order'); ?>">
            <option value="ASC"<?php if($instance['order'] == 'ASC') echo $selected; ?>><?php _e('Ascending (1,2,3;a,b,c; oldest first)'); ?></option>
            <option value="DESC"<?php if($instance['order'] == 'DESC') echo $selected; ?>><?php _e('Descending (3,2,1;c,b,a; newest first)'); ?></option>
        </select>
    </p>

    <p> <!-- ORDER POSTS -->
        <label for="<?php echo $this->get_field_id('output_orderby'); ?>"><?php _e('Sort output by:'); ?></label>
        <select name="<?php echo $this->get_field_name('output_orderby'); ?>"
                id="<?php echo $this->get_field_id('output_orderby'); ?>"
                class="tm_lcptu_dropdown_custom">
            <?php
            $order_options = array('same' => 'Same as above', 'ID' => 'Post ID', 'author' => 'Author', 'title' => 'Title', 'name' => 'Name', 'date' => 'Publish Date', 'modified' => 'Modified Date', 'parent' => 'Post/Page Parent', 'rand' => 'Random');
            foreach($order_options as $value=>$title) {
                echo '<option value="'.$value.'"'.(($instance['output_orderby'] == $value)?$selected:'').'>';
                echo $title.'</option>';
            }
            ?>
        </select>
    </p>

    <p> <!-- ORDER DIRECTION -->
        <label for="<?php echo $this->get_field_id('output_order'); ?>"><?php _e('Output order direction (if "Sort output by" is "Same as above" this has no effect):'); ?></label> <br/>
        <select name="<?php echo $this->get_field_name('output_order'); ?>"
                id="<?php echo $this->get_field_id('output_order'); ?>">
            <option value="ASC"<?php if($instance['output_order'] == 'ASC') echo $selected; ?>><?php _e('Ascending (1,2,3;a,b,c; oldest first)'); ?></option>
            <option value="DESC"<?php if($instance['output_order'] == 'DESC') echo $selected; ?>><?php _e('Descending (3,2,1;c,b,a; newest first)'); ?></option>
        </select>
    </p>

    <p> <!-- TEXT WHEN NO POSTS -->
        <label for="<?php echo $this->get_field_id('empty_display'); ?>"><?php _e('Text to display when there are no posts (defaults to empty):'); ?></label>
        <input  id="<?php echo $this->get_field_id('empty_display'); ?>"
                name="<?php echo $this->get_field_name('empty_display'); ?>"
                type="text"
                value="<?php echo $instance['empty_display']; ?>"
                />
    </p>

    <p> <!-- CSS CLASSES -->
        <label for="<?php echo $this->get_field_id('css_class'); ?>"><?php _e('Custom CSS classes (separated by spaces):'); ?></label><br/>
        <input  id="<?php echo $this->get_field_id('css_class'); ?>"
                name="<?php echo $this->get_field_name('css_class'); ?>"
                type="text"
                value="<?php echo $instance['css_class']; ?>"
                />
    </p>
</div>

<h3 class="tm_lcptu_heading"><?php _e('Advanced Settings'); ?></h3>
<a href="" class="tm_lcptu_show-hide" id="tm_lcptu_advanced_settings"><?php _e('Show/hide'); ?></a>

<div class="tm_lcptu_advanced_settings">
    <p> <!-- SHOW THUMBNAILS? -->
        <input type="checkbox"
               id="<?php echo $this->get_field_id('show_thumbnails'); ?>"
               name="<?php echo $this->get_field_name('show_thumbnails'); ?>"
               value="yes"
               class="tm_lcptu_show-thumbnails tm_lcptu_toggle_options"
               <?php if($instance['show_thumbnails'] == 'yes') echo $checked; ?>
               />
        <label for="<?php echo $this->get_field_id('show_thumbnails'); ?>"><?php _e('Show post thumbnails'); ?></label>
    </p>

    <div class="<?php echo $this->get_field_id('show_thumbnails'); ?>" style="display: <?php echo ($instance['show_thumbnails'] == 'yes')?' block':'none'; ?>;">
        <p> <!-- THUMBNAIL IMAGE FORMAT -->
            <label for="<?php echo $this->get_field_id('thumbnail_format'); ?>"><?php _e('Thumbnail format:'); ?></label>
            <select name="<?php echo $this->get_field_name('thumbnail_format'); ?>"
                    id="<?php echo $this->get_field_id('thumbnail_format'); ?>"
                    class="tm_lcptu_thumbnail_format">
                <option value="0">-- None --</option>
                <?php
                foreach(get_intermediate_image_sizes() as $size) {
                    echo '<option value="'.$size.'"';
                    echo ($instance['thumbnail_format'] == $size)?$selected:'';
                    echo '>'.$size.'</option>';
                }
                ?>
            </select>
        </p>

        <p> <!-- THUMBNAIL WIDTH -->
            <label for="<?php echo $this->get_field_id('thumbnail_width'); ?>"><?php _e('Width:'); ?></label>
            <input type="text"
                   name="<?php echo $this->get_field_name('thumbnail_width'); ?>"
                   id="<?php echo $this->get_field_id('thumbnail_width'); ?>"
                   size="3"
                   value="<?php echo ($instance['thumbnail_width'])?$instance['thumbnail_width']:''; ?>" />
        </p>

        <p> <!-- THUMBNAIL HEIGHT -->
            <label for="<?php echo $this->get_field_id('thumbnail_height'); ?>"><?php _e('Height:'); ?></label>
            <input type="text"
                   name="<?php echo $this->get_field_name('thumbnail_height'); ?>"
                   id="<?php echo $this->get_field_id('thumbnail_height'); ?>"
                   size="3"
                   value="<?php echo ($instance['thumbnail_height'])?$instance['thumbnail_height']:''; ?>" />
        </p>

        <p> <!-- DEFAULT IMAGE -->
            <label for="<?php echo $this->get_field_id('default_image'); ?>"><?php _e('Default image (URL):'); ?></label><br/>
            <input type="text"
                   name="<?php echo $this->get_field_name('default_image'); ?>"
                   id="<?php echo $this->get_field_id('default_image'); ?>"
                   value="<?php echo $instance['default_image']; ?>" />
        </p>
    </div>

    <p> <!-- SHOW POST DATE? -->
        <input type="checkbox"
               id="<?php echo $this->get_field_id('show_date'); ?>"
               name="<?php echo $this->get_field_name('show_date'); ?>"
               value="yes"
               class="tm_lcptu_show-date tm_lcptu_toggle_options"
               <?php if($instance['show_date'] == 'yes') echo $checked; ?>
               />
        <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Show post date'); ?></label>
    </p>

    <div class="<?php echo $this->get_field_id('show_date'); ?>" style="display: <?php echo ($instance['show_date'] == 'yes')?' block':'none'; ?>;">
        <p>
            <label for="<?php echo $this->get_field_id('date_to_show'); ?>"><?php _e('Show post '); ?></label>
            <select id="<?php echo $this->get_field_id('date_to_show'); ?>"
                    name="<?php echo $this->get_field_name('date_to_show'); ?>">
                <option value="publish"<?php echo($instance['date_to_show'] == 'publish')?$selected:''; ?>><?php _e('published date'); ?></option>
                <option value="modified"<?php echo($instance['date_to_show'] == 'modified')?$selected:''; ?>><?php _e('modified date'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('date_format'); ?>"><?php _e('Date format'); ?>:</label><br/>
            <select id="<?php echo $this->get_field_id('date_format'); ?>"
                    name="<?php echo $this->get_field_name('date_format'); ?>"
                    class="tm_lcptu_dropdown_custom">
                <option value="WP"<?php echo($instance['date_format'] == 'WP')?$selected:''; ?>><?php _e('Use WordPress settings'); ?></option>
                <option value="n/j/Y"<?php echo($instance['date_format'] == 'n/j/Y')?$selected:''; ?>>12/13/2012 (No leading zeros)</option>
                <option value="m/d/Y"<?php echo($instance['date_format'] == 'm/d/Y')?$selected:''; ?>>12/13/2012 (Leading zeros)</option>
                <option value="M. j, Y"<?php echo($instance['date_format'] == 'M. j, Y')?$selected:''; ?>>Dec. 8, 2012</option>
                <option value="CUSTOM"<?php echo($instance['date_format'] == 'CUSTOM')?$selected:''; ?>><?php _e('Custom format'); ?></option>
            </select>
        </p>

        <div class="<?php echo $this->get_field_id('date_format'); ?>" style="display: <?php echo ($instance['date_format'] == 'CUSTOM')?'block':'none'; ?>;">
            <p>
                <label for="<?php echo $this->get_field_id('date_format_custom'); ?>"><?php _e('Custom date format'); ?> (<a href="http://php.net/manual/en/function.date.php" target="_blank"><?php _e('help'); ?></a>):</label>
                <input type="text"
                       id="<?php echo $this->get_field_id('date_format_custom'); ?>"
                       name="<?php echo $this->get_field_name('date_format_custom'); ?>"
                       value="<?php echo stripslashes($instance['date_format_custom']); ?>" />
            </p>
        </div>
        <p>
            <input type="checkbox"
                   name="<?php echo $this->get_field_name('show_time'); ?>"
                   id="<?php echo $this->get_field_id('show_time'); ?>"
                   value="yes"
                <?php echo ($instance['show_time'] == 'yes')?$checked:''; ?> />
            <label for="<?php echo $this->get_field_id('show_time'); ?>"><?php _e('Show time after date (WordPress settings only)'); ?></label>
        </p>

    </div>

    <p> <!-- SHOW EXCERPTS? -->
        <input type="checkbox"
               id="<?php echo $this->get_field_id('show_excerpt'); ?>"
               name="<?php echo $this->get_field_name('show_excerpt'); ?>"
               value="yes"
               class="tm_lcptu_show-excerpt tm_lcptu_toggle_options"
               <?php if($instance['show_excerpt'] == 'yes') echo $checked; ?>
               />
        <label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e('Show post excerpts'); ?></label>
    </p>

    <div class="<?php echo $this->get_field_id('show_excerpt'); ?>" style="display: <?php echo ($instance['show_excerpt'] == 'yes')?' block':'none'; ?>;">
        <p> <!-- EXCERPT LENGTH -->
            <label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Excerpt maximum length (in characters):'); ?></label><br />
            <input type="text"
                   name="<?php echo $this->get_field_name('excerpt_length'); ?>"
                   id="<?php echo $this->get_field_id('excerpt_length'); ?>"
                   value="<?php echo $instance['excerpt_length'] ?>"
                   size="4" />
        </p>

        <p> <!-- EXCERPT READ MORE -->
            <label for="<?php echo $this->get_field_id('excerpt_readmore'); ?>"><?php _e('Excerpt read more link text (defaults to nothing if empty):'); ?></label><br />
            <input type="text"
                   name="<?php echo $this->get_field_name('excerpt_readmore'); ?>"
                   id="<?php echo $this->get_field_id('excerpt_readmore'); ?>"
                   value="<?php echo $instance['excerpt_readmore'] ?>" />
        </p>
    </div>

    <p> <!-- SHOW ADVANCED FILTERING OPTIONS? -->
        <input  id="<?php echo $this->get_field_id('show_advanced'); ?>"
                name="<?php echo $this->get_field_name('show_advanced'); ?>"
                class="tm_lcptu_show-advanced tm_lcptu_toggle_options"
                type="checkbox"
                value="yes"
                <?php if($instance['show_advanced'] == 'yes') echo $checked; ?>
            />
        <label 	for="<?php echo $this->get_field_id('show_advanced'); ?>"><?php _e('Filter posts by taxonomy'); ?></label>
    </p>

    <div class="<?php echo $this->get_field_id('show_advanced'); ?>" style="display: <?php echo ($instance['show_advanced'] == 'yes')? 'block' : 'none'; ?>;">
        <p> <!-- TAXONOMY RELATIONS -->
            <label 	for="<?php echo $this->get_field_id('tax_relation'); ?>"><?php _e('Only show posts that are '); ?></label><select
                name="<?php echo $this->get_field_name('tax_relation'); ?>" id="<?php echo $this->get_field_id('tax_relation'); ?>">
            <option value="AND"<?php if($instance['tax_relation'] == 'AND') echo $selected; ?>>in all</option>
            <option value="OR"<?php if($instance['tax_relation'] == 'OR') echo $selected; ?>>in at least one</option>
        </select><?php _e(' of the following taxonomies:'); ?>
        </p>
        <p> <!-- TAXONOMIES FOR POST TYPE(S) -->
            <input type="hidden" class="taxonomies-field-name" value="<?php echo $this->get_field_name('taxonomies'); ?>" />
            <span class="taxonomy-list"><?php echo tm_lcptu_get_taxonomies(
                $instance['post_type'],
                $this->get_field_name('taxonomies'),
                (is_array($instance['taxonomies']))?$instance['taxonomies']:array($instance['taxonomies'])
            ); ?></span>
            <input type="hidden" class="terms-field-name" value="<?php echo $this->get_field_name('tag_list')?>" />
        </p>
        <div class="taxonomy-terms-container">
            <?php
            foreach($instance['taxonomies'] as $taxonomy) {
                if(is_array($instance['tag_list'])) {
                    if(is_array($instance['tag_list'][$taxonomy])) {
                        if(is_array($instance['tag_list'][$taxonomy]['term_ids'])) {
                            if(!$instance['tag_list'][$taxonomy]['post_in'])
                                $instance['tag_list'][$taxonomy]['post_in'] = 'IN';
                        }
                        else {
                            $instance['tag_list'][$taxonomy]['term_ids'] = array();
                            $instance['tag_list'][$taxonomy]['post_in'] = 'IN';
                        }
                        $current_vals = $instance['tag_list'][$taxonomy];
                    }
                    else $current_vals = array('post_in' => 'IN', 'term_ids' => array());
                }
                else $current_vals = array('post_in' => 'IN', 'term_ids' => array());
                echo tm_lcptu_get_terms(
                    $taxonomy,
                    $this->get_field_name('tag_list'),
                    $current_vals
                );
            } ?>
        </div>
    </div>
</div>
