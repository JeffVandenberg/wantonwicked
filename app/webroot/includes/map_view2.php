<?
$page_title = "Wanton Wicked District Map for The City";

$extra_headers = <<<EOQ
    <style type="text/css">
        v\:* {behavior:url(#default#VML); }
      	html, body { width: 99%; height: 95% }
      	body { margin-top: 3px; margin-right: 0px; margin-left: 3px; margin-bottom: 0px }
    </style>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAmGTfcjlUIeXs4aoEIragyxT59gojkbh5st5ZsHLr70RjPARmzBT7L3rhZrOG_BQy3b5PoUuCKuvZAg"
      type="text/javascript"></script>
    <script src="js/gpolygon.gen.js" type="text/javascript"></script>
    <script src="js/map.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="http://www.wantonwicked.net/wicked_map.css">
EOQ;

$territory_subtype_select = <<<EOQ
<select name="feature_subtype_select" id="feature_subtype_select" class="textinput1">
  <option value="District" selected>District</option>
  <option value="Vampire Domain">Vampire Domain</option>
  <option value="Werewolf Territory">Werewolf Territory</option>
  <option value="Mage Territory">Mage Territory</option>
</select>
EOQ;

$code = <<<EOQ
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <body onload="load();" onunload="GUnload();">
    <div class="title">District Map for The City</div>
    <div id="map1" class="map"></div>
    <div id="info_request" class="overlay">
    <table id="info_request_table" class="infotable">
      <tr>
        <td class="information">
          Fill in information for the territory
        </td>
      </tr>
      <tr>
        <td class="territoryinputfield">
          Name: <input type="text" name="feature_name" id="feature_name" class="textinput1" value="">
        </td>
      </tr>
      <tr>
        <td class="territoryinputfield">
          Type: <span id="feature_type"></span>
        </td>
      </tr>
      <tr>
        <td class="territoryinputfield">
          Subtype: <span id="feature_subtype"></span> $territory_subtype_select 
        </td>
      </tr>
      <tr>
        <td class="territoryinputfield">
          <span id="feature_color_text">Color (#rrggbb) or (color name): <input type="text" name="feature_color" id="feature_color" size="10" maxlength="20" class="textinput1"></span>
        </td>
      </tr>
      <tr>
        <td class="territoryinputfield">
          HTML: <textarea name="feature_html" id="feature_html" rows="5" cols="30"></textarea>
        </td>
      </tr>
      <tr>
        <td class="territoryinputfield">
          <input type="hidden" name="feature_id" id="feature_id" value="0">
          <input type="button" value="Finished" onClick="saveFeatureToServer();">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="button" value="Cancel" onClick="cancelFeature()">
        </td>
      </tr>
    </table>
    </div>
    <table class="icontable">
      <tr>
        <th colspan="2">
          Markers
        </th>
      </tr> 
      <tr>
        <td colspan="2" class="icon">
          <img src="img/territory.gif" title="Territory" onClick="setFeatureType('Territory');">
        </td>
      </tr>
      <tr>
        <td class="icon">
          <img src="img/marker.gif" title="Church" onClick="setFeatureType('Church');">
        </td>
        <td class="icon">
          <img src="img/marker.gif" title="Elysium" onClick="setFeatureType('Elysium');">
        </td>
      </tr>
      <tr>
        <td class="icon">
          <img src="img/marker.gif" title="Club" onClick="setFeatureType('Club');">
        </td>
        <td class="icon">
          <img src="img/marker.gif" title="Graveyard" onClick="setFeatureType('Graveyard');">
        </td>
      </tr>
    </table>  
    <form>
      <select name="mapMode" id="mapMode" class="modeselect" onchange="updateStatus();">
        <option value="View">View Feature</option>
        <option value="Add">Add Feature</option>
        <option value="Remove">Remove Feature</option>
        <option value="Modify">Modify Feature</option>
      </select>
      <input type="button" id='buttonRecord' value="Record Points" onclick="toggleRecord();" class="submitbutton" style="visibility:hidden;">
      <input type="text" id="information" name="information" value="" class="information">
    </form>
    
  <b1><!--Pngtw_FUeNoNyzEOgCAMAMAfUQFj4z/cDUKjRClEq/2+TDcdFBZYwr2TDBa8txN2xtkhgqoaDSyVNceTkmESUNogVhbqL3O83kQPlNDWL5M60472A3UDHNo=--></b1></body>
</html>
EOQ;

echo $code;
?>