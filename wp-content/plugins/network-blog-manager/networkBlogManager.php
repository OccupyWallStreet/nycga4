<?php
/**
Plugin Name: Network Blog Manager
Plugin URI: http://wordpress.org/extend/plugins/network-blog-manager/
Description: Administrator Tool for Multisite Blog's Network
Version: 0.354
Author: Carlo Gandolfo
Author URI: mailto:carlo@artilibere.com
Licence: GPL2
Text Domain: networkBlogManager
 /**/
/**
 * Copyright 2010  Carlo Gandolfo (email : carlo@artilibere.com)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
/**/
global $networkBlogManager_optionKey, $capability, $tld_url, $nbm_directory;
$networkBlogManager_optionKey='networkBlogManager';
$capability="list_users";

add_action('init', 'nbm_startup');
add_action('admin_menu', 'networkBlogManagerMenu');
add_action('admin_head', 'networkBlogManager_JS');
add_action('wp_ajax_searchfilter', 'nbm_searchFilter_action_callback');
add_action('wp_ajax_confirmdomain', 'nbm_confirmDomain_action_callback');
add_action('wp_ajax_emptycache', 'nbm_emptyCache_action_callback');

define('NBS_RESPERPAGE',10);
define('NBS_PAGENAVRANGE',3);
define('NBM_CACHE',TRUE);

require_once("plugin/nbm_cache.php");
require_once("plugin/nbm_searchTable.php");
require_once("plugin/nbm_pageRank.php");
require_once("plugin/nbm_alexaRank.php");
require_once("plugin/nbm_yahooBoss.php");
###############################################################################
function nbm_startup(){
	global $networkBlogManager_optionKey, $nbm_directory;
	$nbm_directory=trailingslashit(plugins_url(basename(dirname(__FILE__))));
	wp_enqueue_script('jquery');
	load_plugin_textdomain($networkBlogManager_optionKey,false,$nbm_directory.'lang');
}#end nbm_startup
###############################################################################
/**
 * Create the admin menu link for Blog Manager
 */
function networkBlogManagerMenu() {
	global $networkBlogManager_optionKey,$capability,$nbm_directory;
	#if(is_super_admin() && MULTISITE){
	if(is_super_admin()){
		add_dashboard_page(
			__('Manage Blogs in your Network',$networkBlogManager_optionKey),
			__('Blog Manager',$networkBlogManager_optionKey),
			$capability,
			$networkBlogManager_optionKey,
			$networkBlogManager_optionKey);
	}
	// Load CSS
	$styleDir="/".basename($nbm_directory)."/css/";
	$nbm_styleUrl=WP_PLUGIN_URL.$styleDir.$networkBlogManager_optionKey.'.css';
	$nbm_styleFile=WP_PLUGIN_DIR.$styleDir.$networkBlogManager_optionKey.'.css';
	if(file_exists($nbm_styleFile)) {
		wp_register_style($networkBlogManager_optionKey, $nbm_styleUrl);
		wp_enqueue_style($networkBlogManager_optionKey);
	}
}#end networkBlogManagerMenu
###############################################################################
/**
 * Create the form to search agencies
 */
