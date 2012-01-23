<?php
// when you want to make a method accessible via api, u start with _

/*
 * Used for external api calls
 */
class API {

    var $bp_gtm;
    var $user;
    var $req;

    function __construct() {
        global $bp;

        // Some BP specific vars
        $this->bp_gtm = get_option('bp_gtm');
        $this->user = $bp->loggedin_user;

        // API engine
        $this->req = self::convert_array_to_object($_REQUEST);

        If(substr($this->req->action, 0, 6) == 'create')
            $this->create($this->req);
        else
            $this->do_();
    }

    private function do_() {

        /*
          little protection with _ (only methods start with _ can be executed via POST['operation'])
          additional methods needed should not start with underscore.
         */

        // added support for both method_names and mapped ones above.
        $action = '_' . $this->req->action;

        if (method_exists(API, $action))
            $this->return_response($this->$action());
        else
            $this->return_response(array("error" => "Invalid request, method not found"));
    }

    /*
     * Get all tasks (user defined and not)
     */
    private function _getTasks() {
        return $this->get_tasks($this->req);
    }
    function get_tasks($arg) {
        global $bp;

        $defaults->username = false;
        $defaults->filter = 'deadline'; // accepted: deadline|alpha|done|undone|project|group
        $defaults->limit = '0-10';
        $defaults->in_id = false; // that's ID; use only when filter = project|group, otherwise - ignored

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        $limit = explode('-',$arg->limit);
        $arg->limit = array(
                    'miss'=> $limit[0],
                    'per_page'=> $limit[1],
                );

        $user = get_user_by('login', $arg->username);

        if(bp_gtm_get_access($user->id, $bp->groups->current_group->id, 'task_view')) {

            if($user) // get for defined user
                $tasks = BP_GTM_Personal::get_tasks($user->id, $arg->filter, $arg->limit, $arg->in_id);
            else // get all tasks
                $tasks = bp_gtm_get_tasks(false, $arg->filter, $arg->in_id, $arg->limit);

            if (count($tasks) > 0)
                $out =  $this->output('success', 'default', $tasks);
            else
                $out =  $this->output('warning', 'no_data');

        }else{
            $out =  $this->output('error', 'no_rights');
        }

        return $out;
    }

    /*
     * Get only one task (by ID)
     */
    private function _getTask(){
        return $this->get_task($this->req);
    }
    function get_task($arg) {
        global $bp;

        $defaults->id = false; // id of a task. should be integer

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        if(bp_gtm_get_access($this->user->id, $bp->groups->current_group->id, 'task_view')) {

            $task = BP_GTM_Tasks::get_task_by_id($arg->id);

            if (count($task) > 0)
                $out =  $this->output('success', 'default', $task);
            else
                $out =  $this->output('warning', 'no_data');

        }else{
            $out =  $this->output('error', 'no_rights');
        }

        return $out;
    }

    /*
     * Get all projects (user defined and not)
     */
    private function _getProjects() {
        return $this->get_projects($this->req);
    }
    function get_projects($arg) {
        global $bp;

        $defaults->username = false;
        $defaults->filter = 'deadline'; // accepted: deadline|alpha for user and deadline|alpha|done|undone|group for full list
        $defaults->group_id = $bp->groups->current_group->id; // that's ID; use only when filter = group, otherwise - ignored
        $defaults->done = 0; // default is to display undeno projects. Done = 1. Undone = 0. For user only.

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        $user = get_user_by('login', $arg->username);

        if(bp_gtm_get_access($user->id, $arg->group_id, 'project_view')) {

            if($user) // get for defined user
                $projects = BP_GTM_Personal::get_projects($user->id, $arg->filter, $arg->done);
            else // get all projects
                $projects = bp_gtm_get_projects($arg->group_id, $arg->filter);

            if (count($projects) > 0)
                $out =  $this->output('success', 'default', $projects);
            else
                $out =  $this->output('warning', 'no_data');

        }else{
            $out =  $this->output('error', 'no_rights');
        }

        return $out;
    }

