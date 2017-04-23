/*
* init ajax object
*
*/

//Define XmlHttpRequest
var updateClickThru = getXmlHttpRequestObject();

/*
* show banner adverts
*
*/

function showBannerAds()
{
	// show adver container
	document.getElementById("adverContainer").style.visibility = "visible";	

	// if div does not exist
	if(!document.getElementById("myB"))
	{
		var ni = document.getElementById('adverContainer');

		var newdiv = document.createElement('iframe');
			newdiv.setAttribute("id","myB");
			newdiv.src = "plugins/adver/index.php";	
			newdiv.height="65";
			newdiv.width="475";
			newdiv.frameBorder="0";

			ni.appendChild(newdiv);
	}

	refreshBanner = refreshBanner * 1000;

	var i = setInterval("refreshAdver()",refreshBanner);
}

/*
* refresh banner adverts
*
*/

function refreshAdver()
{
	document.getElementById('myB').src = "plugins/adver/index.php";
}

/*
* update click thro's
*
*/ 

function adverClick(id)
{
	var param = '?';
	param += '&clickID='+id;

	// if ready to send message to DB
	if (updateClickThru.readyState == 4 || updateClickThru.readyState == 0) 
	{
		updateClickThru.open("POST", 'plugins/adver/index.php?rnd='+ Math.random(), true);
		updateClickThru.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		updateClickThru.onreadystatechange = handleClickThru;
		updateClickThru.send(param);
	}
}

function handleClickThru()
{
	// empty
}