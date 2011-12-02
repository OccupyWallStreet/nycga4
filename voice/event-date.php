<?php

include("/var/www/nycga.net/web/env.php");


  // Get the search variable from URL

  $var = @$_GET['date'] ;
  $trimmed = trim($var); //trim whitespace from the stored variable

// rows to return
$limit=200; 


//connect to your database ** EDIT REQUIRED HERE **
mysql_connect(constant("DB_HOST"),constant("DB_USER"),constant("DB_PASSWORD")); //(host, username, password)

//specify database ** EDIT REQUIRED HERE **
mysql_select_db(constant("DB_NAME")) or die("Unable to select database"); //select which database we're using

// Build SQL Query  
$query = "SELECT wp_em_events.event_name, DATE_FORMAT(wp_em_events.event_start_time,'%l:%i%p') as StartTime, DATE_FORMAT(wp_em_events.event_end_time,'%l:%i%p') as EndTime, DATE_FORMAT(wp_em_events.event_start_date,'%W %M %D') as StartDate, wp_em_events.location_id, wp_em_locations.location_name as LocationName, wp_em_locations.location_address as LocationAddress, wp_em_events.group_id, wp_bp_groups.name as GroupName, wp_em_categories.category_name as CategoryName " .
"FROM wp_em_events LEFT JOIN wp_em_locations ON wp_em_events.location_id = wp_em_locations.location_id " .
"LEFT JOIN wp_bp_groups ON wp_em_events.group_id = wp_bp_groups.id " .
"LEFT JOIN wp_em_categories ON wp_em_events.event_category_id = wp_em_categories.category_name " .
"WHERE event_start_date like '$var' order by event_start_time, GroupName";


 $numresults=mysql_query($query);
 $numrows=mysql_num_rows($numresults);

// If we have no results, offer a google search as an alternative


// next determine if s has been passed to script, if not use 0
  if (empty($s)) {
  $s=0;
  }

// get results
  $query .= " limit $s,$limit";
  $result = mysql_query($query) or die("Couldn't execute query");
  $timenow = date("h:i A");
  $datesel = date('l F jS');
  $myDate = date('l F jS', $var );
// display what the person searched for
echo "There are $numrows events for $myDate . To list events by group, say . group . ";

// begin to show results set

$count = 1 + $s ;

// now you can display the results returned
  while ($row= mysql_fetch_array($result)) {
  $eventname = $row["event_name"];
  $startime = $row["StartTime"];
  $endtime = $row["EndTime"];
  $startdate = $row["StartDate"];
  $locationname = $row["LocationName"];
  $locationaddress = $row["LocationAddress"];
  $groupname = $row["GroupName"];
  

  $output .= "$count.  $groupname, $eventname.  Time. $startime . to . $endtime  .  Location. $locationname . at . $locationaddress.    " ;
  $count++ ;
  
  
  
  }
  
    $badchars = array(">", "<", "&amp;", "/", "&", "\\","�", "�");
  $replacechars = array(" and ", " ", " and ", " and ", " and ", " and ", "o", "e");
  $cleantext = str_replace($badchars, $replacechars, $output);
  
  
  echo $cleantext;
  

$currPage = (($s/$limit) + 1);


// calculate number of pages needing links
  $pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division

  if ($numrows%$limit) {
  // has remainder so add one page
  $pages++;
  }


$a = $s + ($limit) ;
  if ($a > $numrows) { $a = $numrows ; }
  $b = $s + 1 ;
 
  
?>