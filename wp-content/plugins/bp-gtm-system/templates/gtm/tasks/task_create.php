<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$parent_task = parse_url($url, PHP_URL_QUERY);
if (is_numeric(parse_url($url, PHP_URL_QUERY)) && parse_url($url, PHP_URL_QUERY) > 0) {
    $parent_task = parse_url($url, PHP_URL_QUERY);
    $h4_title = __('Create New SubTask for', 'bp_gtm') . ' <em>"' . bp_gtm_get_el_name_by_id($parent_task, 'task') . '"</em>';
} else {
    $parent_task = 0;
    $h4_title = __('Create New Task', 'bp_gtm');
}
?>

<h4><?php echo $h4_title ?></h4>

<?php do_action('bp_before_gtm_task_create'); ?>

<p>
    <label for="task_name">* <?php _e('Task name', 'bp_gtm'); ?></label>
    <input type="text" name="task_name" id="task_name" value="" />
</p>

<p>
    <label for="task_desc">* <?php _e('Task description', 'bp_gtm') ?></label>
    <?php
    if (function_exists('wp_editor') && $bp_gtm['mce'] == 'on') {
        wp_editor(
                '', // initial content
                'gtm_desc', // ID attribute value for the textarea
                array(
            'media_buttons' => false,
            'textarea_name' => 'task_desc',
                )
        );
    } else {
        ?>
        <textarea name="task_desc" id="gtm_desc"></textarea>
    <?php } ?>
</p>

<label for="task_resp"><?php _e('Who is responsible for this task execution or has access if its hidden?', 'bp_gtm'); ?></label>
<?php bp_gtm_filter_users($bp_gtm['theme']) ?>
<?php do_action('bp_gtm_task_extra_fields_editable') ?>

<?php $count_tasks = bp_gtm_task_project($parent_task); ?>

<label for="task_deadline">* <?php _e('Task Deadline', 'bp_gtm'); ?> [yyyy-mm-dd]</label>
<input type="text" name="task_deadline" id="task_deadline" value="" readonly="readonly"/>
<div>
    <div class="float">
        <label for="tags"><?php _e('Task Tags', 'bp_gtm');
_e('(comma separated)', 'bp_gtm'); ?></label>
        <ul class="first acfb-holder">
            <li>
                <input type="text" name="task_tags" class="tags" id="tags" />
            </li>
        </ul>
    </div>
    <div class="right">
        <label for="cats"><?php _e('Task Categories', 'bp_gtm');
echo ' ';
_e('(comma separated)', 'bp_gtm'); ?></label>
        <ul class="second acfb-holder">
            <li>
                <input type="text" name="task_cats" class="cats" id="cats" />
            </li>
        </ul>
    </div>
</div>  
<?php do_action('bp_after_gtm_task_create'); ?>


<input type="hidden" name="task_creator" value="<?php echo $bp->loggedin_user->id; ?>" />
<input type="hidden" name="task_parent" value="<?php echo $parent_task ?>" />
<input type="hidden" name="task_group" value="<?php bp_current_group_id() ?>" />
<input type="hidden" name="task_tag_names" id="tag_names" value="" class="" />
<input type="hidden" name="task_cat_names" id="cat_names" value="" class="" />

<p>&nbsp;</p><div class="clear-both"></div>
<p><input type="submit" value="<?php _e('Create Task', 'bp_gtm') ?> &rarr;" id="save" name="saveNewTask" /></p>
<?php wp_nonce_field('bp_gtm_new_task') ?>