function networkBlogManager() {
	global $wpdb, $networkBlogManager_optionKey, $order_by, $order_dir, $tld_url, $nbm_directory;
	$title = __('Network Blog Admin Dashboard',$networkBlogManager_optionKey);
	$out = "<div class=\"wrap\">";
	screen_icon();
	$out.= "	<h2>".esc_html($title)."</h2>";
	$out.= "	<div id=\"message\" class=\"updated fade\" style='display:none;'></div>";

	$search_url=filter_input(INPUT_POST, 'sage_u', FILTER_SANITIZE_URL);
	$search_mail=filter_input(INPUT_POST, 'sage_m', FILTER_SANITIZE_EMAIL);
	
	// Draw the form
	$formSearch="
		<form method=\"GET\" action=\"\">
			<input type=\"hidden\" name=\"page\" value=\"$networkBlogManager_optionKey\">
			<div class=\"tablenav\">
				<div class=\"alignleft actions\">
					<label for=\"sage_u\">".__("Domain",$networkBlogManager_optionKey)."</label>
					<input type=\"text\" id=\"sage_u\" maxlength=\"75\" size=\"50\" name=\"sage_u\" />
					<span class=\"button-secondary\" onclick=\"searchFilter();return false;\">".__("Filter",$networkBlogManager_optionKey)."</span>
					<label for=\"sage_m\">".__("E-mail",$networkBlogManager_optionKey)."</label>
					<input type=\"text\" id=\"sage_m\" maxlength=\"25\" size=\"15\" name=\"sage_m\" />
					<span class=\"button-secondary\" onclick=\"searchFilter();return false;\">".__("Filter",$networkBlogManager_optionKey)."</span>
					<input type=\"hidden\" id=\"orderby\" name=\"orderby\" value=\"blog_id\" />
					<input type=\"hidden\" id=\"orderdir\" name=\"orderdir\" value=\"asc\" />
				</div>
			</div>
		</form>";
	$out.=$formSearch;

	// Init the Search Table
	$tableSearch=new nbm_searchTable(NBS_RESPERPAGE);

	$tableSearch->add_header("blog_id","#",0);
	$tableSearch->add_header("nbm_domain",__("Blog",$networkBlogManager_optionKey),10);
	#$tableSearch->add_header("nbm_email",__("E-mail",$networkBlogManager_optionKey),20);
	$tableSearch->add_header("nbm_regdate",__("Registered",$networkBlogManager_optionKey),30);
	$tableSearch->add_header("nbm_numcontents",__("Contents",$networkBlogManager_optionKey),40);
	
		
	set_transient("tableSearchHeader",$tableSearch);
	do_action('tableSearch_add_header');
	$tableSearch=get_transient("tableSearchHeader");
	
	$tableResults= "
		<table width=\"100%\" cellpadding=\"3\" cellspacing=\"3\" class=\"widefat\">
			<thead><tr>".$tableSearch->get_header()."</tr></thead>
			<tbody id=\"tableResults\">
				<tr><td colspan=\"".$tableSearch->get_columnCount()."\">&nbsp;</td></tr>
			</tbody>
		</table>";
	$out.=$tableResults;
	$out.="</div>";
	echo $out;
}#end networkBlogManager
###############################################################################
function nbm_searchFilter_action_callback(){
	global $wpdb, $networkBlogManager_optionKey, $tld_url,$nbm_directory;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE);
	$time_start=microtime(true);
	
	$res=$wpdb->get_results("SELECT domain from $wpdb->blogs WHERE blog_id=1");
	$tld_url=".".$res[0]->domain;

	// Build the filter condition
	$sqlBlogSearchFilter="";$sqlNBSFilter="";$sqlNBSOrder="";$order_by="";
	$search_url=filter_input(INPUT_POST, 'sage_u', FILTER_SANITIZE_URL);
	$search_mail=filter_input(INPUT_POST, 'sage_m', FILTER_SANITIZE_EMAIL);
	$order_by=filter_input(INPUT_POST, 'orderby', FILTER_SANITIZE_STRING);
	$order_dir=filter_input(INPUT_POST, 'orderdir', FILTER_SANITIZE_STRING);
	$paged=filter_input(INPUT_POST, 'paged', FILTER_SANITIZE_NUMBER_INT);
	$tableSearch=get_transient("tableSearchHeader");
	if(empty($tableSearch)) $tableSearch=new nbm_searchTable(NBS_RESPERPAGE);
	
	if(!empty($search_url)) $sqlBlogSearchFilter.=" AND REPLACE(_blogs.domain, '$tld_url', '') LIKE '%$search_url%'";
	else $sqlBlogSearchFilter="";
	
	// Fetch cache (on transient db object) if exists
	if(NBM_CACHE){
		$cache_key=md5($networkBlogManager_optionKey.$search_url.$search_mail.$order_by.$order_dir.$paged);
		if(class_exists('nbm_cache')){
			$nbm_cache=new nbm_cache();
			$nbm_cached_searchFilter_output=$nbm_cache->get($cache_key);
		}else $nbm_cached_searchFilter_output=get_transient($cache_key);
		if(!empty($nbm_cached_searchFilter_output)){
			$time_end=microtime(true);
			$cached_output="
				<tr>
					<td colspan=\"".$tableSearch->get_columnCount()."\" id=\"cachemsg\">".
					sprintf(__("Cached output in %s ms"),round(1000*($time_end-$time_start),3))."
					<small>[<a onclick=\"emptyCache('$cache_key');\">".__("Empty Cache")."</a>]</small>
					</td>
				</tr>";
			die($nbm_cached_searchFilter_output.$cached_output);
		}
	}
	
	// Fetch blogs
	$blogList="";
	$sqlBlogSearch="SELECT blog_id AS ID, registered AS nbm_regdate FROM $wpdb->blogs AS _blogs WHERE TRUE $sqlBlogSearchFilter";
	$resBlogs=$wpdb->get_results($sqlBlogSearch); 
	foreach($resBlogs as $blog) {
		$res=array();
		// Filter out by Agency Mail
		$res['nbm_email']=get_blog_option($blog->ID, 'admin_email');
		if(!empty($search_mail) AND FALSE===stripos($res['nbm_email'],(string)$search_mail)) continue;
		$res['blog_id']=$blog->ID;
		$res['nbm_domain']=get_blog_option($blog->ID, 'siteurl');
		$res['nbm_regdate']=$blog->nbm_regdate;
		$res['nbm_numposts']=(int)nbm_getBlogContentCount($blog->ID, 'post');
		$res['nbm_numpages']=(int)nbm_getBlogContentCount($blog->ID, 'page');
		$res['nbm_numcomments']=(int)nbm_getBlogContentCount($blog->ID, 'comment');
		$res['nbm_numcontents']=$res['nbm_numposts']+$res['nbm_numpages']+$res['nbm_numcomments'];
		switch($order_by){
			// If does not exists an order or order_by is blog_id
			case "":
			case "blog_id":
				$result[$res['blog_id']]=$res;
				break;
			// If order_by is a date field
			case "nbm_regdate":
				$result[strtotime($res['nbm_regdate'])."_".$res['blog_id']]=$res;
				break;
			// Else, order data by requested criteria
			default:
				$result[urlencode($res[$order_by])."_".$res['blog_id']]=$res;
		} 
	}
	if(!empty($order_by)){
		switch($order_by){
			case "blog_id":
			case "nbm_regdate":
			case "nbm_numposts":
			case "nbm_numpages":
			case "nbm_numcomments":
			case "nbm_numcontents":
				$order_type=SORT_NUMERIC;break;
			default:
				$order_type=SORT_STRING;break;
		}
		if(empty($order_dir)) $order_dir="asc";
		switch($order_dir){
			case "asc":if(!empty($result)) ksort($result, $order_type);break;
			case "desc":if(!empty($result)) krsort($result, $order_type);break;
		}
	}
	$tableResults="";$formPagination="";
	
	// Set the current url
	$url="/wp-admin/index.php?page=$networkBlogManager_optionKey&sage_u=$search_url&sage_m=$search_mail";

	// Pagination
	$pagination=FALSE;
	if(!empty($result)){
		// Slice the result array
		$numResults=count($result);
		if($numResults>NBS_RESPERPAGE){
			$pagination=TRUE;
			$pages=ceil($numResults/NBS_RESPERPAGE);
			$currPage=(int)!empty($paged)?$paged:1;
			$minRes=($currPage-1)*NBS_RESPERPAGE;
			$maxRes=($currPage<$pages)?$minRes+NBS_RESPERPAGE:$numResults;
			$result=array_slice($result, $minRes, NBS_RESPERPAGE);
		}
	}
	
	if($pagination){	
		$formPagination.="
			<tr class=\"tablenav-pages\">
				<td colspan=\"".($tableSearch->get_columnCount()-1)."\" class=\"summary\">";
		$formPagination.=sprintf(__('Results %1$s-%2$s of %3$s',$networkBlogManager_optionKey), $minRes+1, $maxRes, $numResults);
		$formPagination.="
				</td>
				<td>";
		// Link to first page
		if($currPage>1) $formPagination.="<a class=\"page-numbers\" href=\"$url&paged=1\">&laquo;</a>";
		// Digg-like dots for page navigation for far before pages
		if($currPage>NBS_PAGENAVRANGE+1){
			$startPage=$currPage-NBS_PAGENAVRANGE;
			$formPagination.="<span class=\"page-numbers dots\">...</span>";
		}else $startPage=1;
		// Link to previous pages
		for($i=$startPage;$i<$currPage;$i++) $formPagination.="<a class=\"page-numbers\" href=\"$url&paged=$i\">$i</a>";
		// Current page
		$formPagination.="<span class=\"page-numbers current\">$currPage</span>";
		// Link to next pages
		if(($pages-$currPage)>NBS_PAGENAVRANGE) $endPage=$currPage+NBS_PAGENAVRANGE;
		else $endPage=$pages;
		for($i=$currPage+1;$i<=$endPage;$i++) $formPagination.="<a class=\"page-numbers\" href=\"$url&paged=$i\">$i</a>";						
		// Digg-like dots for page navigation for far after pages
		if(($pages-$currPage)>NBS_PAGENAVRANGE) $formPagination.="<span class=\"page-numbers dots\">...</span>";
		// Link to last page
		if($currPage<$pages) $formPagination.="<a class=\"page-numbers\" href=\"$url&paged=$pages\">&raquo;</a>";
		$formPagination.="
				</td>
			</tr>";
	}
	// Draw result's table
	if(empty($result)){
		$tableResults.="
			<tr>
				<td colspan=\"$colcount\">".__("Sorry, no blog matched your criteria.",$networkBlogManager_optionKey)."</td>
			</tr>";
	}else{
		$searchRow=0;
		foreach($result as $blog) {
			$searchRow++;
			$sitename=$blog['nbm_domain'];
			$sitename=str_replace("http://","", $sitename);
			$url ="<a href=\"".$blog['nbm_domain']."\">"."<span id=\"domain_".$blog['blog_id']."\">".$sitename."</span>"."</a> ";
			$url ="<span id=\"blockDomain_".$blog['blog_id']."\">".$url."</span>";
			$urlAdmin="<a href=\"".$blog['nbm_domain']."/wp-admin/ms-sites.php?action=editblog&id=".$blog['blog_id']."\">"."<img src=\"".$nbm_directory."img/settings12.png"."\" width=\"12px\" height=\"12px\" />"."</a>";
			$modButton1 ="<span id=\"modDomain_".$blog['blog_id']."\">";
			$modButton1.="<a onclick=\"editDomain(".$blog['blog_id']."); return false;\">";
			$modButton1.="<img class=\"clickLink\" src=\"".$nbm_directory."img/mod12.png"."\" width=\"12px\" height=\"12px\" />";
			$modButton1.="</a>";
			$modButton1.="</span> ";
			$modButton2 ="<span id=\"confirmDomain_".$blog['blog_id']."\">";
			$modButton2.="<a class=\"edit-slug button hide-if-no-js\" onclick=\"confirmDomain(".$blog['blog_id']."); return false;\">".__("Ok",$networkBlogManager_optionKey)."</a>";
			$modButton2.="</span> ";
			$numposts ="<a id=\"numposts_".$blog['blog_id']."\" href=\"".$blog['nbm_domain']."/wp-admin/edit.php\" class=\"clickLink\" >".$blog['nbm_numposts']."</a>";
			$numpages ="<a id=\"numpages_".$blog['blog_id']."\" href=\"".$blog['nbm_domain']."/wp-admin/edit.php?post_type=page\" class=\"clickLink\" >".$blog['nbm_numpages']."</a>";
			$numcomments ="<a id=\"numcomments_".$blog['blog_id']."\" href=\"".$blog['nbm_domain']."/wp-admin/edit-comments.php\" class=\"clickLink\" >".$blog['nbm_numcomments']."</a>";
			$numcontents ="&nbsp;$numposts&nbsp;<img src=\"".$nbm_directory."img/posts12.png"."\" width=\"12px\" height=\"12px\" />";
			$numcontents.="&nbsp;$numpages&nbsp;<img src=\"".$nbm_directory."img/pages12.png"."\" width=\"12px\" height=\"12px\" />";
			$numcontents.="&nbsp;$numcomments&nbsp;<img src=\"".$nbm_directory."img/comments12.png"."\" width=\"12px\" height=\"12px\" />";
			$datetimeformat=get_option('date_format');//.' - '.get_option('time_format');
			$mailLink="<a href='mailto:".$blog['nbm_email']."' title=\"".$blog['nbm_email']."\"><img src=\"".$nbm_directory."img/email12.png"."\" alt=\"".$blog['nbm_email']."\" /></a>";
			$tableSearch->add_element($searchRow,"blog_id",$blog['blog_id']);
			$tableSearch->add_element($searchRow,"nbm_domain",$urlAdmin."&nbsp;".$mailLink."&nbsp;".$modButton1."<span style=\"display:none;\">".$modButton2."</span>".$url);
#			$tableSearch->add_element($searchRow,"nbm_email",$mailLink);
			$tableSearch->add_element($searchRow,"nbm_regdate",mysql2date($datetimeformat,$blog['nbm_regdate']));
			$tableSearch->add_element($searchRow,"nbm_numcontents",$numcontents);
		}
		set_transient("tableSearchBody",$tableSearch);
		do_action('tableSearch_add_body');
		$tableSearch=get_transient("tableSearchBody");
		for($i=1;$i<=$searchRow;$i++){
			$tableResults.="<tr>".$tableSearch->get_body($i)."</tr>";
		}
		set_transient("tableSearchBody",$tableSearch);
	}
	$output=$tableResults.$formPagination;

	// Set in cache
	if(NBM_CACHE){
		if(class_exists('nbm_cache')) $output=$nbm_cache->set($cache_key, $output);
		else set_transient($cache_key, $output, 3600);
	}

	// Output and return
	$time_end=microtime(true);
	$uncached_output="<tr><td colspan=\"".$tableSearch->get_columnCount()."\" id=\"cachemsg\">".sprintf(__("Uncached output in %s ms"),round(1000*($time_end-$time_start),3))."</td></tr>";
	die($output.$uncached_output);	
}#end nbm_searchFilter_action_callback
###############################################################################
function nbm_confirmDomain_action_callback() {
	global $wpdb, $table_prefix, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE); 
	$signal=FALSE;
	$dominio_id=filter_input(INPUT_POST, 'dominio_id', FILTER_VALIDATE_INT);
	$dominio_url=filter_input(INPUT_POST, 'dominio_url', FILTER_SANITIZE_URL);
	if(!empty($dominio_url)){
		$dominio_http="http://$dominio_url";
		update_blog_option($dominio_id, 'home', $dominio_http);
		update_blog_option($dominio_id, 'siteurl', $dominio_http);
		update_blog_option($dominio_id, 'fileupload_url', $dominio_http.'/'."files");
		update_blog_details($dominio_id, array(
			'domain'=>$dominio_url
		,	'siteurl'=>$dominio_http
		));
		$signal=TRUE;
	}
	echo $signal;
	die();
}#end nbm_confirmDomain_action_callback
###############################################################################
function nbm_emptyCache_action_callback(){
	global $wpdb, $table_prefix, $networkBlogManager_optionKey;
	$nonce=filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_ENCODED);
	if(!wp_verify_nonce($nonce, $networkBlogManager_optionKey) ) die(FALSE);
	$cache_key=filter_input(INPUT_POST, 'cache_key', FILTER_SANITIZE_ENCODED);
	if(class_exists('nbm_cache')){
		$nbm_cache=new nbm_cache();
		return $nbm_cache->del($cache_key);
	}else return delete_transient($cache_key);
}#end nbm_emptyCache_action_callback
###############################################################################
function networkBlogManager_JS(){
	global $tld_url, $networkBlogManager_optionKey, $tableSearch;
	$paged=filter_input(INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT);
	$currPage=(int)!empty($paged)?$paged:1;
	$jsScript="
<script type=\"text/javascript\">

function changeOrder(newOrderBy){
	jQuery(document).ready(function($) {
		oldOrderBy=$('#orderby').val();
		oldOrderDir=$('#orderdir').val();
		if(newOrderBy==oldOrderBy){
			if('desc'==oldOrderDir){
				$('#orderdir').val('asc');
				$('#arrow_'+oldOrderBy).html('');
				$('#arrow_'+newOrderBy).html('&darr;');
			}else{
				$('#orderdir').val('desc');
				$('#arrow_'+oldOrderBy).html('');
				$('#arrow_'+newOrderBy).html('&uarr;');
			}
		}else{
			$('#orderby').val(newOrderBy);
			$('#orderdir').val('asc');
			$('#arrow_'+oldOrderBy).html('');
			$('#arrow_'+newOrderBy).html('&darr;');
		}
		searchFilter();
	});
}//end changeOrder

function searchFilter(){
	jQuery(document).ready(function($) {
		var data = {
			action: 'searchfilter'
		,	sage_u: $('#sage_u').val()
		,	sage_m: $('#sage_m').val()
		,	orderby: $('#orderby').val()
		,	orderdir: $('#orderdir').val()
		,	paged: $currPage
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#tableResults').html(response);
		});
	});
}//end searchFilter

