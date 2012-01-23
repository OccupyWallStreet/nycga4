<?php
// get all allowed file types
function bp_gtm_files_types($all = false){
    $types['archives'] = array('gz','zip','rar','7z','tar');
    $types['media'] = array('bmp','gif','jpeg','jpg','png','mp3','mov','avi','3gp','mp4','wav','ogg','flv');
    $types['documents'] = array('pdf','djvu','txt','rtf','doc','docx','xls','xlsx','ppt','pptx');
    $types['other'] = array('xml','json','css');
    
    if($all)
        $types = array('xml','json','css','gz','zip','rar','7z','tar','pdf','djvu','txt','rtf','doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx','bmp','gif','jpeg','jpg','png','mp3','mov','avi','3gp','mp4','wav','ogg','flv');
    
    return $types;
}

add_action('bp_gtm_discuss_new_reply_after', 'bp_gtm_files_form');
function bp_gtm_files_form($bp_gtm){
    if (!empty($bp_gtm['files']) && $bp_gtm['files'] == 'on')
        for($i = 1; $i <= $bp_gtm['files_count']; $i++){
            echo '
            <div class="gtm_files">
                <input type="file" name="gtmFile_' . $i . '" id="gtmFile">
            </div>';
        }
}

add_action('bp_gtm_discuss_after_content','bp_gtm_files_discuss_display');
function bp_gtm_files_discuss_display($post){
    global $wpdb,$bp;
    $files = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$bp->gtm->table_files} WHERE `discuss_id` = {$post->id}"));
    if(!empty($files)){        
        echo '<div class="clear"></div><div class="gtm_files_list"><ul>';
        foreach($files as $file){
            $name = str_replace('/', '', substr($file->path, strrpos($file->path,'/'), strlen($file->path)-1)); // what's the name
            echo '<li><a href="' . WP_CONTENT_URL . $file->path . '">'.$name.'</a></li>';
        }
        echo '</ul></div>';
    }
}

add_action('bp_gtm_save_discussion_post', 'bp_gtm_files_save',1,6);
function bp_gtm_files_save($elem_type, $owner_id, $post_id, $task_id, $project_id, $group_id){
    $return = false;
    
    if(!empty($_FILES)){
        global $bp, $wpdb;
        $bp_gtm = get_option('bp_gtm');
        
        $allowed_filetypes = $bp_gtm['files_types'];

        if($elem_type == 'task')
            $elem_id = $task_id;
        elseif($elem_type == 'project')
            $elem_id = $project_id;
        else
            $elem_id = $post_id;

        $upload_path = WP_CONTENT_DIR . '/uploads/gtm/files/' . $elem_type . '/'; // where to upload
        if(!file_exists($upload_path))
            wp_mkdir_p($upload_path);

        foreach($_FILES as $form => $file){
            $ext = str_replace('.', '', substr($file['name'], strrpos($file['name'],'.'), strlen($file['name'])-1)); // what's the extension
            if(!in_array($ext, $allowed_filetypes)) { //not allowed file
                $return['messages'][] = sprintf(__('You cannot upload %s-files.','bp_gtm'), $file['name']);
                unset($_FILES[$form]);
            }
        }

        foreach($_FILES as $file){
            if(empty($file['name'])) continue;
            if( move_uploaded_file( $file['tmp_name'], $upload_path . urlencode($elem_id . '_' . $file['name']) ) ){
                $wpdb->insert( $bp->gtm->table_files, array( 
                            'task_id' => $task_id,
                            'project_id' => $project_id,
                            'discuss_id' =>$post_id,
                            'owner_id' => $owner_id,
                            'group_id' => $group_id,
                            'path' => '/uploads/gtm/files/' . $elem_type . '/' . urlencode($elem_id . '_' . $file['name'])
                    ), array( '%d', '%d', '%d', '%d', '%d', '%s' ) );
                $return['file_id'][] = $wpdb->insert_id;
            }else{
                $return['messages'][] = sprintf(__('There was an error uploding %s. Do you have enough rights?','bp_gtm'), $file['name']);
            }
        }
        return $return;
    }
    return $return;
}

