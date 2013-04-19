<?php
/** Google PageRank v0.13 **/
add_action('init', 'nbm_pageRank_init');
add_action('tableSearch_add_header', 'tableSearch_pageRank_header');
add_action('tableSearch_add_body', 'tableSearch_pageRank_body');
add_action('admin_head', 'nbm_pageRank_JS');
add_action('wp_ajax_getpagerank', 'nbm_pageRank_getPR_action_callback');
###############################################################################
function nbm_pageRank_init(){
}#end nbm_pageRank_init
###############################################################################
function tableSearch_pageRank_header(){
	global $networkBlogManager_optionKey;
	// Retrieve table
	$tableSearch=get_transient("tableSearchHeader");
	// Add a column
	$tableSearch->add_header("pageRank",__("Google PR",$networkBlogManager_optionKey),11);
	// Write back table
	set_transient("tableSearchHeader",$tableSearch);
}#end tableSearch_pageRank_header
###############################################################################
function tableSearch_pageRank_body(){
	global $networkBlogManager_optionKey, $nbm_directory;
	// Retrieve table
	$tableSearch=get_transient("tableSearchBody");
	// Populate rows
	for($i=1;$i<=NBS_RESPERPAGE;$i++){
		$blog_id=$tableSearch->get_element($i,'blog_id');
		$tableSearch->add_element($i,"pageRank","<span id=\"nmb_pageRank_$blog_id\"><img src=\"".$nbm_directory."img/loading12.gif"."\" width=\"12px\" height=\"12px\" onload=\"getPR('$blog_id');\"/></span>");
	}
	// Write back table
	set_transient("tableSearchBody",$tableSearch);
}#end tableSearch_pageRank_body
###############################################################################
function nbm_getPageRank($blog_id){
	$blogUrl=get_blogaddress_by_id($blog_id);
	// Get from cache
	$cache_key=md5($networkBlogManager_optionKey.$blogUrl."_googlePageRank");
	if(class_exists('nbm_cache')){
		$nbm_cache=new nbm_cache();
		$gPRData_cached=$nbm_cache->get($cache_key);
	}else $gPRData_cached=get_transient($cache_key);
	if(!empty($gPRData_cached)) return $gPRData_cached;
	// Otherwise get from WS
	try{
		$sClient=new SoapClient('http://www.artilibere.net/ws/googlepagerank_v01.php?wsdl');
		$params=array(
			'url'=>$blogUrl,
			'caller'=>get_rootSite(),
			'api'=>nbm_getAPIKey()
		);
		$gPRData=$sClient->getData($params);
		// Finally set in cache
		if(class_exists('nbm_cache')) $gPRData_cached=$nbm_cache->set($cache_key, $gPRData['GooglePageRank']);
		else set_transient($cache_key, $gPRData['GooglePageRank'], 3600);
		return $gPRData['GooglePageRank'];
	} catch(SoapFault $e){
	}
	return "N/D";
}#end nbm_getPageRank
###############################################################################
function nbm_pageRank_JS(){
	global $networkBlogManager_optionKey;
	$jsScript="
<script type=\"text/javascript\">
function getPR(id){
	jQuery(document).ready(function($) {
		var data = {
			action: 'getpagerank'
		,	blog_id: id
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#nmb_pageRank_'+id).html(response);
		});
	});
}
</script>";
	echo $jsScript;
}#end nbm_pageRank_JS
###############################################################################
function nbm_pageRank_getPR_action_callback(){
	global $wpdb, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE); 
	$blog_id=filter_input(INPUT_POST, 'blog_id', FILTER_VALIDATE_INT);
	echo nbm_getPageRank($blog_id);
	die();
}#end nbm_pageRank_getPR_action_callback
###############################################################################
?>