    /*
     * Get only 1 project (by ID)
     */
    private function _getProject() {
        return $this->get_project($this->req);
    }
    function get_project($arg) {
        global $bp;

        $defaults->id = false;

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        if(bp_gtm_get_access($this->user->id, $bp->groups->current_group->id, 'project_view')) {

            $project = BP_GTM_Projects::get_project_by_id($arg->id);

            if (count($project) > 0)
                $out =  $this->output('success', 'default', $project);
            else
                $out =  $this->output('warning', 'no_data');

        }else{
            $out =  $this->output('error', 'no_rights');
        }

        return $out;
    }

    /*
     * Get all discussion posts for specified task or project
     */
    private function _getPosts(){
        return $this->get_posts($this->req);
    }
    function get_posts($arg){
        global $bp;

        $defaults->el_type = 'tasks';
        $defaults->el_id = false;

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        if(bp_gtm_get_access($this->user->id, $bp->groups->current_group->id, 'discuss_view')) {

            $posts = BP_GTM_Discussion::get_posts($arg->el_id, $arg->el_type);

            if (count($posts) > 0)
                $out =  $this->output('success', 'default', $posts);
            else
                $out =  $this->output('warning', 'no_data');
            
        }else{
            $out =  $this->output('error', 'no_rights');
        }
        return $out;
    }

    /*
     * Get the list of tasks or projects that were discussed recently
     */
    private function _getDiscussed(){
        return $this->get_discussed($this->req);
    }
    function get_discussed($arg){
        global $bp;

        $defaults->group_id = false;
        $defaults->filter = 'tasks'; // accepted: tasks|projects
        $defaults->limit = '0-10'; // offset and number to display

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        $limit = explode('-',$arg->limit);
        $arg->limit = array(
                    'miss'=> $limit[0],
                    'per_page'=> $limit[1],
                );

        if(bp_gtm_get_access($this->user->id, $bp->groups->current_group->id, 'discuss_view')) {

            $posts = BP_GTM_Discussion::get_list($arg->group_id, $arg->filter, $arg->limit);

            if (count($posts) > 0)
                $out =  $this->output('success', 'default', $posts);
            else
                $out =  $this->output('warning', 'no_data');

        }else{
            $out =  $this->output('error', 'no_rights');
        }
        return $out;
    }

    /*
     * Create a discussion post
     */

    /*
     * Get all or group only label
     */
    private function _getLabels(){
        return $this->get_labels($this->req);
    }
    function get_labels($arg){
        global $bp;

        $defaults->group_id = false;

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        if( bp_gtm_get_access($this->user->id, $bp->groups->current_group->id, 'taxon_view') ){

            if ( $arg->group_id )
                $labels = BP_GTM_Taxon::get_terms_in_group($arg->group_id, 'tag');
            else
                $labels = BP_GTM_Taxon::get_all_terms('tag', true);
                

            if (count($labels) > 0)
                $out =  $this->output('success', 'default', $labels);
            else
                $out =  $this->output('warning', 'no_data');

        }else{
            $out =  $this->output('error', 'no_rights');
        }
        return $out;
    }

    /*
     * Get labels for task or project
     */
    private function _getElLabels(){
        return $this->get_el_labels($this->req);
    }
    function get_el_labels($arg){
        global $bp;

        $defaults->group_id = $bp->groups->current_group->id; // in which group to search
        $defaults->el_id = false; // ID of el_type
        $defaults->el_type = 'task'; // allowed: task | project

        $arg = (object) array_merge( (array)$defaults, (array)$arg );

        if( bp_gtm_get_access($this->user->id, $arg->group_id, 'taxon_view') ){

            if ( $arg->el_type == 'task' )
                $labels = BP_GTM_Taxon::get_terms_4task($arg->group_id, $arg->el_id, 'tag');
            elseif ( $arg->el_type == 'project' )
                $labels = BP_GTM_Taxon::get_terms_4project($arg->group_id, $arg->el_id, 'tag');


            if (count($labels) > 0)
                $out =  $this->output('success', 'default', $labels);
            else
                $out =  $this->output('warning', 'no_data');

        }else{
            $out =  $this->output('error', 'no_rights');
        }
        return $out;
    }

