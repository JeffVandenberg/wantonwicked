<?php if (!defined('PmWiki')) exit();
/*
+----------------------------------------------------------------------+
| Copyright 2010 Ville Takanen
| This program is free software; you can redistribute it and/or modify
| it under the terms of the GNU General Public License, Version 2, as
| published by the Free Software Foundation.
| http://www.gnu.org/copyleft/gpl.html
| This program is distributed in the hope that it will be useful,
| but WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
| GNU General Public License for more details.
+----------------------------------------------------------------------+
*/
$RecipeInfo['FaceBookLikeButton']['Version'] = '2010-07-12';

/*
 *Add AJAX components to the page
 */
$HTMLFooterFmt['facebook_JavaScript_SDK'] ='<div id="fb-root"></div><script>window.fbAsyncInit = function() {FB.init({appId: "170157659759565", status: true, cookie: true,xfbml: true});};(function() {var e = document.createElement("script"); e.async = true;e.src = document.location.protocol +"//connect.facebook.net/en_US/all.js";document.getElementById("fb-root").appendChild(e);}());</script>';

/*
 *Add the like markup to PmWiki markup dictionary.
 */
Markup('fblike', 'directives',  '/\\(:fblike(.*?):\\)/e', 'DspFBLike("$pagename", "$1")');


/**
 * Create a fbxml's "like" tag from wiki3.0 directive
 *
 * Empty tag creates "like"-tag for this page
 *
 * name=pagename creates "like"-tag for wikipage [group(.|/)]title
 */
function DspFBLike($pn, $opts) {
  
  global $ScriptUrl;
  $args = ParseArgs($opts);
  
  if (empty($args['name'])){
	//no group or page title given -> generate default tag
    return '<fb:like show_faces="false" width="620" font="arial"></fb:like>';
  }
  else {
	//Just name given -> make pagename from the title
	$fbhref=$ScriptUrl."?name=".MakePageName($pn ,$args['name']);
	return Keep('<fb:like href="'.$fbhref.'" show_faces="false" width="620" font="arial"></fb:like>');
  }
}