function editDomain(dom_id){
	var dominioStandard=\"$tld_url\";
	var dominio_slot=document.getElementById(\"domain_\"+dom_id);
	var dominio_slotBlock=document.getElementById(\"blockDomain_\"+dom_id);
	var dominio=dominio_slot.innerHTML;
	dominio=dominio.replace(dominioStandard,\"\");
	var newText='<input type=\"text\" id=\"changeDomain_'+dom_id+'\" name=\"changeDomain_'+dom_id+'\" value=\"'+dominio+'\">'+dominioStandard;
	dominio_slotBlock.innerHTML=newText;
	var button1_slot=document.getElementById(\"modDomain_\"+dom_id);
	var button2_slot=document.getElementById(\"confirmDomain_\"+dom_id);
	button_temp=button1_slot.innerHTML;
	button1_slot.innerHTML=button2_slot.innerHTML;
	button2_slot.innerHTML=button_temp;
}//end editDomain

function confirmDomain(dom_id){
	var dominioStandard=\"$tld_url\";
	var dominioConf_slot=document.getElementById(\"changeDomain_\"+dom_id);
	var dominioConf=dominioConf_slot.value+dominioStandard;
	var button1_slot=document.getElementById(\"modDomain_\"+dom_id);
	var button2_slot=document.getElementById(\"confirmDomain_\"+dom_id);	
	jQuery(document).ready(function($) {
		var data = {
			action: 'confirmdomain'
		,	dominio_id: dom_id
		,	dominio_url: dominioConf
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
		};
		jQuery.post(ajaxurl, data, function(response) {
			if(response!=1){
				$('#message').show();
				$('#message').html(\"".__("Sorry, an error has occurred.",$networkBlogManager_optionKey)."\");
			}else{
				$('#message').show();
				$('#message').html(\"".__("Domain modified correctly.",$networkBlogManager_optionKey)."\");
			}
		});
		button_temp=button1_slot.innerHTML;
		button1_slot.innerHTML=button2_slot.innerHTML;
		button2_slot.innerHTML=button_temp;
		dominio_slotBlock=document.getElementById(\"blockDomain_\"+dom_id);
		dominio_slotBlock.innerHTML='<a href=\"http://'+dominioConf+'\"><span id=\"domain_'+dom_id+'\">'+dominioConf+'</span></a>'; 
	});
}//end confirmDomain

function emptyCache(cache_key){
	jQuery(document).ready(function($) {
		var data = {
			action: 'emptycache'
		,	cache_key: cache_key
		,	nonce: '".wp_create_nonce($networkBlogManager_optionKey)."'
	};
	jQuery.post(ajaxurl, data, function(response) {
			$('#cachemsg').html(\"".__("Current Cache Cleaned!")."\");
		});
	});
}//end emptyCache

jQuery(document).ready(function($) {
	searchFilter();
	$('tableResults').show();
});
</script>";
	echo $jsScript;
}#end networkBlogManager_JS
###############################################################################
function nbm_getBlogContentCount($blogID, $contentType){
	global $wpdb;
	switch($contentType){
		case "post":
		case "page":
			$sqlCounter="SELECT COUNT(ID) AS num FROM {$wpdb->get_blog_prefix($blogID)}posts WHERE post_type='$contentType' AND post_status='publish'";
			break;
		case "comment":
			$sqlCounter="SELECT COUNT(comment_ID) AS num FROM {$wpdb->get_blog_prefix($blogID)}comments WHERE comment_approved='1' AND comment_type=''";
			break;			
	}
	return $wpdb->get_var($sqlCounter);
}#end get_blog_contentCount
###############################################################################
function nbm_getAPIKey(){
	global $networkBlogManager_optionKey;
	$apiKEYName='nbm_APIKey';
	$apiKEY=get_site_option($apiKEYName);
	if(empty($apiKEY)){
		$apiKEY=md5(AUTH_KEY.'_'.$networkBlogManager_optionKey.'_'.$apiKEYName);
		update_site_option($apiKEYName, $apiKEY); 
	}
	return $apiKEY;
}#end nbm_getAPIKey
###############################################################################
if(!function_exists('nbm_mtime')){
	function nbm_mtime(){
	    list($usec, $sec) = explode(" ", microtime());
	    return 1000.00*((float)$usec + (float)$sec);
	}
}#end nbm_mtime
###############################################################################
function get_rootSite(){
	if(defined('DOMAIN_CURRENT_SITE')) return DOMAIN_CURRENT_SITE;
	else return get_site_url();
}#end get_rootSite
###############################################################################
?>