    /*
     * A list of create_ methods
     * Primary oriented to POST
     * Using _ to work with GET too
     */
    function create($arg){

        if ( $arg->action == 'createLabel' )
            $out = $this->create_label($arg);
        elseif( $arg->action == 'createPost' )
            $out = $this->create_post($arg);
        elseif( $arg->action == 'createTask' )
            $out = $this->create_task($arg);

        $this->return_response($out);
    }

    private function _createLabel(){
        return $this->create_label($this->req);
    }
    function create_label($data){
        global $bp, $wpdb;

        $name = apply_filters('bp_gtm_term_name_content', $this->req->name);
        
        $group_id = $this->req->group_id;
        if(!$group_id || !is_numeric($group_id) )
            $group_id = $bp->groups->current_group->id;

        $taxon =$this->req->taxon;
        if ($taxon != 'tag')
            $taxon = 'tag';

        if( bp_gtm_get_access($this->user->id, $group_id, 'taxon_create') ){
            if ($name && $group_id){

                $inserted_term = $wpdb->query($wpdb->prepare("
                    INSERT INTO " . $bp->gtm->table_terms . " ( `name`, `taxon`, `group_id` )
                    VALUES ( %s, %s, %d )
                    ", $name, $taxon, $group_id));

                 if ($inserted_term)
                    $out = $this->output('success', 'created', array(0 => array('name' => $name, 'taxon' => $taxon, 'group_id' => $group_id)));
                 else
                    $out = $this->output('error', 'not_created');

            }else{
                $out = $this->output('error', 'no_data');
            }
        }else{
            $out =  $this->output('error', 'no_rights');
        }

        return $out;
    }

    private function _createPost(){
        return $this->create_label($this->req);
    }
    function create_post($data){
        global $bp, $wpdb;

        $text = apply_filters('bp_gtm_discuss_text_content', $this->req->text);
        $group_id = $this->req->group_id;
        $el_id = $this->req->el_id;
        $el_type = $this->req->el_type;
        $user_id = $this->req->user_id;
        if (!$user_id) $user_id = $bp->loggedin_user->id;
        if (!$group_id) $group_id = $bp->groups->current_group->id;
        if (!$el_type) $el_type = 'task';

        if( bp_gtm_get_access($this->user->id, $group_id, 'discuss_create') ){
            if ($text && $user_id && $group_id && $el_id && $el_type){

                if ($el_type == 'project'){
                    $task_id = 0;
                    $project_id = $el_id;
                }elseif ($el_type == 'task'){
                    $project_id = 0;
                    $task_id = $el_id;
                }

                $inserted_post = $wpdb->query($wpdb->prepare("
                    INSERT INTO {$bp->gtm->table_discuss} ( `text`, `author_id`, `task_id`, `project_id`, `group_id`, `date_created` )
                    VALUES ( %s, %d, %d, %d, %d, NOW() )
                    ", $text, $user_id, $task_id, $project_id, $group_id));
                $post_id = $wpdb->insert_id; // id of a newly created post

                if ($task_id != '0') {
                    $insert_in_task = $wpdb->query($wpdb->prepare("
                         UPDATE {$bp->gtm->table_tasks}
                         SET `discuss_count` = `discuss_count` + 1
                         WHERE `id` = $task_id"));
                     $elem_type = 'discuss_tasks_' . $task_id;
                }
                if ($project_id != '0') {
                    $insert_in_project = $wpdb->query($wpdb->prepare("
                         UPDATE {$bp->gtm->table_projects}
                         SET `discuss_count` = `discuss_count` + 1
                         WHERE `id` = $project_id"));
                     $elem_type = 'discuss_projects_' . $project_id;
                }

                 if ($inserted_post) {
                    bp_gtm_group_activity(array(
                        'user_id' => $user_id,
                        'group_id' => $group_id,
                        'elem_id' => $post_id,
                        'elem_type' => $elem_type,
                        'elem_name' => $text
                    ));
                    $out = $this->output('success', 'created', array(
                                    0 => array(
                                                'text' => $text,
                                                'author_id' => $user_id,
                                                'group_id' => $group_id,
                                                'task_id' => $task_id, 
                                                'project_id' => $project_id, 
                                                'date_created' => date( 'Y-m-d G:i', time() )
                                                )
                                    )
                                );
                 }else{
                    $out = $this->output('error', 'not_created');
                 }

            }else{
                $out = $this->output('error', 'no_data');
            }
        }else{
            $out =  $this->output('error', 'no_rights');
        }

        return $out;
    }

    private function _createTask(){
        return $this->create_task($this->req);
    }
    function create_task($data){
        global $bp, $wpdb;

        $name = apply_filters('bp_gtm_task_name_content', $this->req->name);
        $description = apply_filters('bp_gtm_task_desc_content', $this->req->desc);
        $group_id = $this->req->group_id;
        $project_id = $this->req->project_id;
        $status = $this->req->status;
        $users = $this->req->users;
        $creator = $this->req->creator;
        $parent = $this->req->parent;
        $order = $this->req->order;
        if (!$users) $users = $bp->loggedin_user->id;
        if (!$creator) $creator = $bp->loggedin_user->id;
        if (!$group_id) $group_id = $bp->groups->current_group->id;
        if (!$status) $status = 'pending';
        if (!$parent) $parent = '0';
        if (!$order) $order = '0';

        $resps = explode(',', $users);

        if( bp_gtm_get_access($this->user->id, $group_id, 'task_create') ){
            if ( $name && $group_id && $project_id && (count($resps) > 0) ){

                // save data to tasks table
                $inserted_task = $wpdb->query($wpdb->prepare("
                    INSERT INTO " . $bp->gtm->table_tasks . " ( `name`, `desc`, `status`, `parent_id`, `group_id`, `creator_id`, `project_id`, `resp_id`, `date_created`, `deadline`)
                    VALUES ( %s, %s, %s, %d, %d, %d, %d, %s, NOW(), %s )
                    ", $name, $description, $status, $parent, $group_id, $creator, $project_id, $resps, $order));
                $task_id = $wpdb->insert_id; // id of a newly created project

                if ($inserted_task){
                    // save data to resps table
                    bp_gtm_save_g_resps($task_id, $project_id, $group_id, $resps);
                    // notify responsible people about responsibilities
                    foreach ($resps as $resp) {
                        bp_core_add_notification($task_id, $resp, 'gtm', 'task_created', $group_id);
                    }

                    $out = $this->output('success', 'created', array(0 => array('name' => $name, 'taxon' => $taxon, 'group_id' => $group_id)));
                }else{
                    $out = $this->output('error', 'not_created');
                }

            }else{
                $out = $this->output('error', 'no_data');
            }
        }else{
            $out =  $this->output('error', 'no_rights');
        }

        return $out;
    }

    private function _test(){
        $data = array('1');
        update_option('aaa_test', $data);
        $out = get_option('aaa_test');
        return $out;
    }

    /*
     * Print results
     */
    function output($type, $what, $data = false){
        if(!$data) {
            $count = 0;
            $data = '';
        }else{
            $count = count($data);
        }

        switch ($what){
            case 'no_rights':
                $message = __('Sorry, not enough rights.', 'bp_gtm');
                break;
            case 'no_data':
                $message = __('Unfortunately, there is nothing to show yet.', 'bp_gtm');
                break;
            case 'default':
                $message = __('The request was processed successfully.', 'bp_gtm');
                break;
            case 'no_data':
                $message = __('You didn\'t provide the required data.', 'bp_gtm');
                break;
            case 'created':
                $message = __('You have successfully created an element.', 'bp_gtm');
                break;
            case 'not_created':
                $message = __('There was an error while creating an element.', 'bp_gtm');
                break;
        }

        return array(
                            'status' => $type, // success|error
                            'message' => $message,
                            'items' =>array(
                                    'count' => $count,
                                    'data' => $data
                                )
                            );
    }

    function return_response($array) {

        header('Content-Type: application/json');
        header('Accept: application/json');

        if ($array == "")
            $array = array();

        if ($_REQUEST['callback'] == "")
            echo json_encode($array);
        else
            echo $_REQUEST['callback'] . "(" . json_encode($array) . ")";
    }

    function convert_array_to_object($arr) {
        $obj = new stdClass();
        foreach ($arr as $k => $v) {
            if ($v != "") {
                $obj->$k = $v;
            }
        }
        return $obj;
    }

}
