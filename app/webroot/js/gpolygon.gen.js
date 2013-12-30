// GPolygon preview
// This is code that's been ripped from the 2.49 version
// of the api. It's mostly functional.
// Chris Smoak - 3 May 2006
// Modified by Jim Miller - 4 August 2006

// For this script to work correctly, you will need to use
// version 2.52 or greater of the Google Maps API. It has been
// tested with versions 2.52-2.60. As of this writing, you can
// specify the 'v' parameter of the api javascript url to be
// either specify one of the versions in that range or 2.x:
//   <script src="http://maps.google.com/maps?file=api&v=2.x&key=abc123" type="text/javascript"></script>


/* NO LONGER NEEDED
// In order to use a different API version, you will need
// to update the following functions with their current
// obfuscated name. You can find these through careful
// inspection of the api javascript. The names I have
// given these methods are probably innacurate:

//GMap2.prototype.getDivPixelCenter = GMap2.prototype.D;
//GMap2.prototype.fromDivPixelsToLatLngBounds = GMap2.prototype.df;
//GPolyline.prototype.getVectors = GPolyline.prototype.Jb;  
//GPolyline.prototype.getPoints = GPolyline.prototype.Hb;  
//GPolyline.prototype.getSomething = GPolyline.prototype.Ib;  
*/


// XUserAgent is a copy of the user agent code in maps.10.js

// user agent types
XUserAgent.IE = 1;
XUserAgent.MOZILLA = 2;
XUserAgent.SAFARI = 3;
XUserAgent.OPERA = 4;

// user agent OSes
XUserAgent.WIN = 0;
XUserAgent.NIX = 1;
XUserAgent.MAC = 2;

function XUserAgent(type, version, os) { this.type = type; this.version = version; this.os = os };

XUserAgent.instance = function() {
    var userAgent = new XUserAgent(0, 0, null);
    var userAgentName = navigator.userAgent.toLowerCase();
    if (userAgentName.indexOf("opera") != -1) {
        userAgent.type = XUserAgent.OPERA;
        if (userAgentName.indexOf("opera/7") != -1 || userAgentName.indexOf("opera 7") != -1) {
            userAgent.version = 7
        } else if (userAgentName.indexOf("opera/8") != -1 || userAgentName.indexOf("opera 8") != -1) {
            userAgent.version = 8
        }
    } else if (userAgentName.indexOf("msie") != -1 && document.all) {
        userAgent.type = XUserAgent.IE;
        if (userAgentName.indexOf("msie 5")) { userAgent.version = 5 }
    } else if (userAgentName.indexOf("safari") != -1) { userAgent.type = XUserAgent.SAFARI }
    else if (userAgentName.indexOf("mozilla") != -1) { userAgent.type = XUserAgent.MOZILLA }
    if (userAgentName.indexOf("x11;") != -1) { userAgent.os = XUserAgent.NIX }
    else if (userAgentName.indexOf("macintosh") != -1) { userAgent.os = XUserAgent.MAC }

    return userAgent;
}();

// Turns on SVG polylines
_mSvgForced = true;
_mSvgEnabled = true;

