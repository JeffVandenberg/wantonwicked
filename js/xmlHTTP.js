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
