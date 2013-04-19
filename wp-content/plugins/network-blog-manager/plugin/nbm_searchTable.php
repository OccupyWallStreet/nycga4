<?php
/**
 * Class Name: Network Blog Manager - Search Table Builder
 * Description: Search Table Builder for Network Blog Manager
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
class nbm_searchTable{
	
	private $thead;
	private $tbody;
	private $rowCount;
	private $columnCount;
	
	/**
	 * Constructor
	/**/
	public function nbm_searchTable($rows){
		$this->thead=array();
		$this->tbody=array();
		$this->rowCount=$rows;
	}#end nbm_searchTable
	###########################################################################
	/**
	 * Add an element to the header table
	 * @param string $id Unique identifier of the column
	 * @param string $label Column name
	 * @param int $order Column order
	/**/
	public function add_header($id, $label, $order=0){
		$this->thead[$order][$id]=$label;
		$this->columnCount++;
	}#end add_element
	###########################################################################
	/**
	 * Add an element to the body table
	 * @param int $row Line number
	 * @param string $id Unique identifier of the column
	 * @param string $data Data to add to the table
	/**/
	public function add_element($row, $id, $data){
		$this->tbody[$row][$id]=$data;	
	}#end add_element
	###########################################################################
	/**
	 * Retrieve an element from the body table
	 * @param int $row Line number
	 * @param string $id Unique identifier of the column
	 */
	public function get_element($row, $id){
		return $this->tbody[$row][$id];
	}#end get_element
	###########################################################################
	/**
	 * Draw the table header
	/**/
	public function get_header(){
		$header="";
		ksort($this->thead);
		foreach($this->thead as $order => $headerData){
			foreach($headerData as $id => $label){
				$header.="<th><a class=\"clickLink\" id=\"ordby_$id\" onclick=\"changeOrder('$id');return false;\">$label</a><span id=\"arrow_$id\"></span></th>";
			}
		}
		return $header;
	}#end get_header
	###########################################################################
	/**
 	* Draw the table body
 	* @param int $row Line number
 	/**/
	public function get_body($row){
		$body="";
		ksort($this->thead);
		foreach($this->thead as $order => $headerData){
			foreach($headerData as $id => $label){
				$data=$this->tbody[$row][$id];
				$body.="<td>$data</td>";
			}
		}
		return $body;
	}#end get_header
	###########################################################################
	/**
	 * Get number of table columns
	/**/
	public function set_columnCount($columns){
		$this->columnCount=$columns;
	}#end get_columnCount
	###########################################################################
	/**
	 * Get number of table rows
	/**/
	public function set_rowCount($rows){
		$this->rowCount=$rows;
	}#end get_rowCount
	###########################################################################
	/**
	 * Get number of table columns
	/**/
	public function get_columnCount(){
		return $this->columnCount;
	}#end get_columnCount
	###########################################################################
	/**
	 * Get number of table rows
	/**/
	public function get_rowCount(){
		return $this->rowCount;
	}#end get_rowCount
	###########################################################################
}#end class nbm_searchTable
