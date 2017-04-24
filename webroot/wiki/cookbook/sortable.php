<?php if (!defined('PmWiki')) exit();
/*  
+----------------------------------------------------------------------+
| sortable.php for PmWiki.  
| Copyright 2008 Hans Bracker.
| This program is free software; you can redistribute it and/or modify
| it under the terms of the GNU General Public License, Version 2, as
| published by the Free Software Foundation.
| http://www.gnu.org/copyleft/gpl.html
| This program is distributed in the hope that it will be useful,
| but WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
| GNU General Public License for more details.
| Version History:
| 2009-07-20 - modified sortable.js to replace 'td' tags with 'th' in first (head) row [Rik Blok]
+----------------------------------------------------------------------+
| This script adds javascript table sorting
| Use markup (:sortable:) to load javascript and table styles.
| Javascript and table styles will only be loaded for page which holds this markup.
| Use optional parameters for assigning background colors (see defaults below).
| Use simple table markup. Add class=sortable id=mytableid to table markup (top).
| Use !column heading to create table header cells.
| Use style markup %colnosort% inside header row cell, but without the ! for column heading,
| to create column heading which can't be clicked and sorted. Evtl. use '''....''' markup
| to emphasise heading text as it appears in column heading cells.
| Use style markup %rownosort% inside bottom row cell 
| Use style markup %rownosort% inside bottom row cell 
| to create bottom row which will remain in bottom position, i.e. not get sorted.
| Other parameters: see $defaults below.  
| Install: Copy sortable.php (this file) to cookbook/ folder and add to config.php:
| include_once("$FarmD/cookbook/sortable.php");
| copy all other files to pub/sortable/ (create a new folder 'sortable' in pub/).
+----------------------------------------------------------------------+
*/
$RecipeInfo['SortableTables']['Version'] = '2015-06-12';

if(function_exists('Markup_e')) { 
	Markup_e('sortable', 'directives',
  	'/\\(:sortable\\s*(.*?)\\s*:\\)/',
  	"LoadSortable(\$pagename, \$m[1])");
}
else { 
	Markup('sortable', 'directives',
  	'/\\(:sortable\\s*(.*?)\\s*:\\)/e',
  	"LoadSortable(\$pagename, PSS('$1'))");
}
  
function LoadSortable($pagenam, $args) {
	global $PubDirUrl, $HTMLHeaderFmt;
	$defaults = array(
		'date' => 'eu',
		'oddrowbg'    => '#f3f3f3',
		'evenrowbg'   => '#e8e8e8',
		'headerrowbg' => '#bbb',
		'bottomrowbg' => '#d6d6d6',
		'negnumcolor' => 'red',
		'arrows' => 'white',
		'headerrow'   => '#fff',  //header row font color
		'headerrowhover' => '#333', //header row font hover colour
		'altrowbg' => 1,
	);
	$args = ParseArgs($args);
	$args = array_merge($defaults, $args);
	if (isset($args[''][0])) $args['date'] = $args[''][0];
	if ($args['date'] == 'eu') $EUDate = '1';
	elseif ($args['date'] == 'us') $EUDate = '0';
	if ($args['altrowbg'] == '0') $AltRow = '0';
	else $AltRow = '1';
	if ($args['arrows'] == 'white') {
		$ArrowUp = 'up-white.gif';
		$ArrowDown = 'down-white.gif';		
	}	
	else if ($args['arrows'] == 'gray' || $args['arrows'] == 'grey') {
		$ArrowUp = 'up-gray.gif';
		$ArrowDown = 'down-gray.gif';
	} else if ($args['arrows'] == 'black') {
		$ArrowUp = 'up-black.gif';
		$ArrowDown = 'down-black.gif';		
	}
	
	$HTMLHeaderFmt['sortable'] =  '
		<script type="text/javascript" language="JavaScript1.2">
			var image_path = "$PubDirUrl/sortable/";
			var image_up = "'.$ArrowUp.'";
			var image_down = "'.$ArrowDown.'";
			var image_none = "none.gif";
			var europeandate = '.$EUDate.';
			var alternate_row_colors = '.$AltRow.'; 
		</script>
		<script type="text/javascript" language="JavaScript1.2" src="$PubDirUrl/sortable/sortable.js"></script>
		<style type="text/css">
			.sortable * {padding:0 0.3em;}
			.even {background:'.$args["evenrowbg"].';}
			.odd  {background:'.$args["oddrowbg"].';}
			.sortable th, td.unsortable {background:'.$args["headerrowbg"].';}
			.sortable th a, td.unsortable {color:'.$args["headerrow"].'; text-decoration:none;}
			.sortable th a:hover {color:'.$args["headerrowhover"].';}
			.sortable td.unsortable {vertical-align: bottom;}
			.sortbottom {background:'.$args["bottomrowbg"].';}
			.sortable td.negnum {color:'.$args["negnumcolor"].';}
		</style>';
}

$WikiStyle['colnosort']['class'] = 'unsortable';
$WikiStyle['colnosort']['apply'] = 'td';
$WikiStyleApply['td'] = 'td';

$WikiStyle['rownosort']['class'] = 'sortbottom';
$WikiStyle['rownosort']['apply'] = 'tr';
$WikiStyleApply['tr'] = 'tr';
