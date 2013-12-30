<?
Header("Location:http://www.wantonwicked.net/map.php?action=view");
die();

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
  <head>
    <style type="text/css">
        v\:* {behavior:url(#default#VML); }
      	html, body { width: 99%; height: 95% }
      	body { margin-top: 3px; margin-right: 0px; margin-left: 3px; margin-bottom: 0px }
    </style>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAmGTfcjlUIeXs4aoEIragyxT59gojkbh5st5ZsHLr70RjPARmzBT7L3rhZrOG_BQy3b5PoUuCKuvZAg"
      type="text/javascript"></script>
    <script src="js/gpolygon.gen.js" type="text/javascript"></script>
    <title>Wanton Wicked District Map for The City</title>
    <style>
div.overlay
{
  position:absolute;
  left:3px;
  top:66px;
  z-index:1;
  visibility:hidden;
  background:#ffffff;
  width:600px;
  height:600px;
}

div.map
{
  position:absolute;
  left:3px;
  top:66px;
  width: 600px;
  height: 600px;
}

div.title
{
  position:absolute;
  left:3px;
  top:3px;
  font-size: 25px;
  font-weight: 600;
}

select.modeselect
{
  position:absolute;
  left:3px;
  top:33px;
}

input.submitbutton
{
  position:absolute;
  left:140px;
  top:32px;
}

input.information
{
  position:absolute;
  left:300px;
  top:32px;
  width:250px;
}
table.infotable
{
  position:absolute;
  left:153px;
  top:105px;
  background:#aaaaaa;
  width:300px;
  height:400px;
  border-style: ridge;
  border-color:#3a3333;
  border-width: 3px
}

table.icontable
{
  position:absolute;
  left:633px;
  top:66px;
  background:#ffffff;
  width:150px;
  height:300px;
  padding: 0px;
}

td.icon
{
  border-style: ridge;
  border-color:#443333;
  border-width: 3px;
  width:50%;
  vertical-align: middle;
  text-align: center;
}

td.information
{
  text-align: center;
  font-size: 15px;
}

td.territoryinputfield
{
  text-align: center;
}

.textinput1
{
  border-style: solid;
  border-width: 1px;
  border-color: #0000ff;
}

.featureinformation
{
  font-size: 12px;
  font-family: arial;
}
    </style>
  <script type="text/javascript">

    //<![CDATA[
    
    var siteID = 1004;
    var pointList = null; // array of LatLng for Markers
    var markerList = null; // GMarker array
    var map = null;
    //var overlayList = new Array();
    var featureList = new Array();
    var overlayHoveredOver = -1;
    var featureType = "Territory";
    var tempFeature = null;
    var mapMode = "view";
    
    function load() {
      if (GBrowserIsCompatible()) {
        
        map = new GMap2(document.getElementById("map1"));
        map.setCenter(new GLatLng(42.344335, -83.03054), 11);
        map.addControl(new GSmallMapControl());
        var mapcontrol = new GSmallMapControl();
        
        GEvent.addListener(map, "click", function(marker, point) {
          switch(mapMode)
          {
            case "view":
              mapView(marker, point);
              break;
            case "add":
              mapAdd(point);
              break;
            case "modify":
              mapModify(marker, point);
              break;
            case "remove":
              mapRemove(marker, point);
              break;
          }
        });
        
        GEvent.addListener(map, "zoomend", function(oldLevel, newLevel) {
          if(newLevel < 8)
          {
            map.setZoom(8);
          }
          if(newLevel > 15)
          {
            map.setZoom(15);
          }
        });
        
        getFeatures();
      }
      else
      {
        document.write("You need a JavaScript enabled Browser");
      }
    }
    
    // make a unique marker    
    function createMarker(point, iconURL) {
      var icon = new GIcon(G_DEFAULT_ICON);
      icon.image = iconURL;
      icon.infoWindowAnchor = new GPoint(4,20);
      var marker = new GMarker(point, icon);
      return marker;
    }
    
    // called during load
    function getFeatures()
    {
      var http_request = getXmlHttpObject();
      if(http_request)
      {
        // build url
        var url = "/map.php?action=list&site_id="+siteID;
        http_request.onreadystatechange = function() { drawFeatures(http_request); };
        http_request.open('GET', url, true);
        http_request.send(null);
      }
    }
    
    // called after finish download
    function drawFeatures(http_request)
    {
      if (http_request.readyState == 4)
      {
        if (http_request.status == 200)
        {
          // get response XML 
          var serverResponse = http_request.responseXML;
          
          // get list of features
          var xmlFeatureList = serverResponse.getElementsByTagName('feature');
          
          var featureID = 0;
          var featureName = "";
          var featureHTML = "";
          var featureType = "";
          var featureSubtype = "";
          var featureColor = "";
          
          // process features
          for(var i = 0; i < xmlFeatureList.length; i++)
          {
            if(xmlFeatureList[i].getElementsByTagName('featureID')[0].childNodes[0])
            {
              featureID = xmlFeatureList[i].getElementsByTagName('featureID')[0].childNodes[0].nodeValue;
            }
            else
            {
              alert('Aborting due to an invalid feature ID');
              break;
            }
            
            if(xmlFeatureList[i].getElementsByTagName('featureName')[0].childNodes[0])
            {
              featureName = xmlFeatureList[i].getElementsByTagName('featureName')[0].childNodes[0].nodeValue;
            }
            else
            {
              featureName = "Unknown Name";
            }
            
            if(xmlFeatureList[i].getElementsByTagName('featureHTML')[0].childNodes[0])
            {
              featureHTML = xmlFeatureList[i].getElementsByTagName('featureHTML')[0].childNodes[0].nodeValue;
            }
            else
            {
              featureHTML = "No Known Description";
            }
            
            if(xmlFeatureList[i].getElementsByTagName('featureType')[0].childNodes[0])
            {
              featureType = xmlFeatureList[i].getElementsByTagName('featureType')[0].childNodes[0].nodeValue;
            }
            else
            {
              alert('Unknown Feature Type. Aborting.');
              break;
            }
            
            if(xmlFeatureList[i].getElementsByTagName('featureSubtype')[0].childNodes[0])
            {
              featureSubtype = xmlFeatureList[i].getElementsByTagName('featureSubtype')[0].childNodes[0].nodeValue;
            }
            else
            {
              featureSubtype = "Unknown Type of Feature";
            }
            
            if(xmlFeatureList[i].getElementsByTagName('featureColor')[0].childNodes[0])
            {
              featureColor = xmlFeatureList[i].getElementsByTagName('featureColor')[0].childNodes[0].nodeValue;
            }
            else
            {
              featureColor = "#660000";
            }
            
            
            var lats = xmlFeatureList[i].getElementsByTagName('lat');
            var lngs = xmlFeatureList[i].getElementsByTagName('lng');
            pointList = new Array();
            markerList = new Array();
            if(featureType == "Territory")
            {
              // create point list
              for(var j = 0; j < lats.length; j++)
              {
                pointList.push(new GLatLng(Number(lats[j].childNodes[0].nodeValue), Number(lngs[j].childNodes[0].nodeValue)));
              }
              
              // create a GPolygon
              drawLines(featureColor);
            }
            else
            {
              // create a GMarker
              pointList.push(new GLatLng(Number(lats[0].childNodes[0].nodeValue), Number(lngs[0].childNodes[0].nodeValue)));
              var alertText = makeOverlayText(featureName, featureSubtype, featureHTML);
              map.addOverlay(createMarker(pointList[0], "http://www.wantonwicked.net/img/location_" + featureSubtype.toLowerCase() + ".png"));
            
              tempFeature = createMarker(pointList[0], "http://www.wantonwicked.net/img/location_" + featureSubtype.toLowerCase() + ".png");
            }
            
            // clear pointlist
            pointList = null;
            markerList = null;
            
            // create feature Object
            var myFeature = new Feature(featureID, featureName, featureHTML, featureType, featureSubtype, tempFeature, featureColor);
            
            featureList.push(myFeature);
            
          }
        }
        else
        {
          alert('There was a problem with the request.');
        }
      }
      
      http_request = null;
      tempFeature = null;
    }
    
    // update internal status
    function updateStatus()
    {
      mapMode = document.getElementById('mapMode').value.toLowerCase();
      document.getElementById('information').value = mapMode;
      switch(mapMode)
      {
        case "add":
          window.document.getElementById('buttonRecord').style.visibility="visible";
          break;
        case "view":
        case "modify":
        case "remove":
          window.document.getElementById('buttonRecord').style.visibility="hidden";
          break;
      }
    }
    
    function makeOverlayText(featureName, featureSubtype, featureHTML)
    {
      return "<span class=\"featureinformation\"><b>Name:</b> " + featureName + "<br><b>Type:</b> " + featureSubtype + "<br>" + featureHTML + "</span>";
    }
    
    function mapView(marker, point)
    {
      if(marker)
      {
        var markerIndex = findMarker(marker)
        if((mapMode == "view") && (markerIndex > -1))
        {
          var alertText = makeOverlayText(featureList[markerIndex].name, featureList[markerIndex].subtype, featureList[markerIndex].html);
          try
          {
            marker.openInfoWindowHtml(alertText);
          }
          catch(e)
          {
            // do nothing
          }
        }
      }
      else
      {
        var overlayIndex = findOverlay(point) 
        if((mapMode == "view") && (overlayIndex > -1))
        {
          var alertText = makeOverlayText(featureList[overlayIndex].name, featureList[overlayIndex].subtype, featureList[overlayIndex].html);
          map.openInfoWindowHtml(point, alertText);
        }
      }
    }
    
    function mapAdd(point)
    {
      if(pointList != null)
      {
        recordMarker(point);
      }
      else
      {
        window.alert('Click Record Points first.');
      }
    }
    
    function mapRemove(marker, point)
    {
      var removedFeature = -1;
      if(marker)
      {
        removedFeature = findMarker(marker);
      }
      else
      {
        removedFeature = findOverlay(point);
      }
      
      if(removedFeature > -1)
      {
        var confirmed = confirm("Are you sure you wish to remove: " + featureList[removedFeature].name);
        if(confirmed)
        {
          // remove from the database
          var http_request = getXmlHttpObject();
          if(http_request)
          {
            // build url
            var url = "/map.php?action=remove&feature_id="+featureList[removedFeature].id;
            document.getElementById('information').value = url;
            http_request.onreadystatechange = function() { featureRemoved(http_request, removedFeature); };
            http_request.open('GET', url, true);
            http_request.send(null);
          }
        }
      }
    }
    
    function mapModify(marker, point)
    {
      var modifiedFeature = -1;
      if(marker)
      {
        modifiedFeature = findMarker(marker);
      }
      else
      {
        modifiedFeature = findOverlay(point);
      }
      
      if(modifiedFeature > -1)
      {
        // found a feature, display the edit page
        setupForm(featureList[modifiedFeature].type);
        fillInForm(featureList[modifiedFeature].name,featureList[modifiedFeature].type, featureList[modifiedFeature].subtype, featureList[modifiedFeature].html, featureList[modifiedFeature].color, featureList[modifiedFeature].id);

        window.document.getElementById('info_request').style.visibility="visible";
        window.document.getElementById('buttonRecord').style.visibility="hidden";
      }
    }
    
    function featureRemoved(http_request, featureID)
    {
      if (http_request.readyState == 4)
      {
        if (http_request.status == 200)
        {
          var serverResponse = http_request.responseXML;
          alert(serverResponse.getElementsByTagName('message')[0].childNodes[0].nodeValue);
          if(serverResponse.getElementsByTagName('status')[0].childNodes[0].nodeValue == "success")
          {
            map.removeOverlay(featureList[featureID].overlay);
            featureList[featureID] = null;
          }
        }
      }
    }
    
    function findMarker(marker)
    {
      var markerIndex = -1;
      for(var i = 0; i < featureList.length; i++)
      {
        if(featureList[i] != null)
        {
          if((featureList[i].type == "Location") && (marker.getPoint() == featureList[i].overlay.getPoint()))
          {
            markerIndex = i;
            break;
          }
        }
      }
      return markerIndex;
    }
    
    function findOverlay(point)
    {
      var overlayIndex = -1;
      
      for(var i = 0; i < featureList.length; i++)
      {
        if(featureList[i] != null)
        {
          if ((featureList[i].type == "Territory") && (featureList[i].overlay.inZone(point)))
          {
            overlayIndex = i;
            break;
          }
        }
      }
      return overlayIndex;
    }
    
    
    // called from js on toggle button
    function toggleRecord()
    {
      if(pointList == null)
      {
        pointList = new Array();
        markerList = new Array();
        window.document.getElementById('buttonRecord').value = "Stop Recording";
      }
      else
      {
        if(drawLines("#ff0000"))
        {
          confirmTerritory();
        }
      }
    }
    
    // called from mouse click event in load function
    function recordMarker(point)
    {
      var marker = new GMarker(point);
      markerList.push(marker);
      map.addOverlay(marker);
      pointList.push(new GLatLng(point.y, point.x));
      
      if(featureType != "Territory")
      {
        // stop recording, only need a single point
        toggleRecord();
      }
    }
    
    // called after processing the feature
    function cleanUp()
    {
      // remove the markers
      pointList = null;
      markerList = null;
      window.document.getElementById('buttonRecord').value = "Record Points";
    }
    
    // called from toggleRecord()
    function drawLines(color)
    {
      removeMarkers();
      
      if((pointList.length == 1) && (featureType != "Territory"))
      {
        var marker = createMarker(pointList[0], "http://www.wantonwicked.net/img/location_" + featureType.toLowerCase() + ".png");
      
        map.addOverlay(marker);
        tempFeature = marker;
        return true;
      }
      else if(pointList.length >= 3)
      {  
        var territory = new GPolygon(pointList, true, color, .25, true);
        map.addOverlay(territory);
        tempFeature = territory;
        return true;
      }
      else
      {
        cleanUp();
        return false;
      }
    }
    
    // called from drawLines()
    function removeMarkers()
    {
      for(var i = 0; i < markerList.length; i++)
      {
        map.removeOverlay(markerList[i]);
      }
    }
    
    function confirmTerritory()
    {
      var confirmed;
      if(featureType == "Territory")
      {
         confirmed = confirm('Do you want to save this territory?');
      }
      else
      {
        confirmed = confirm('Do you want to save this location?');
      }
      
      if(confirmed)
      {
        // get color and text and store
        var type = "";
        var subtype = "";
        
        if(featureType == "Territory")
        {
          type = "Territory";
          subtype = "District";
        }
        else
        {
          type = "Location";
          subtype = featureType;
        }
        
        setupForm(featureType);
        fillInForm("", type, subtype, "", "", 0);

        window.document.getElementById('info_request').style.visibility="visible";
        window.document.getElementById('buttonRecord').style.visibility="hidden";
      }
      else
      {
        // remove the feature held in temp storage
        map.removeOverlay(tempFeature);
        cleanUp();
      }
    }

    // called from confirmTerritory();    
    function setupForm(selectedFeatureType)//setTypeAndSubtype()
    {
      if(selectedFeatureType == "Territory")
      {
        document.getElementById('feature_subtype_select').style.visibility="visible";
        document.getElementById('feature_subtype_select').style.width="150px";
        document.getElementById('feature_color_text').style.visibility="visible";
      }
      else
      {
        document.getElementById('feature_subtype_select').style.visibility="hidden";
        document.getElementById('feature_subtype_select').style.width="0px";
        document.getElementById('feature_color_text').style.visibility="hidden";
      }
    }
    
    function fillInForm(name, type, subtype, html, color, id)
    {
      document.getElementById('feature_name').value = name;
      document.getElementById('feature_type').innerHTML = type;
      if(type == "Territory")
      {
        document.getElementById('feature_subtype').innerHTML = "";
        document.getElementById('feature_subtype_select').value = subtype;
      }
      else
      {
        document.getElementById('feature_subtype').innerHTML = subtype;
      }
      document.getElementById('feature_color').value = color;
      document.getElementById('feature_html').value = html;
      document.getElementById('feature_id').value = id;
    }
    
    // called from info form
    function cancelFeature()
    {
      if(mapMode.toLowerCase() == 'add')
      {
        map.removeOverlay(tempFeature);
        cleanUp();
      }      
      hideInfoForm();
    }
    
    // called from info form
    function saveFeatureToServer(feature)
    {
      var http_request = getXmlHttpObject();
      if(http_request)
      {
        // build url
        var url = buildURL();
        document.getElementById('information').value = url;
        http_request.onreadystatechange = function() { alertStatus(http_request); };
        http_request.open('GET', url, true);
        http_request.send(null);
      }
    }
    
    // called from saveFeatureToServer()
    function buildURL()
    {
      var url = '';
      if(mapMode.toLowerCase() == 'add')
      {
        var subtype = featureType;
        if(featureType == "Territory")
        {
          subtype = document.getElementById('feature_subtype_select').value;
        }
        url = "/map.php?action=add";
        url += "&name=" + encodeURIComponent(document.getElementById('feature_name').value);
        url += "&type=" + document.getElementById('feature_type').innerHTML;
        url += "&subtype=" + subtype;
        url += "&html=" + encodeURIComponent(document.getElementById('feature_html').value);
        url += "&color=" + encodeURIComponent(document.getElementById('feature_color').value);
        url += "&site_id=" + siteID;
        
        for(var i = 0; i < pointList.length; i++)
        {
          url += "&lat[]=" + pointList[i].lat();
          url += "&lng[]=" + pointList[i].lng();
        }
      }
      
      if(mapMode.toLowerCase() == 'modify')
      {
        var selectedFeatureType = document.getElementById('feature_type').innerHTML;
        var subtype = document.getElementById('feature_subtype').innerHTML;
        if(selectedFeatureType == 'Territory')
        {
          subtype = document.getElementById('feature_subtype_select').value;
        }
        
        url = "map.php?action=modify";
        url += "&name=" + encodeURIComponent(document.getElementById('feature_name').value);
        url += "&type=" + selectedFeatureType;
        url += "&subtype=" + subtype;
        url += "&html=" + encodeURIComponent(document.getElementById('feature_html').value);
        url += "&color=" + encodeURIComponent(document.getElementById('feature_color').value);
        url += "&id=" + encodeURIComponent(document.getElementById('feature_id').value);
      }
      
      return url;
    }
    
    // called when there is a response from the server
    function alertStatus(http_request)
    {
      if (http_request.readyState == 4)
      {
        if (http_request.status == 200)
        {
          var serverResponse = http_request.responseXML;
          alert(serverResponse.getElementsByTagName('message')[0].childNodes[0].nodeValue);
          if(mapMode.toLowerCase() == 'add')
          {
            if(serverResponse.getElementsByTagName('status')[0].childNodes[0].nodeValue == "success")
            {
              // add to local feature list
              var featureID = serverResponse.getElementsByTagName('featureId')[0].childNodes[0].nodeValue;
              var featureName = document.getElementById('feature_name').value;
              var featureHTML = document.getElementById('feature_html').value;
              var tempFeatureType = document.getElementById('feature_type').innerHTML;
              var featureSubtype = document.getElementById('feature_subtype_select').value;
              var featureColor = document.getElementById('feature_color').value;
              if(featureType != "Territory")
              {
                featureSubtype = featureType;
              }
              var myFeature = new Feature(featureID, featureName, featureHTML, tempFeatureType, featureSubtype, tempFeature, featureColor);
              featureList.push(myFeature);
            }
            else
            {
              // remove from feature list
              alert('There was a problem adding the feature on the server side.');
              map.removeOverlay(tempFeature);
            }
          }
          
          if(mapMode.toLowerCase() == 'modify')
          {
            if(serverResponse.getElementsByTagName('status')[0].childNodes[0].nodeValue == "success")
            {
              // update local feature list
              
              var featureID = serverResponse.getElementsByTagName('featureId')[0].childNodes[0].nodeValue;
              featureList[featureID].name = document.getElementById('feature_name').value;
              featureList[featureID].html = document.getElementById('feature_html').value;
              featureList[featureID].type = document.getElementById('feature_type').innerHTML;
              featureList[featureID].subtype = document.getElementById('feature_subtype_select').value;
              featureList[featureID].color = document.getElementById('feature_color').value;
              if(featureList[featureID].type != "Territory")
              {
                featureList[featureID].subtype = document.getElementById('feature_subtype').innerHTML;
              }
            }
          }
        }
        else
        {
          alert('There was a problem with the request.');
          map.removeOverlay(tempFeature);
        }
      }
      cleanUp();
      hideInfoForm();
      
      http_request = null;
    }
    
    function hideInfoForm()
    {
      if(mapMode.toLowerCase() == 'add')
      {
        window.document.getElementById('buttonRecord').style.visibility="visible";
      }
      window.document.getElementById('info_request').style.visibility="hidden";
      window.document.getElementById('feature_subtype_select').style.visibility="hidden";
      window.document.getElementById('feature_color_text').style.visibility="hidden";
    }
    
    function setFeatureType(selectedFeatureType)
    {
      featureType = selectedFeatureType;
    }
    
    
    // Feature Object
    function Feature_getID()
    {
      return this.id;
    }
    
    function Feature(id, name, html, type, subtype, overlay, color)
    {
      this.id = id;
      this.name = name;
      this.html = html;
      this.type = type;
      this.subtype = subtype;
      this.overlay = overlay;
      this.color = color;
      this.getID = Feature_getID;
    }

    // utility function to get a proper XMLHttpObject    
    function getXmlHttpObject()
    { 
      var objXMLHttp=false;
      
      if (window.XMLHttpRequest)
      {
        objXMLHttp=new XMLHttpRequest();
      }
      else if (window.ActiveXObject)
      {
        try
        {
          objXMLHttp = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) 
        {
          try
          {
            objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
          catch (e)
          {
            alert('Microsoft.XMLHTTP Failed');
          }
        }
      }
      
      if(!objXMLHttp)
      {
        alert('Failed to make XML HTTP Instance. Not Saving.');
      }
      return objXMLHttp
    }
    
    //]]>
  </script>
  </head>
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
    
  <b1><!--Pngtw_FUeNoNy0EKgCAQBdAbOZnR0D3ah+lgEY5SH+b6uXqrR1VBe3yLYPIUgl95sGwzM5mZs6hoand6JDsVkMlJqSlkvFJjPyAfXL/6D365GY4=--></b1></body>
</html>
EOQ;

echo $code;
?>