add_action('admin_init', 'file_meta_box');
function file_meta_box(){
add_meta_box('bp-gtm-admin-files', __('Tasks/Projects/Discussion Posts Files Management', 'bp_gtm'), 'on_bp_gtm_admin_files', 'buddypress_page_bp-gtm-admin', 'normal', 'core');
}        
function on_bp_gtm_admin_files() {
        $bp_gtm = get_option('bp_gtm');
        echo '<p>' . __('If you want your users and group members upload files into tasks, projects and discussion post, you can set all the options here.', 'bp_gtm') . '</p>';
        echo '<p>' . __('First of all you need to decide - do you need file management?', 'bp_gtm') . '</p>';
        echo '<p><input name="bp_gtm_files" id="bp_gtm_files_on" type="radio" value="on" ' . ('on' == $bp_gtm['files'] ? 'checked="checked" ' : '') . '/> <label for="bp_gtm_files_on">' . __('Enable', 'bp_gtm') . '</label></p>
            <p><input name="bp_gtm_files" id="bp_gtm_files_off" type="radio" value="off" ' . ('off' == $bp_gtm['files'] ? 'checked="checked" ' : '') . '/> <label for="bp_gtm_files_off">' . __('Disable', 'bp_gtm') . '</label></p>';

        echo '<hr />';

        echo '<p>' . __('Up to how many files would you like to allow users to upload for each task, project or discussion post?', 'bp_gtm') . '</p>';
        echo '<p><input name="bp_gtm_files_count" id="bp_gtm_files_count"  value="' . $bp_gtm['files_count'] . '" /> &rarr; ' . __('Should be numeric, otherwise will not be saved. Set 0 for unlimited number.', 'bp_gtm');

        echo '<hr />';

        echo '<p>' . __('Which file types would you like users have ability to upload?', 'bp_gtm') . '</p>';
        echo '<p>';
        $all_types = bp_gtm_files_types();
        $i = 0;
        foreach ($all_types as $slug => $types) {
            if ($slug == 'media')
                $name = __('Media', 'bp_gtm');
            elseif ($slug == 'archives')
                $name = __('Archives', 'bp_gtm');
            elseif ($slug == 'documents')
                $name = __('Documents', 'bp_gtm');
            else
                $name = __('Other', 'bp_gtm');
            echo '<p><strong>' . $name . ': </strong>';

            foreach ($types as $type) {
                $checked = '';
                if (!empty($bp_gtm['files_types']) && in_array($type, $bp_gtm['files_types'])) {
                    $checked = 'checked="checked"';
                }
                echo ' <input type="checkbox" name="bp_gtm_files_types[]" id="bp_gtm_files_types_' . $i . '" ' . $checked . ' value="' . $type . '" /><label for="bp_gtm_files_types_' . $i . '">' . $type . '</label>;';
                $i++;
            }
            echo '</p>';
        }
        echo '</p>';

        echo '<hr />';

        echo '<p>' . __('What is the maximum size of uploaded file?', 'bp_gtm') . '</p>';
        $upload_size_unit = wp_max_upload_size();
        $sizes = array('KB', 'MB', 'GB');
        for ($u = -1; $upload_size_unit > 1024 && $u < count($sizes) - 1; $u++)
            $upload_size_unit /= 1024;
        if ($u < 0) {
            $upload_size_unit = 0;
            $u = 0;
        } else {
            $upload_size_unit = (int) $upload_size_unit;
        }
        echo '<p><input name="bp_gtm_files_size" id="bp_gtm_files_size"  clas="text-right" value="' . $bp_gtm['files_size'] . '" />KB &rarr; ' . sprintf(__('Please remember that 1MB = 1024KB. Should be less than WordPress limit: %d%s', 'bp_gtm'), $upload_size_unit, $sizes[$u]);
    }