XUserAgent.prototype.supportsSvg = function() {
  if (false && !_mSvgForced) {
    if (this.os == XUserAgent.WIN) { return false }
    if (this.type != XUserAgent.SAFARI) { return false }
  }
  if (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#SVG","1.1")) { return true }
  return false
};


// from maps2.50a.js

//function GPolygon(polylines, fill, color, opacity, outline) {
function GPolygon(points, fill, color, opacity, outline) {
  this.points = [];
  this.sortedPoints = [];
  
  if (points) {
    this.points = points.slice();
    this.points.push(points[0]);

    // copy the points array into one that will be sorted to find the lowest latitude
    this.sortedPoints = points.slice();
    this.sortedPoints.sort();
  }

//  this.polylines = polylines || []; // t
  this.fill = fill != null ? fill : true; // od
  this.color = color || "#0055ff"; // A
  this.opacity = opacity || 0.25; // B
  this.outline = outline != null ? outline : true // ge
  this.outlineThickness = 4;

  this.polylines = [new GPolyline(this.points, this.color, this.outlineThickness)];//, this.opacity)];

  this.minLat = this.sortedPoints[0].lat();
  this.maxLat = this.sortedPoints[this.sortedPoints.length-1].lat();
  this.minLng = 0;
  this.maxLng = 0;
  this.getMinMaxLng(this.points);
  this.latlngBounds = new GLatLngBounds(new GLatLng(this.minLat, this.minLng), new GLatLng(this.maxLat, this.maxLng));
}

GPolygon.prototype.initialize = function(map) {
  this.map = map; // a
  for (var i = 0; i < this.polylines.length; i++) {
    this.polylines[i].initialize(map)
  }
}

GPolygon.prototype.remove = function() {
  for (var i = 0; i < this.polylines.length; ++i) {
    this.polylines[i].remove()
  }
  var elem = this.element;
  if (elem) {
    elemRemove(elem);
    this.element = null;
    // TODO: s(this, 'remove')
  }
}

GPolygon.prototype.copy = function() {
  return new GPolygon(this.polylines, this.fill, this.color, this.opacity, this.outline)
}

GPolygon.prototype.redraw = function(force) {
  redrawLinearOverlay(this, 3, force)
};

// JIM ADD
GPolygon.prototype.getBounds = function() {
    return this.latlngBounds;
}

// Gets the minimum and maximum longitudes in an array of points
GPolygon.prototype.getMinMaxLng = function(a) {
    this.minLng = a[0].lng();
    this.maxLng = a[0].lng();
    for (var i = 1; i < a.length; i++) {
        if (a[i].lng() < this.minLng) {
            this.minLng = a[i].lng();
        }
        if (a[i].lng() > this.maxLng) {
            this.maxLng = a[i].lng();
        }
    }
}

// Determines if a point is inside a polygon
GPolygon.prototype.inZone = function(pt) {
    var isInZone = 0;
    var j = this.points.length-1;
    var eval;
    for (var i = 0; i < this.points.length; j = i++) {
      eval = ((((this.points[i].lat() <= pt.lat()) && (pt.lat() < this.points[j].lat())) ||
             ((this.points[j].lat() <= pt.lat()) && (pt.lat() < this.points[i].lat()))) &&
             (pt.lng() < (this.points[j].lng() - this.points[i].lng()) *
             (pt.lat() - this.points[i].lat()) / (this.points[j].lat() - this.points[i].lat()) + this.points[i].lng()))
      if (eval) {
          isInZone++;
      }
    }
    if ((isInZone % 2) == 0) {
        return false;
    }
    else {
        return true;
    }
} 
// END JIM ADD

GPolygon.prototype.getSomething = function() { // Va
  var min = 100; // a
  for (var i = 0; i < this.polylines.length; i++) {
    var c = this.polylines[i].getSomething(this);
    if (min > c) { min = c }
  }
  return min
};

GPolygon.prototype.getVectors = function(latLngBounds, b) { // Za
  var c = [];
  for (var i = 0; i < this.polylines.length; i++) {
    c.push(this.polylines[i].getVectors(latLngBounds, b, this))
  }
  return c
};

GPolygon.prototype.getPoints = function(vectors, points, extent) {
  for (var i = 0; i < this.polylines.length; i++) {
    var pts = [];
//    this.polylines[i].getPoints(vectors[i], pts, extent);
    this.polylines[i].getPoints(vectors[i], pts, extent, this);
    points.push(pts)
  }
  return points
}

GPolygon.prototype.rgba = function() {
  var color = this.color;
  if (color.charAt(0) == '#') {
    color = color.substring(1);
  }
  var r = parseInt(color.substring(0, 2), 16);
  var g = parseInt(color.substring(2, 2), 16);
  var b = parseInt(color.substring(4, 2), 16);
  return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + this.opacity;
}

GPolygon.prototype.createElement = function(imageBounds, latLngBounds, pane, svg) { // cd
  var e = this.getSomething();
  var vectors = this.getVectors(latLngBounds, e); // f, Za
  var points = []; // g
  var extent = new GBounds(); // h
  this.getPoints(vectors, points, extent); // Xa
  var element = null; // i
  if (points.length > 0 && this.fill) {
    if (svg) {
      var width = imageBounds.max().x - imageBounds.min().x; // l
      element = document.createElementNS($svgNamespaceUrl, "svg");
      var poly = document.createElementNS($svgNamespaceUrl, "polygon"); // n
      this.poly = poly;
      element.appendChild(poly);
      elemSetPosition(element, new GPoint(extent.min().x, extent.min().y));
      element.setAttribute("version", "1.1");
      element.setAttribute("width", (width + 10) + 'px');
      element.setAttribute("height", (width + 10) + 'px');
      element.setAttribute("viewBox", extent.min().x + " " + extent.min().y + " " + width + " " + width);
      element.setAttribute("overflow", "visible");
      var pointsString = pointsToSvgString(points); // p
      poly.setAttribute("points", pointsString);
      poly.setAttribute("fill-rule", "evenodd");
      poly.setAttribute("fill", this.color);
      poly.setAttribute("fill-opacity", this.opacity);
/*
      // my attempt at a mouseover effect. doesn't always work--don't know why.

      poly.style.zIndex = 100000;
      var _this = this;
      poly.addEventListener('mouseover', function() {
        _this.opacity = 0.5;
        _this.poly.setAttribute('fill-opacity', _this.opacity);
      }, false);
      poly.addEventListener('mouseout', function() {
        _this.opacity = 0.25;
        _this.poly.setAttribute('fill-opacity', _this.opacity);
      }, false);
*/
      pane.appendChild(element)
    } else {
      var center = this.map.getDivPixelCenter(); // M
      element = createVmlElement("v:shape", pane, center, new GSize(1, 1));
      element.unselectable = "on";
      element.coordorigin = center.x + " " + center.y;
      element.coordsize = "1 1";
      var pointsString = pointsToVmlString(points);
      element.path = pointsString;
      var fill = createVmlElement("v:fill", element);
      fill.color = this.color;
      fill.opacity = this.opacity;
      var stroke = createVmlElement("v:stroke", element);
      stroke.opacity = 0
    }
  }
  return element
};

//JIM ADD
GMap2.prototype.getDivPixelCenter = function() {
//  var a=this.q();
var c = this.getBounds().getCenter();
var a = this.fromLatLngToDivPixel(c);
//  var b=this.getSize();
//  a.x+=Math.round(b.width/2);
//  a.y+=Math.round(b.height/2);
  return a     // a must be of type GPoint
};

GMap2.prototype.fromDivPixelsToLatLngBounds = function(bl, tr) {
    var sw = this.fromDivPixelToLatLng(bl);
    var ne = this.fromDivPixelToLatLng(tr);
    var llb = new GLatLngBounds(sw, ne);
    return llb;
};

// GPolyline.getVectors
GPolyline.prototype.getVectors=function(a,b,gp){  // a = latLngBounds, b = getSomething()
  var c=[];
//  this.xf(a,0,this.z.length-1,this.Rb.length-1,b,c);       // Rb = i[[]]
  this.myxf(a,0,gp.points.length-1,0,b,c,gp);
  return c
}

GPolyline.prototype.myxf=function(a,b,c,d,e,f,gp){ // a= latLngBounds  f=[]
//GPolyline.prototype.xf=function(a,b,c,d,e,f){ // a= latLngBounds  f=[]
  var g=7.62939453125E-6;
  for(var h=d;h>0;--h){
    g*=32;  //this.dd
  }
  var i=null;
  if(a){
    var m=a.getSouthWest()
    var o=a.getNorthEast()
    var p=new GLatLng(m.lat()-g,m.lng()-g,true);
    var s=new GLatLng(o.lat()+g,o.lng()+g,true);
    i=new GLatLngBounds(p,s)
  }
  var t=b;
  var z=1; //z;
  var A=gp.points[t];  //A=this.z[t]; 
  //while((z=this.Rb[d][t])<=c){
  while (z <= c) {
    var L=gp.points[z];  //var L=this.z[z];
    var N=new GLatLngBounds;
    N.extend(A);
    N.extend(L);
    if(i==null||i.intersects(N)){
      if(d>e){
        this.myxf(a,t,z,d-1,e,f,gp)
      }else{
        f.push(A);
        f.push(L)
      }
    }
    var P=A;
    A=L;
    L=P;
    z++;  //t=z
  }
}


GPolyline.prototype.getPoints=function(a,b,c,gp){    
  var d=null;
  var e=a.length;
  var f=this.myWk(a,gp);
  for(var g=0;g<e;++g){
    var h=(g+f)%e;
    var i=d=gp.map.fromLatLngToDivPixel(a[h],d);   //var i=d=this.a.e(a[h],d);
    b.push(Math.floor(i.x));
    b.push(Math.floor(i.y));
    c.extend(i)
  }
  return b
}

GPolyline.prototype.myWk=function(a,gp){
  if(!a||a.length==0){
    return 0
  }
  if(!a[0].equals(a[a.length-1])){
    return 0
  }

  // Normally set in GPolyline constructor
  this.he = 0;
  if(gp.points.length > 0){
    if(gp.points[0].equals(gp.points[gp.points.length-1])){
      this.he=Of(gp.points)
    }
  }

  if(this.he==0){
    return 0
  }
  b= gp.map.getCenter();  //var b=this.a.n();
  var c=0;
  var d=0;
  for(var e=0;e<a.length;e+=2){
    var f=Xb(a[e].lng()-b.lng(),-180,180)*this.he;  // this.he = 0 or Of(e.z) (z is array of points)
    if(f<d){
      d=f;c=e
    }
  }
  return c
}

function Of(a){  // a is an array of GLatLng
  var b=0;
  for(var c=0;c<a.length-1;++c){
    b+=Xb(a[c+1].lng()-a[c].lng(),-180,180)
  }
  var d=Math.round(b/360);
  return d
}


function Xb(a,b,c){
  while(a>c){ // while (a > 180)
    a-=c-b  // a -= 360
  }
  while(a<b){ // while (a < -180)
    a+=c-b  // a += 360
  }
  return a
}

GPolyline.prototype.getSomething=function(gp){
  var a=0;
  var b=gp.points[0];  //var b=this.z[0];
  var c = new GSize(1.0E-5, 1.0E-5);  //c=new q(this.vg,this.vg);  this.vg = 1.0E-5
  var d=new GSize(2,2);
  var e=32;  //e=this.dd;
  while(a<1){ // l(this.Rb) = this.Rb.length
    c.width*=e;  // c is type GSize
    c.height*=e;
    var f=b.lat()-c.height/2;
    var g=b.lng()-c.width/2;
    var h=f+c.height;
    var i=g+c.width;
    var m=new GLatLngBounds(new GLatLng(f,g),new GLatLng(h,i));
//    var o=this.a.k().nb(m,d);
    var o = gp.map.getCurrentMapType().getBoundsZoomLevel(m,d);
    if (gp.map.getZoom() >= o) {   //if(this.a.r()>=o){
      break
    }
    ++a
  }
  return a
}

// END JIM ADD

function redrawLinearOverlay(obj, type, force) { // od
  var map = obj.map; // d
  var size = map.getSize(); // i
  var center = map.getDivPixelCenter(); // f // u
  if (!force) {
    var left = center.x - Math.round(size.width / 2); // g
    var top = center.y - Math.round(size.height / 2); // h
    var bounds = new GBounds([new GPoint(left, top), new GPoint(left + size.width, top + size.height)]); // i
    if (obj.bounds.containsBounds(bounds)) { return } // ya
  }
  var ie = XUserAgent.instance.type == XUserAgent.IE; // l, w, 1
  var svg = XUserAgent.instance.supportsSvg(); // n
  var maxSize = 900; // p
  var width, height;
  if (ie || svg) {
    width = Math.max(1000, screen.width); // q
    height = Math.max(1000, screen.height) // u
  } else {
    width = Math.min(size.width, maxSize);
    height = Math.min(size.height, maxSize)
  }
  var bl = new GPoint(center.x - width, center.y + height); // y
  var tr = new GPoint(center.x + width, center.y - height); // C
  var imageBounds = new GBounds([tr, bl]); // E
  obj.bounds = imageBounds; // tf
  obj.remove();
  var latLngBounds = map.fromDivPixelsToLatLngBounds(bl, tr); // J
  var pane = map.getPane(0); // L, E
  if(svg || ie) {
    obj.element = obj.createElement(imageBounds, latLngBounds, pane, svg) // D, cd
  } else {
    if (type == 3) {
      // TODO: ie?
    } else if (type == 2) {
// not needed for polygon
//      obj.D = obj.lf(E, J, L)
    }
  }
  if (type == 3 && obj.outline) {
    for (var i = 0; i < obj.polylines.length; i++) {
      obj.polylines[i].redraw(force)
    }
  }
}


function pointsToSvgString(points) { // pe
  var string = "";
  for (var i = 0; i < points.length; i++) {
    string += points[i].join(" ") + " "
  }
  return string
}

function pointsToVmlString(points) {
  var parts = [];
  for (var i = 0; i < points.length; i++) {
    var vml = encodeVmlPoints(points[i]);
    parts.push(vml.substring(0, vml.length - 1))
  }
  parts.push("e");
  return parts.join(" ")
}

function encodeVmlPoints(a) {
  var b = [];
  var c;
  var d;
  for (var e = 0; e < a.length; ) {
    var f = a[e++];
    var g = a[e++];
    var h = a[e++];
    var i = a[e++];
    if (g != c || f != d) {
      b.push("m");
      b.push(f);
      b.push(g);
      b.push("l")
    }
    b.push(h);
    b.push(i);
    c = i;
    d = h
  }
  b.push("e");
  return b.join(" ")
}


function elemSetPosition(element, position) {
  var style = element.style;
  style.position = "absolute";
  style.left = (position.x) + 'px';
  style.top = (position.y) + 'px'
}


function elemOwnerDocument(elem) {
  return (elem ? elem.ownerDocument : null) || document
}

function elemSetSize(elem, size) {
  var style = elem.style;
  style.width = (size.width) + 'px';
  style.height = (size.height) + 'px'
}

function createVmlElement(name, parent, position, size) { // Kb
  var vml = elemOwnerDocument(parent).createElement(name);
  if (parent) { parent.appendChild(vml) }
  vml.style.behavior = "url(#default#VML)";
  if (position) { elemSetPosition(vml, position) }
  if (size) { elemSetSize(vml, size) }
  return vml
}

function elemRemove(elem) { // Z
  if (elem.parentNode) {
    elem.parentNode.removeChild(elem);
    // TODO: clean up listeners : ae(elem, wc)
  }
}

var $svgNamespaceUrl = "http://www.w3.org/2000/svg";

/*

// this is code to clean up events after the element has been
// removed--I wasn't able to recreate this b/c certain
// functions are hid within the api code and are not accessible here.
// *sad*

function ae(a, b, c) {
  if (b) { b.call(null,a) }
  for (var d = a.firstChild; d; d = d.nextSibling) {
    if (d.nodeType == 1) {
      arguments.callee.call(this, d, b, c)
    }
  }
  if (c) { c.call(null, a) }
}

function wc(a) {
  s(a, 'clearlisteners');
  bb(ed(a), function() {
    this.remove();
    zb(Ua, this)
  })
}

  
function ed(a){var b=[];if(a["__e_"]){Jb(b,a["__e_"])}return b}
  


function zb(a,b,c){var d=0;for(var e=0;e<a.length;++e){if(a[e]===b||c&&a[e]==b){a.splice(e--,1);d++}}return d}

*/
