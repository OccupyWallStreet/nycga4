<?php
/**
 * Class Name: Network Blog Manager - Cache Manager
 * Description: Simple Cache Manager for Network Blog Manager
 * Version: 0.10
 * Author: Carlo Gandolfo
 * Author URI: mailto:carlo@artilibere.com
 * Licence: GPL2
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
class nbm_cache{
	/**
	 * Constructor
	 */
	public function nbm_cache(){}
	/**
	 * Getter
	 * @param string $key A unique identifier for your cached data
	 * @param string $group Where the cache contents are grouped
	 */
	###########################################################################
	public function get($key, $group="nbm_cache"){
		return get_transient("$key@$group");
	}#end get
	/**
	 * Setter
	 * @param string $key A unique identifier for your cached data
	 * @param object $data Data to cache
	 * @param int $interval How long to cache data
	 * @param string $group Where the cache contents are grouped
	 */
	###########################################################################
	public function set($key, $data, $interval=3600, $group="nbm_cache"){
		set_transient("$key@$group", $data, $interval);
		return $data;
	}#end set
	/**
	 * Cache cleaner
	 * @param string $key A unique identifier for your cached data
	 * @param string $group Where the cache contents are grouped
	 */
	###########################################################################
	public function del($key, $group="nbm_cache"){
		return delete_transient("$key@$group");
	}#end del
}#end class nbm_cache
?>