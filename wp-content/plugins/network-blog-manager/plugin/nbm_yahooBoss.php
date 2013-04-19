<?php
/** Yahoo! BOSS v0.06 **/
add_action('init', 'nbm_yahooBoss_init');
add_action('tableSearch_add_header', 'tableSearch_yahooBoss_header');
add_action('tableSearch_add_body', 'tableSearch_yahooBoss_body');
add_action('admin_head', 'nbm_yahooBoss_JS');
add_action('wp_ajax_getyahooBoss', 'nbm_yahooBoss_getYB_action_callback');
###############################################################################
function nbm_yahooBoss_init(){
}#end nbm_yahooBoss_init
###############################################################################
function tableSearch_yahooBoss_header(){
	global $networkBlogManager_optionKey;
	// Retrieve table
	$tableSearch=get_transient("tableSearchHeader");
	// Add a column
	$tableSearch->add_header("yahooBoss",__("Yahoo Total Links",$networkBlogManager_optionKey),13);
	// Write back table
	set_transient("tableSearchHeader",$tableSearch);
}#end tableSearch_yahooBoss_header
###############################################################################
function tableSearch_yahooBoss_body(){
	global $networkBlogManager_optionKey, $nbm_directory;
	// Retrieve table
	$tableSearch=get_transient("tableSearchBody");
	// Populate rows
	for($i=1;$i<=NBS_RESPERPAGE;$i++){
		$blog_id=$tableSearch->get_element($i,'blog_id');
		$tableSearch->add_element($i,"yahooBoss","<span id=\"nmb_yahooBoss_$blog_id\"><img src=\"".$nbm_directory."img/loading12.gif"."\" width=\"12px\" height=\"12px\" onload=\"getYB('$blog_id');\"/></span>");
	}
	// Write back table
	set_transient("tableSearchBody",$tableSearch);
}#end tableSearch_yahooBoss_body
###############################################################################
function nbm_getyahooDataGet($blog_id,$cache_ext="yahooBoss"){
	$blogUrl=get_blogaddress_by_id($blog_id);
	// Get from cache
	$cache_key=md5($networkBlogManager_optionKey.$blogUrl.$cache_ext);
	if(class_exists('nbm_cache')){
		$nbm_cache=new nbm_cache();
		$yahooData_cached=$nbm_cache->get($cache_key);
	}else $yahooData_cached=get_transient($cache_key);
	if(!empty($yahooData_cached)) return $yahooData_cached;
	// Otherwise get from WS
	try{
		$sClient=new SoapClient('http://www.artilibere.net/ws/yahooboss_v01.php?wsdl');
		$params=array(
			'url'=>$blogUrl,
			'caller'=>get_rootSite(),
			'api'=>nbm_getAPIKey()
		);
		$yahooData=$sClient->getData($params);
		// Finally set in cache
		if(class_exists('nbm_cache')) $yahooData_cached=$nbm_cache->set($cache_key, $yahooData, 3600);
		else set_transient($cache_key, $yahooData, 3600);
		return $yahooData;
	} catch(SoapFault $e){
	}
	return NULL;
}#end nbm_getyahooDataGet
###############################################################################
function nbm_getyahooBoss($blog_id){
	$blogUrl=get_blogaddress_by_id($blog_id);
	$yahooData=nbm_getyahooDataGet($blog_id, "yahooBoss");
	if(is_array($yahooData) && !empty($yahooData['TotalHits'])) return $yahooData['TotalHits'];
	else return "N/A";
}#end nbm_getyahooBoss
###############################################################################
function nbm_yahooBoss_JS(){
	global $networkBlogManager_optionKey;
	$jsScript="
<script type=\"text/javascript\">
function getYB(id){
	jQuery(document).ready(function($) {
		var data = {
			action: 'getyahooBoss'
		,	blog_id: id
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#nmb_yahooBoss_'+id).html(response);
		});
	});
}
</script>";
	echo $jsScript;
}#end nbm_yahooBoss_JS
###############################################################################
function nbm_yahooBoss_getYB_action_callback(){
	global $wpdb, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE); 
	$blog_id=filter_input(INPUT_POST, 'blog_id', FILTER_VALIDATE_INT);
	echo nbm_getyahooBoss($blog_id);
	die();
}#end nbm_yahooBoss_getYB_action_callback
###############################################################################
?>