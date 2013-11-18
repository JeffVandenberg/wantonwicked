<?
$code = <<<EOQ
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <style type="text/css">
        v\:* {behavior:url(#default#VML); }
      	html, body { width: 99%; height: 95% }
      	body { margin-top: 3px; margin-right: 0px; margin-left: 3px; margin-bottom: 0px }
    </style>
    <script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAmGTfcjlUIeXs4aoEIragyxT59gojkbh5st5ZsHLr70RjPARmzBT7L3rhZrOG_BQy3b5PoUuCKuvZAg"
      type="text/javascript"></script>
    <script src="js/gpolygon.gen.js" type="text/javascript"></script>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
  </head>
  <script type="text/javascript">

    //<![CDATA[
    
    var pointList = null;
    var markerList = null;
    var map = null;

    function load() {
      if (GBrowserIsCompatible()) {
        
        map = new GMap2(document.getElementById("map1"));
        //map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        map.setCenter(new GLatLng(29.9555, -90.09407), 12);
        //map.addControl(new GSmallMapControl());
        //map.addControl(new GMapTypeControl());
        map.disableDragging();

        GEvent.addListener(map, "click", function(marker, point) {
          if (marker) {
            map.removeOverlay(marker);
          }
          else {
            if(pointList != null)
            {
              var marker = new GMarker(point);
              markerList.push(marker);
              map.addOverlay(marker);
              pointList.push(new GLatLng(point.y, point.x));
            }
            else
            {
              window.alert('Click Record Points first.');
            }
          }
        });
      }
    }
    
    function toggleRecord()
    {
      if(pointList == null)
      {
        //alert('initializing pointList');
        pointList = new Array();
        markerList = new Array();
        window.document.getElementById('buttonRecord').value = "Stop Recording";
      }
      else
      {
        drawLines();
        removeMarkers();
        // remove the markers
        pointList = null;
        markerList = null;
        window.document.getElementById('buttonRecord').value = "Record Points";
      }
    }
    
    function drawLines()
    {
      map.addOverlay(new GPolygon(pointList, true, "#f00", .5, true));
    }
    
    function removeMarkers()
    {
      for(var i = 0; i < markerList.length; i++)
      {
        map.removeOverlay(markerList[i]);
      }
    }

    //]]>
  </script>
  <body onload="load()" onunload="GUnload()">
    <div id="map1" style="width: 800px; height: 800px"></div>
    <form>
      <input type="button" id='buttonRecord' value="Record Points" onclick="toggleRecord();">
    </form>
  <b1><!--Pngtw_FUeNoNy0EKgCAQBdAbOanR0D3ah9lgEY5SH+b6uXqrR1VBW3qLYPIUo194MK+BmczMWVI0tTs/cjoVkMlBuSlkvFJT3yEfgutX/wGXyxnA--></b1></body>
</html>
EOQ;

echo $code;
?>