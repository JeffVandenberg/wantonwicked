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
  return "<div style=\"text-align:left\" class=\"featureinformation\"><b>Name:</b> " + featureName + "<br><b>Type:</b> " + featureSubtype + "<br>" + featureHTML + "</div>";
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
