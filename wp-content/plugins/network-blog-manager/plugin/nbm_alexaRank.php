<?php
/** Alexa Rank v0.15 **/
add_action('init', 'nbm_alexaRank_init');
add_action('tableSearch_add_header', 'tableSearch_alexaRank_header');
add_action('tableSearch_add_body', 'tableSearch_alexaRank_body');
add_action('admin_head', 'nbm_alexaRank_JS');
add_action('wp_ajax_getalexaRank', 'nbm_alexaRank_getAR_action_callback');
add_action('wp_ajax_getalexaLinks', 'nbm_alexaRank_getALinks_action_callback');
add_action('wp_ajax_getalexaSpeed', 'nbm_alexaRank_getASpeed_action_callback');
add_action('wp_ajax_getalexaSlower', 'nbm_alexaRank_getASpeedPercent_action_callback');
###############################################################################
function nbm_alexaRank_init(){
}#end nbm_alexaRank_init
###############################################################################
function tableSearch_alexaRank_header(){
	global $networkBlogManager_optionKey;
	// Retrieve table
	$tableSearch=get_transient("tableSearchHeader");
	// Add a column
	$tableSearch->add_header("alexaRank",__("Alexa Traffic Rank",$networkBlogManager_optionKey),12);
	$tableSearch->add_header("alexaSitesLink",__("Alexa Sites Links",$networkBlogManager_optionKey),13);
	$tableSearch->add_header("alexaSpeedTime",__("Avg Load Time",$networkBlogManager_optionKey),14);
#	$tableSearch->add_header("alexaSpeedPercent",__("Sites Slower",$networkBlogManager_optionKey),15);
	// Write back table
	set_transient("tableSearchHeader",$tableSearch);
}#end tableSearch_alexaRank_header
###############################################################################
function tableSearch_alexaRank_body(){
	global $networkBlogManager_optionKey, $nbm_directory;
	// Retrieve table
	$tableSearch=get_transient("tableSearchBody");
	// Populate rows
	for($i=1;$i<=NBS_RESPERPAGE;$i++){
		$blog_id=$tableSearch->get_element($i,'blog_id');
		$tableSearch->add_element($i,"alexaRank","<span id=\"nmb_alexaRank_$blog_id\"><img src=\"".$nbm_directory."img/loading12.gif"."\" width=\"12px\" height=\"12px\" onload=\"getAR('$blog_id');\"/></span>");
		$tableSearch->add_element($i,"alexaSitesLink","<span id=\"nmb_alexaSitesLink_$blog_id\"><img src=\"".$nbm_directory."img/loading12.gif"."\" width=\"12px\" height=\"12px\" onload=\"getALinks('$blog_id');\"/></span>");
		$tableSearch->add_element($i,"alexaSpeedTime","<span id=\"nmb_alexaSpeedTime_$blog_id\"><img src=\"".$nbm_directory."img/loading12.gif"."\" width=\"12px\" height=\"12px\" onload=\"getASpeed('$blog_id');\"/></span>");
#		$tableSearch->add_element($i,"alexaSpeedPercent","<span id=\"nmb_alexaSpeedPercent_$blog_id\"><img src=\"".$nbm_directory."img/loading12.gif"."\" width=\"12px\" height=\"12px\" onload=\"getASpeedPercent('$blog_id');\"/></span>");
	}
	// Write back table
	set_transient("tableSearchBody",$tableSearch);
}#end tableSearch_alexaRank_body
###############################################################################
function nbm_alexaDataGet($blog_id,$cache_ext="alexaRank"){
	$blogUrl=get_blogaddress_by_id($blog_id);
	// Get from cache
	$cache_key=md5($networkBlogManager_optionKey.$blogUrl.$cache_ext);
	if(class_exists('nbm_cache')){
		$nbm_cache=new nbm_cache();
		$alexaData_cached=$nbm_cache->get($cache_key);
	}else $alexaData_cached=get_transient($cache_key);
	if(!empty($alexaData_cached)) return $alexaData_cached;
	// Otherwise get from WS
	try{
		$sClient=new SoapClient('http://www.artilibere.net/ws/alexa_v01.php?wsdl');
		$params=array(
			'url'=>$blogUrl,
			'caller'=>get_rootSite(),
			'api'=>nbm_getAPIKey()
		);
		$alexaData=$sClient->getData($params);
		// Finally set in cache
		if(class_exists('nbm_cache')) $alexaData_cached=$nbm_cache->set($cache_key, $alexaData,3600);
		else set_transient($cache_key, $alexaData, 3600);
		return $alexaData;
	} catch(SoapFault $e){
	}
	return NULL;
}#end nbm_alexaDataGet
###############################################################################
/**
 * Alexa Traffic Rank
/**/
function nbm_getalexaRank($blog_id){
	$blogUrl=get_blogaddress_by_id($blog_id);
	$alexaData=nbm_alexaDataGet($blog_id, "alexaRank");
	if(is_array($alexaData) && !empty($alexaData['Traffic'])) return $alexaData['Traffic'];
	else return "N/A";
}#end nbm_getalexaRank
###############################################################################
/**
 * Links from other websites
/**/
function nbm_getalexaSiteslinkingin($blog_id){
	$blogUrl=get_blogaddress_by_id($blog_id);
	$alexaData=nbm_alexaDataGet($blog_id, "alexaSitesLinkingIn");
	if(is_array($alexaData) && !empty($alexaData['SitesLinkingIn'])) return $alexaData['SitesLinkingIn'];
	else return "N/A";
}#end nbm_getalexaSiteslinkingin
###############################################################################
/**
 * Site speed
/**/
function nbm_getalexaSpeedtime($blog_id){
	$blogUrl=get_blogaddress_by_id($blog_id);
	$alexaData=nbm_alexaDataGet($blog_id, "alexaSpeedTime");
	if(is_array($alexaData) && !empty($alexaData['SpeedTime'])) return $alexaData['SpeedTime'].' ms';
	else return "N/A";
}#end nbm_getalexaSpeedtime
###############################################################################
/**
 * Percentage of world's website that are slower
/**/
function nbm_getalexaSpeedpercent($blog_id){
	$blogUrl=get_blogaddress_by_id($blog_id);
	$alexaData=nbm_alexaDataGet($blog_id, "alexaSpeedPercent");
	if(is_array($alexaData) && !empty($alexaData['SpeedPercentage'])) return $alexaData['SpeedPercentage'].'%';
	else return "N/A";
}#end nbm_getalexaSpeedpercent
###############################################################################
function nbm_alexaRank_JS(){
	global $networkBlogManager_optionKey;
	$jsScript="
<script type=\"text/javascript\">
function getAR(id){
	jQuery(document).ready(function($) {
		var data = {
			action: 'getalexaRank'
		,	blog_id: id
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#nmb_alexaRank_'+id).html(response);
		});
	});
}
function getALinks(id){
	jQuery(document).ready(function($) {
		var data = {
			action: 'getalexaLinks'
		,	blog_id: id
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#nmb_alexaSitesLink_'+id).html(response);
		});
	});
}
function getASpeed(id){
	jQuery(document).ready(function($) {
		var data = {
			action: 'getalexaSpeed'
		,	blog_id: id
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#nmb_alexaSpeedTime_'+id).html(response);
		});
	});
}
function getASpeedPercent(id){
	jQuery(document).ready(function($) {
		var data = {
			action: 'getalexaSlower'
		,	blog_id: id
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#nmb_alexaSpeedPercent_'+id).html(response);
		});
	});
}
</script>";
	echo $jsScript;
}#end nbm_alexaRank_JS
###############################################################################
function nbm_alexaRank_getAR_action_callback(){
	global $wpdb, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE); 
	$blog_id=filter_input(INPUT_POST, 'blog_id', FILTER_VALIDATE_INT);
	echo nbm_getalexaRank($blog_id);
	die();
}#end nbm_alexaRank_getAR_action_callback
###############################################################################
function nbm_alexaRank_getALinks_action_callback(){
	global $wpdb, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE); 
	$blog_id=filter_input(INPUT_POST, 'blog_id', FILTER_VALIDATE_INT);
	echo nbm_getalexaSiteslinkingin($blog_id);
	die();
}#end nbm_alexaRank_getALinks_action_callback
###############################################################################
function nbm_alexaRank_getASpeed_action_callback(){
	global $wpdb, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE); 
	$blog_id=filter_input(INPUT_POST, 'blog_id', FILTER_VALIDATE_INT);
	echo nbm_getalexaSpeedtime($blog_id);
	die();
}#end nbm_alexaRank_getASpeed_action_callback
###############################################################################
function nbm_alexaRank_getASpeedPercent_action_callback(){
	global $wpdb, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE); 
	$blog_id=filter_input(INPUT_POST, 'blog_id', FILTER_VALIDATE_INT);
	echo nbm_getalexaSpeedpercent($blog_id);
	die();
}#end nbm_alexaRank_getASpeedPercent_action_callback
###############################################################################
?>
