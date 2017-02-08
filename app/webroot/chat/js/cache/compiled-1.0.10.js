/*
* user settings
*
*/

var userRPM = true;
var userRWebcam = false;
var userEntryExitSFX = true;
var userNewMessageSFX = true;
var userSFX = true;

/*
* create cookie
*
*/

function createCookie(name,value,days)
{
    var expires = "";
	if(days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires = "; expires="+date.toGMTString();
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

createCookie('login','',-1);

/*
* read cookie
*
*/

function readCookie(name)
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}

	return null;
}

/*
* get cookie
*
*/

var gotCookie1 = readCookie('myTextStyle');
var gotCookie2 = readCookie('myOptions');

function getCookie()
{
    var gotCookie;
	if(gotCookie1)
	{
		gotCookie = decodeURI(gotCookie1).split("|");

		mBold = gotCookie[0];
		mItalic = gotCookie[1];
		mUnderline = gotCookie[2];
		textColor = getSafeColor(gotCookie[3], defaultColor);
		textSize = gotCookie[4];
		textFamily = gotCookie[5];
	}

	if(gotCookie2)
	{
		gotCookie = decodeURI(gotCookie2).split("|");

		userRPM = gotCookie[0];
		userRWebcam = gotCookie[1];
		userEntryExitSFX = gotCookie[2];
		userNewMessageSFX = gotCookie[3];
		userSFX = gotCookie[4];
        textScale = gotCookie[5];
	}
}

function getSafeColor(hexValue, defaultColor)
{
	if(myColor.indexOf(hexValue) === -1) {
		return defaultColor;
	}
	return hexValue;
}
/*
* resize main divs
*
*/

function resizeDivs()
{
	var w = 0, h = 0;

	// check browser type and get window sizes
  	if( typeof( window.innerWidth ) == 'number' ) 
	{
    	// Non-IE
    	w = window.innerWidth;
		h = window.innerHeight;
 	} 
	else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) 
	{
    	// IE 6+ in 'standards compliant mode'
    	w = document.documentElement.clientWidth;
    	h = document.documentElement.clientHeight;
  	} 
	else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) 
	{
    	// IE 4 compatible
    	w = document.body.clientWidth;
    	h = document.body.clientHeight;
  	}
    var jqWindow = $(window);
    w = jqWindow.width();
    h = jqWindow.height();

	// set main container width
	document.getElementById("mainContainer").style.width = (w - 7) + "px";
	document.getElementById("mainContainer").style.height = (h - 7) + "px";

	// set the width of the userlist
	var userWidth = 236;

	// set the width of the chat screen
	var chatWidth = (w - userWidth) - 29;

	// new chat window width
	document.getElementById("chatContainer").style.width = chatWidth + "px";

	// new chat window height
	document.getElementById("chatContainer").style.height = (h - 224) + "px";

	// new user window width
	document.getElementById("userContainer").style.width = userWidth + "px";

	// new user window height
	document.getElementById("userContainer").style.height = (h - 120) + "px";

	// new top window width
	document.getElementById("topContainer").style.width = (w - 19) + "px";

	// new options window width
	document.getElementById("optionsContainer").style.width = ((w - userWidth) - 25) + "px";

	// new options message bar width
	document.getElementById("optionsBar").style.width = ((w - userWidth) - 125) + "px";

	// new options icon bar width
	document.getElementById("optionsIcons").style.width = ((w - userWidth) - 125) + "px";
	
	// disable whisper
	if(!whisperOn && document.getElementById("uwhisperID"))
	{	
		document.getElementById("uwhisperID").style.display = "none";			
	}	
}

window.onresize = function() {
	resizeDivs();
};/*
* init all
*
*/


function initAll()
{
	resizeDivs();
	displayAdverts();
	getCookie();
	editSettings();

	switchSettingsStatus(userRPM,"allowPM");
	switchSettingsStatus(userRWebcam,"viewMyCamID");
	switchSettingsStatus(userEntryExitSFX,"entryExitID");
	switchSettingsStatus(userNewMessageSFX,"soundsID");
	switchSettingsStatus(userSFX,"sfxID");

    optionsMenu('optionsIcons','optionsBar','chatContainer','menuWin', 0);

	if(publicWelcome == "")
	{
		publicWelcome = lang1;
	}

	var entryWelcome = "../images/notice.gif|"+stextColor+"|"+stextSize+"|"+stextFamily+"|"+publicWelcome+"|1";
	var entryNotice = "entry.png|"+stextColor+"|"+stextSize+"|"+stextFamily+"|"+publicEntry;
	var entryMessages = "../images/notice.gif|"+stextColor+"|"+stextSize+"|"+stextFamily+"|Displaying last messages ...|1";

	createMessageDiv('1',uID,displayMDiv,1,entryWelcome,'doorbell.mp3','','','');

	if(invisibleOn == 1 && (admin == 1 && hide == 1))
	{
		// empty
	}
	else
	{
		if(dispLastMess > 1)
		{
			createMessageDiv('0',uID,displayMDiv,2,entryMessages,'beep_high.mp3','','','');
		}

		roomlogout();
		roomlogin();
	}

	getMessages();
	showRoomHeaders();
}

/*
* Array indexOf method (for unsupported browsers)
* https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Array/indexOf
*/
	
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
		"use strict";
		if (this == null) {
			throw new TypeError();
		}
		var t = Object(this);
		var len = t.length >>> 0;
		if (len === 0) {
			return -1;
		}
		var n = 0;
		if (arguments.length > 1) {
			n = Number(arguments[1]);
			if (n != n) { // shortcut for verifying if it's NaN
				n = 0;
			} else if (n != 0 && n != Infinity && n != -Infinity) {
				n = (n > 0 || -1) * Math.floor(Math.abs(n));
			}
		}
		if (n >= len) {
			return -1;
		}
		var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
		for (; k < len; k++) {
			if (k in t && t[k] === searchElement) {
				return k;
			}
		}
		return -1;
	}
}

/*
* show room headers 
*
*/

function showRoomHeaders()
{
	var i = 1;

	for (i=1;i<=totalRooms;i++)
	{
		if(roomID != i)
		{
			if(document.getElementById("room_"+i))
			{
				toggleHeader('room_'+i);
			}
		}

	}

}

/*
* login message
*
*/

function roomlogin()
{
	roomID = currRoom;

	message = "entry.png|"+stextColor+"|"+stextSize+"|"+stextFamily+"|"+publicEntry;

	// send login message
	setTimeout('sendData(displayMDiv);',1000);
}

/*
* logout message
*
*/

function roomlogout()
{
	// remove user from current room
	removeUsersDiv(uID,roomID);

	// insert logout message in prev room
	if(currRoom != prevRoom)
	{
		roomID = prevRoom;

		message = "exit.png|"+stextColor+"|"+stextSize+"|"+stextFamily+"|"+publicExit;

		// send logout message
		sendData(displayMDiv);
	}
}

/*
* focus windows 
*
*/

var zIndex = 100;

function doFocus(divID)
{
	// if div exists
	if(document.getElementById(divID))
	{
		// assign div zIndex
		document.getElementById(divID).style.zIndex = zIndex;

		if(zIndex >= 31000)
		{
			zIndex = 30000;
		}
		else
		{
			zIndex += 1;
		}

	}
}

/*
* toggle headers 
*
*/

function toggleHeader(headerID,subID)
{
	if(document.getElementById(headerID).style.height != '24px')
	{
		document.getElementById(headerID).style.height = '24px';
		document.getElementById(headerID).style.overflow = 'hidden';

		if(subID) // pChatWin
		{
			document.getElementById(headerID).style.width = "180px";
			document.getElementById("pcontent_"+subID).style.visibility = 'hidden';
			document.getElementById("pmenuBar_"+subID).style.visibility = 'hidden';
			document.getElementById("psendbox_"+subID).style.visibility = 'hidden';
		}
	}
	else
	{
		document.getElementById(headerID).style.height = "auto";
		
		if(subID)
		{
			document.getElementById(headerID).style.height = "300px";
			document.getElementById(headerID).style.width = "400px";

			document.getElementById("ptitle_"+subID).style.backgroundColor = newPM;
			document.getElementById("pcontent_"+subID).style.visibility = 'visible';
			document.getElementById("pmenuBar_"+subID).style.visibility = 'visible';
			document.getElementById("psendbox_"+subID).style.visibility = 'visible';
		}
	}
}

/*
* display adverts container 
*
*/

function displayAdverts()
{
	if(advertsOn == 0 && document.getElementById("advertContainer"))
	{
		document.getElementById("advertContainer").style.visibility = 'hidden';
	}
}

/*
* change rooms 
*
*/

function changeRooms(roomID)
{
	window.location = '?roomID='+roomID;
}

/*
* toggle divs 
*
*/

var topLevel = 100;
function toggleBox(szDivID)
{
	if(document.layers)	   //NN4+
	{
		if(document.layers[szDivID].visibility == "visible")
		{
			document.layers[szDivID].visibility = "hidden";
		}
		else
		{
			document.layers[szDivID].zIndex = topLevel++;
			document.layers[szDivID].visibility = "visible"; 
		}

	}
	else if(document.getElementById)	  //gecko(NN6) + IE 5+
	{
		var obj = document.getElementById(szDivID);

		if(obj.style.visibility == "visible")
		{
			obj.style.visibility = "hidden";
		}
		else
		{
			obj.style.zIndex = topLevel++;
			obj.style.visibility = "visible";
		}

	}
	else if(document.all)	// IE 4
	{
			
		if(document.all[szDivID].style.visibility == "visible")
		{
			document.all[szDivID].style.visibility = "hidden";
		}
		else
		{
			document.all[szDivID].style.zIndex = topLevel++;
			document.all[szDivID].style.visibility = "visible"; 
		}
	}

	if(topLevel > 32000)
	{
		topLevel = 10;
	}
}

/*
* init avatar menu
*
*/

function doAvatars(inputMDiv, displayMDiv, nWin)
{
	createMdiv('avatarsWin',nWin);

	if(displayMDiv != 'chatContainer')
	{
		document.getElementById('avatarsWin').style.bottom = '66px';
	}

	$("#" + nWin).show();
	createMenu(inputMDiv,displayMDiv,'avatarsWin',totalAvatars,loopAvatars);
	toggleBox('avatarsWin');
}

/*
* init sfx menu
*
*/

function doSFX(inputMDiv,displayMDiv, nWin)
{
	createMdiv('sFXWin',nWin);

	if(displayMDiv != 'chatContainer')
	{
		document.getElementById('sFXWin').style.bottom = '66px';
	}

	$("#" + nWin).show();
	createMenu(inputMDiv,displayMDiv,'sFXWin',totalSFX,'1');
	toggleBox('sFXWin');
}

/*
* init smilie menu
*
*/

function doSmilies(inputMDiv, displayMDiv, nWin)
{
	createMdiv('smiliesWin',nWin);

	if(displayMDiv != 'chatContainer')
	{
		document.getElementById('smiliesWin').style.bottom = '66px';
	}

	$("#" + nWin).show();
	createMenu(inputMDiv,displayMDiv,'smiliesWin',totalSmilies,loopSmilies);
	toggleBox('smiliesWin');	
}

/*
* init style window
*
*/

function doStyles(inputMDiv, displayMDiv, nWin)
{
	createMdiv('colorsWin',nWin);
	createMenu(inputMDiv,displayMDiv,'colorsWin',totalColors,loopColors);
	toggleBox('colorsWin');

	createMdiv('fontfamilyWin',nWin);
	createMenu(inputMDiv,displayMDiv,'fontfamilyWin',totalFontFamily,loopFontFamily);
	toggleBox('fontfamilyWin');

	createMdiv('fontsizeWin',nWin);
	createMenu(inputMDiv,displayMDiv,'fontsizeWin',totalFontSize,loopFontSize);
	toggleBox('fontsizeWin');
	$("#" + nWin).show();
	if(displayMDiv != 'chatContainer')
	{
		document.getElementById('colorsWin').style.bottom = '66px';
		document.getElementById('fontfamilyWin').style.bottom = '66px';
		document.getElementById('fontsizeWin').style.bottom = '66px';
	}
}

/*
* options menu
* to hide icons in private windows,
* if(ndiv.search("pmenuBar_")){}
*/

function optionsMenu(ndiv,nBar,nContainer,nWin, toUserId)
{
	document.getElementById(ndiv).innerHTML  = '<span alt="'+lang52+'" title="'+lang52+'" id="smilies" class="iconSmilies" onmouseover="this.className=\'iconSmiliesOver\'" onmouseout="this.className=\'iconSmilies\'" onclick="doSmilies(\''+nBar+'\',\''+nContainer+'\',\''+nWin+'\');"></span>';
	document.getElementById(ndiv).innerHTML +=
        '<span alt="'+lang53+'" title="'+lang53+'" id="ringbell" class="iconRingbell" onmouseover="this.className=\'iconRingbellOver\'" onmouseout="this.className=\'iconRingbell\'" onclick="ringBell(\''+nBar+'\',\''+nContainer+'\',' + toUserId + ')"></span>';
	document.getElementById(ndiv).innerHTML += '<span alt="'+lang54+'" title="'+lang54+'" id="style" class="iconStyle" onmouseover="this.className=\'iconStyleOver\'" onmouseout="this.className=\'iconStyle\'" onclick="doStyles(\''+nBar+'\',\''+nContainer+'\',\''+nWin+'\');"></span>';
    if(true || moderator || admin) {
	    document.getElementById(ndiv).innerHTML += '<span alt="'+lang55+'" title="'+lang55+'" id="avatar" class="iconAvatar" onmouseover="this.className=\'iconAvatarOver\'" onmouseout="this.className=\'iconAvatar\'" onclick="doAvatars(\''+nBar+'\',\''+nContainer+'\',\''+nWin+'\')"></span>';
    }

	if(mySFX[0])
	{
		document.getElementById(ndiv).innerHTML += '<span alt="'+lang56+'" title="'+lang56+'" id="sounds" class="iconSounds" onmouseover="this.className=\'iconSoundsOver\'" onmouseout="this.className=\'iconSounds\'" onclick="doSFX(\''+nBar+'\',\''+nContainer+'\',\''+nWin+'\')"></span>';
	}

	if(ndiv.search("pmenuBar_"))
	{
		document.getElementById(ndiv).innerHTML += '<span alt="'+lang57+'" title="'+lang57+'" id="rubber" class="iconRubber" onmouseover="this.className=\'iconRubberOver\'" onmouseout="this.className=\'iconRubber\'" onclick=\'clrScreen();\'></span>';
		document.getElementById(ndiv).innerHTML += '<span alt="'+lang58+'" title="'+lang58+'" id="edit" class="iconEdit" onmouseover="this.className=\'iconEditOver\'" onmouseout="this.className=\'iconEdit\'" onclick=\'editSettings();\'></span>';

	}

	document.getElementById(ndiv).innerHTML += '<span alt="'+lang59+'" title="'+lang59+'" id="transcripts" class="iconTranscripts" onmouseover="this.className=\'iconTranscriptsOver\'" onmouseout="this.className=\'iconTranscripts\'" onclick=\'showInfoBox("viewTranscripts","400","600","100","index.php?transcripts=1&roomID="+roomID,"");\'></span>';
	document.getElementById(ndiv).innerHTML += '<span alt="'+lang60+'" title="'+lang60+'" id="help" class="iconHelp" onmouseover="this.className=\'iconHelpOver\'" onmouseout="this.className=\'iconHelp\'" onclick=\'newWin("http://wantonwicked.gamingsandbox.com/wiki/index.php?n=GameRef.Chat")\'></span>';

    if(hasSharePlugin) {
        document.getElementById(ndiv).innerHTML += '<span alt="'+lang62+'" title="'+lang62+'" id="share" class="iconShare" onmouseover="this.className=\'iconShareOver\'" onmouseout="this.className=\'iconShare\'" onclick=\'showInfoBox("shareFiles","280","300","260","plugins/share/","");\'></span>';
    }

	if(hasGamesPlugin) {
        document.getElementById(ndiv).innerHTML += '<span alt="'+lang61+'" title="'+lang61+'" id="playGames" class="iconGames" onmouseover="this.className=\'iconGamesOver\'" onmouseout="this.className=\'iconGames\'" onclick=\'showInfoBox("games","370","418","260","plugins/games/","");\'></span>';
    }

	/* do not edit */
	if(showCopyright)
	{
		document.getElementById(ndiv).innerHTML += '<span alt="'+lang63+'" title="'+lang63+'" id="copyright" class="iconCopyright" onmouseover="this.className=\'iconCopyrightOver\'" onmouseout="this.className=\'iconCopyright\'" onclick=\'showInfoBox("copyRight","220","300","200","",copyRight());\'></span>';
	}
}

/*
* create edit div
*
*/
function editSettings()
{
	// if div does not exist
	if(!document.getElementById("editDiv"))
	{
		// create div
		var ni = document.getElementById('settingsWin');
		var newdiv = document.createElement('editDiv');

		newdiv.setAttribute("id","editDiv");
		newdiv.className = "editWin";
		newdiv.innerHTML  = '<div style="text-align:right;" class="roomheader" onclick="toggleBox(\'editDiv\')"><img src="images/close.gif"></div>';		
		newdiv.innerHTML += '<div>&nbsp;</div>';
		newdiv.innerHTML += '<div><input type="checkbox" id="allowPM" onclick="updateUserSettings()"> '+lang46+'&nbsp;</div>';
		newdiv.innerHTML += '<div><input type="checkbox" id="viewMyCamID" onclick="updateUserSettings()"> '+lang47+'&nbsp;</div>';
		newdiv.innerHTML += '<div><input type="checkbox" id="entryExitID" onclick="updateUserSettings()"> '+lang48+'&nbsp;</div>';
		newdiv.innerHTML += '<div><input type="checkbox" id="soundsID" onclick="updateUserSettings()"> '+lang49+'&nbsp;</div>';
		newdiv.innerHTML += '<div><input type="checkbox" id="sfxID" onclick="updateUserSettings()"> '+lang50+'&nbsp;</div>';
		newdiv.innerHTML += '<div><input type="text" id="textScale" onblur="updateUserSettings()" value="' + textScale + '" style="width:30px;"> Text Scale (100=Normal)</div>';
		newdiv.innerHTML += '<div>&nbsp;</div>';
		newdiv.innerHTML += '<div>&nbsp;</div>';
		newdiv.innerHTML += '<div>&nbsp;'+lang51+': <select id="selectStatusID" onchange="sendStatus(this.value);"></select></div>';
		newdiv.innerHTML += '<div>&nbsp;</div>';

		ni.appendChild(newdiv);
	}
	else
	{
		document.getElementById("editDiv").style.visibility = 'visible';
	}

	createStatusSelectOptions();
}

/*
* update user settings
*
*/

function updateUserSettings()
{
	userRPM = document.getElementById('allowPM').checked;
	userRWebcam = document.getElementById('viewMyCamID').checked;
	userEntryExitSFX = document.getElementById('entryExitID').checked;
	userNewMessageSFX = document.getElementById('soundsID').checked;
	userSFX = document.getElementById('sfxID').checked;
    textScale = $("#textScale").val();
    if(!(parseInt(textScale) > 0)) {
        textScale = 100;
        $("#textScale").val(100);
    }
    $(".message-text-scale").css('font-size', (textScale / 100) + 'em');
	createCookie('myOptions',encodeURI(userRPM+"|"+userRWebcam+"|"+userEntryExitSFX+"|"+userNewMessageSFX+"|"+userSFX+'|'+textScale),30);
}

/*
* switch settings status
*
*/

function switchSettingsStatus(value,div)
{
    var newStatus = true;
	if(value == 'false' || value == false)
	{
		newStatus = false;
	}

	document.getElementById(div).checked = newStatus;
}

/*
* create menu div
*
*/

function createMdiv(ndiv,nWin)
{
	if(document.getElementById(ndiv))
	{
		var el = document.getElementById(ndiv);
		el.parentNode.removeChild(el);
	}

	if(!document.getElementById(ndiv))
	{
		document.getElementById(nWin).innerHTML  += '<div id="'+ndiv+'" class="'+ndiv+'"></div>';
	}
}

/*
* close menu div
*
*/

function closeMdiv(ndiv)
{
	var element = $("#" + ndiv);
	if(element.length > 0)
	{
		element.parent().empty().hide();
	}
}

/*
* create menu - (using all js array values)
* 
*/

var nClass = '';

function createMenu(inputMDiv,displayMDiv,ndiv,ntotal,nloop)
{
	var i=0;
	var iLoop = 1;

	document.getElementById(ndiv).innerHTML = '';

	document.getElementById(ndiv).innerHTML = '<div style="text-align:right;" class="roomheader" onclick=closeMdiv("'+ndiv+'")><img src="images/close.gif"></div>';

	if(ndiv == 'avatarsWin')
	{
		// create custom avatar upload div
		document.getElementById(ndiv).style.width = "320px";		
        if(moderator || admin) {
		var ni = document.getElementById(ndiv);
		var newdiv = document.createElement('iframe');
        newdiv.setAttribute("id","myAvatarUpload");
        newdiv.src = "avatars/upload.php";
        newdiv.height="140";
        newdiv.width="310";
        newdiv.frameBorder="0";

		ni.appendChild(newdiv);

		document.getElementById(ndiv).innerHTML += "<br/>";
		document.getElementById(ndiv).innerHTML += "OR, choose a default avatar below,";		
		document.getElementById(ndiv).innerHTML += "<br/><br/>";
        }
	}
	
	for (i = 0; i <= ntotal; i++)
	{
		if(ndiv == 'smiliesWin' && mySmilies[i])
		{
			document.getElementById(ndiv).innerHTML += '<span onclick=addsmiley("'+inputMDiv+'","'+mySmilies[i]+'");toggleBox("'+ndiv+'"); title="'+mySmilies[i]+'" alt="'+mySmilies[i]+'"/>'+mySmiliesImg[i]+'</span>';
		}

		if(ndiv == 'avatarsWin')
		{
			var showAvatar = 1;
		
			if(myAvatars[i] == 'pc.gif')
			{
				var showAvatar = 0;
			}
			
			if(myAvatars[i] == 'phone.gif')
			{
				var showAvatar = 0;
			}
			
			if(myAvatars[i] == '')
			{
				var showAvatar = 0;
			}			
			
			if(showAvatar)
			{
				document.getElementById(ndiv).innerHTML += '<span style="padding: 2px 2px 2px 2px;" onclick="addAvatar(\''+inputMDiv+'\',\''+myAvatars[i]+'\');updateAvatar(\''+uID+'\', \''+myAvatars[i]+'\');sendAvatarData();" /><img src="avatars/'+myAvatars[i]+'"></span>';
			}
		}

		if(ndiv == 'fontfamilyWin')
		{
			nClass = 'highliteOff';

			if(myFontFamily[i] == textFamily)
			{
				nClass = 'highliteOn';
			}

			document.getElementById(ndiv).innerHTML += '<div class="'+nClass+'" onmouseover="this.className=\'highliteOn\'" onmouseout="this.className=\'highliteOff\'" style="font-family:'+myFontFamily[i]+'" alt="'+myFontFamily[i]+'" title="'+myFontFamily[i]+'" onclick="addFontFamily(\''+myFontFamily[i]+'\');changeMessBoxStyle(\''+inputMDiv+'\');" />'+myFontFamily[i]+'</div>';

			nClass = '';
		}

		if(ndiv == 'fontsizeWin')
		{
			nClass = 'highliteOff';

			if(myFontSize[i].toLowerCase() == textSize.toLowerCase())
			{
				nClass = 'highliteOn';
			}

			if(mBold == 1)
			{
				document.getElementById(ndiv).style.fontWeight="900";	
			}

			if(mUnderline == 1)
			{
				document.getElementById(ndiv).style.textDecoration="underline";	
			}

			if(mItalic == 1)
			{
				document.getElementById(ndiv).style.fontStyle="italic";	
			}

			document.getElementById(ndiv).style.color = textColor;

			document.getElementById(ndiv).innerHTML += '<div class="'+nClass+'" onmouseover="this.className=\'highliteOn\'" onmouseout="this.className=\'highliteOff\'" style="font-size:'+myFontSize[i]+';" alt="'+myFontSize[i]+'" title="'+myFontSize[i]+'" onclick="addFontSize(\''+myFontSize[i]+'\');changeMessBoxStyle(\''+inputMDiv+'\');" />'+lang2+'</div>';

			nClass = '';
		}

		if(ndiv == 'colorsWin')
		{
			document.getElementById(ndiv).innerHTML += '<span style="padding: 2px 2px 2px 2px;background-color:'+myColor[i]+'" alt="'+myColor[i]+'" title="'+myColor[i]+'" onclick="addColor(\''+myColor[i]+'\');changeMessBoxStyle(\''+inputMDiv+'\');" />&nbsp;</span>';
		}

		if(ndiv == 'sFXWin')
		{
			document.getElementById(ndiv).innerHTML += '<div class="highliteOff" onmouseover="this.className=\'highliteOn\'" onmouseout="this.className=\'highliteOff\'"><img src="sounds/sfx/speaker.gif" onclick="doSound(\'sfx/'+mySFX[i]+'\');" alt="'+lang3+'" title="'+lang3+'">&nbsp;<span style="padding: 2px 2px 2px 2px;" onclick="addSFX(\''+inputMDiv+'\',\''+displayMDiv+'\',\''+mySFX[i].replace(/.mp3/i,"")+'\');"/>'+mySFX[i].replace(/.mp3/i,"")+'</span></div>';
		}

		if(iLoop >= nloop)
		{
			if(ndiv != 'fontfamilyWin' && ndiv != 'fontsizeWin' && ndiv != 'sFXWin')
			{
				document.getElementById(ndiv).innerHTML += '<br />';
			}

			iLoop = 0;

		}

		iLoop += 1;
	}

	if(ndiv == 'fontfamilyWin')
	{
		var isBoldChecked = '';
		var isUnderlineChecked = '';
		var isItalicChecked = '';

		if(mBold == 1)
		{
			isBoldChecked = 'checked';
		}

		document.getElementById(ndiv).innerHTML += '<span class="highliteOff" onmouseover="this.className=\'highliteOn\'" onmouseout="this.className=\'highliteOff\'" /><input type="checkbox" id="bold" onclick="addFontBold();changeMessBoxStyle(\''+inputMDiv+'\');" '+isBoldChecked+'><b>B</b></span>';

		if(mUnderline == 1)
		{
			isUnderlineChecked = 'checked';	
		}

		document.getElementById(ndiv).innerHTML += '<span class="highliteOff" onmouseover="this.className=\'highliteOn\'" onmouseout="this.className=\'highliteOff\'" /><input type="checkbox" id="underline" onclick="addFontUnderline();changeMessBoxStyle(\''+inputMDiv+'\');" '+isUnderlineChecked+'><u>U</u></span>';

		if(mItalic == 1)
		{
			isItalicChecked = 'checked';	
		}

		document.getElementById(ndiv).innerHTML += '<span class="highliteOff" onmouseover="this.className=\'highliteOn\'" onmouseout="this.className=\'highliteOff\'" /><input type="checkbox" id="italic" onclick="addFontItalic();changeMessBoxStyle(\''+inputMDiv+'\');" '+isItalicChecked+'><i>I</i></span>';
	}

}

/*
* update message box text style
*
*/

function changeMessBoxStyle(div)
{
	document.getElementById(div).style.color = textColor;
	document.getElementById(div).style.fontFamily = textFamily;
	document.getElementById(div).style.fontSize = textSize;

	document.getElementById(div).style.fontWeight= "normal";
		document.getElementById(div).style.fontStyle= "normal";
	document.getElementById(div).style.textDecoration = "none";

	if(mBold == 1)
	{
		document.getElementById(div).style.fontWeight= "bold";
	}

	if(mItalic == 1)
	{
		document.getElementById(div).style.fontStyle= "italic";
	}

	if(mUnderline == 1)
	{
		document.getElementById(div).style.textDecoration = "underline";
	}


}

/*
* add smilie to message 
*
*/

function addSmilie(nSmilie)
{
	for (i = 0; i <= totalSmilies; i++)
	{
		nSmilie = nSmilie.split(mySmilies[i]).join(mySmiliesImg[i]);
	}

	return nSmilie;
}

/*
* add smilie to messagebar 
*
*/

function addsmiley(inputMDiv,code)
{
	var pretext = document.getElementById(inputMDiv).value;
	this.code = code;
	document.getElementById(inputMDiv).focus();
	document.getElementById(inputMDiv).value = pretext + code;
}

/*
* update users avatar 
*
*/

function addAvatar(inputMDiv,nAvatar)
{
	// update avatar
	userAvatar = nAvatar;

	// close avatar window
	toggleBox("avatarsWin");

	// focus message input
	document.getElementById(inputMDiv).focus();
}

/*
* play SFX 
*
*/

function addSFX(inputMDiv,displayMDiv,sfx)
{
	// clear message input
	document.getElementById(inputMDiv).value = '';

	// add SFX to message input
	document.getElementById(inputMDiv).value = '/play '+sfx;

	// send message
	addMessage(inputMDiv,displayMDiv);

	// close SFX window
	toggleBox('sFXWin');
		
}

/*
* update selected Font Color
*
*/

function addColor(nColor)
{
	// update avatar
	textColor = nColor;

	// update text sample window
	document.getElementById("fontsizeWin").style.color=nColor;
}

/*
* update selected Font Family
*
*/

function addFontFamily(nFont)
{
	// update font family
	textFamily = nFont;

	// update text sample window
	document.getElementById("fontsizeWin").style.fontFamily=nFont;
}

/*
* update selected Font Size
*
*/

function addFontSize(nSize)
{
	// update font size
	textSize = nSize;
}
 
/*
* update Bold for user text 
*
*/

function addFontBold()
{
	if(mBold == 0)
	{
		mBold = 1;

		// update text sample window
		document.getElementById("fontsizeWin").style.fontWeight="900";
	}
	else
	{
		mBold = 0;

		// update text sample window
		document.getElementById("fontsizeWin").style.fontWeight="normal";
	}

}

/*
* update Underline for user text 
*
*/

function addFontUnderline()
{
	if(mUnderline == 0)
	{
		mUnderline = 1;

		// update text sample window
		document.getElementById("fontsizeWin").style.textDecoration="underline";
	}
	else
	{
		mUnderline = 0;

		// update text sample window
		document.getElementById("fontsizeWin").style.textDecoration="none";
	}

}

/*
* update Italic for user text 
*
*/

function addFontItalic()
{
	if(mItalic == 0)
	{
		mItalic = 1;

		// update text sample window
		document.getElementById("fontsizeWin").style.fontStyle="italic";
	}
	else
	{
		mItalic = 0;

		// update text sample window
		document.getElementById("fontsizeWin").style.fontStyle="normal";
	}

}

/*
* flood control
*
*/

var lastPost = 1;

function floodControl()
{
	lastPost++;	
}

setInterval('floodControl();',1000);

/*
* logout user
*
*/

function logout(id)
{
	window.location.replace("index.php?" + roomText + "logout");
}

/*
* create status select list
*
*/

function createStatusSelectOptions()
{
	var sel = document.getElementById('selectStatusID');

	for (var i = 0; i < userStatusMes.length; i++)
	{
		if(!document.getElementById("selectStatusID_"+i))
		{
			var opt = document.createElement("option");
			opt.setAttribute("id","selectStatusID_"+i);
			opt.value = i;
			opt.text = decodeURI(userStatusMes[i]);

			if(opt.value == '1')
			{
				opt.setAttribute('selected','selected');	
			}

  			try 
			{
				// standards compliant; doesn't work in IE
    			sel.add(opt, null); 
  			}
  			catch(ex)
			{
				// IE only
    			sel.add(opt);
  			}

		}

	}

}

/*
* update status
*
*/

function sendStatus(status)
{
	var param = '?';
	param += '&status=' + encodeURI(status);
	param += '&uid=' + uID;

	// if ready to send message to DB
	if (sendReq.readyState == 4 || sendReq.readyState == 0) 
	{
		sendReq.open("POST", 'includes/sendData.php?rnd='+ Math.random(), true);
		sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sendReq.onreadystatechange = handleSendChat;
		sendReq.send(param);
	}

}

/*
* send avatar to database
*
*/

function sendAvatarData()
{
	var param = '?';
	param += '&uavatar=' + encodeURI(userAvatar);	

	// if ready to send message to DB
	if (sendReq.readyState == 4 || sendReq.readyState == 0) 
	{
		sendReq.open("POST", 'includes/sendData.php?rnd='+ Math.random(), true);
		sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sendReq.onreadystatechange = handleSendChat;
		sendReq.send(param);
	}

}

/*
* block/unblock user
*
*/

function blockUsers(i,id)
{
	if(i=='block')
	{
		blockedList = blockedList + "|"+id+"|";
	}

	if(i=='unblock')
	{
		blockedList = blockedList.replace("|"+id+"|","");
	}

	var param = '?';
	param += '&myBlockList=' + encodeURI(blockedList);	

	// if ready to send message to DB
	if (sendReq.readyState == 4 || sendReq.readyState == 0) 
	{
		sendReq.open("POST", 'includes/sendData.php?rnd='+ Math.random(), true);
		sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sendReq.onreadystatechange = handleSendChat;
		sendReq.send(param);
	}
}

/*
* show/hide password field
* used on login page
*/

var y = 1;
function toggleLoginPass()
{
    var state;
	if(y)
	{
		state = 'hidden';
		y = 0;	
	}
	else
	{
		state = 'visible';
		y = 1;	
	}

	document.getElementById("pass").style.visibility = state;
	document.getElementById("lostpass").style.visibility = state;
}

/*
* open new window
*
*/

function newWin(url)
{
	window.open(url,'','');
}

/*
* clear screen
*
*/

function clrScreen()
{
	document.getElementById("chatContainer").innerHTML = '';
}

/*
* show info box
* showInfoBox("div name","height","width","top","url to file","text to display")
* example: showInfoBox("lost","200","300","200","templates/default/lost.php","");
* example: showInfoBox("system","200","300","200","","some text goes here");
*/

function showInfoBox(info,height,width,top,url,txt)
{
	// delete div if exists
	if(document.getElementById('oInfo'))
	{
		closeMdiv(info);
	}

	// create div
	var ni = document.getElementById('oInfo');

	if(url)
	{
		var newdiv = document.createElement('iframe');
		newdiv.frameBorder="0";
		newdiv.src = url;
	}
	
	if(info == 'games')
	{
		var newdiv = document.createElement('div');
		newdiv.innerHTML  = "<div class=\"userInfoTitle\" style=\"cursor:move;\"><b>"+lang61+"</b><span style='float:right;cursor:pointer;'><img src='images/close.gif' onclick='closeMdiv(\""+info+"\");'></span></div>";
		newdiv.innerHTML += "<div><iframe style='border:0;' src='"+url+"' width='"+(width)+"' height='"+(height-40)+"'></div>";	
	}
	
	if(info == 'shareFiles')
	{
		var newdiv = document.createElement('div');
		newdiv.innerHTML  = "<div class=\"userInfoTitle\" style=\"cursor:move;\"><b>"+lang62+"</b><span style='float:right;cursor:pointer;'><img src='images/close.gif' onclick='closeMdiv(\""+info+"\");'></span></div>";
		newdiv.innerHTML += "<div><iframe style='border:0;' src='"+url+"' width='"+(width)+"' height='"+(height-36)+"'></div>";	
	}

	if(info == 'viewTranscripts')
	{
		var newdiv = document.createElement('div');
		newdiv.innerHTML  = "<div class=\"userInfoTitle\" style=\"cursor:move;\"><b>Transcripts</b><span style='float:right;cursor:pointer;'><img src='images/close.gif' onclick='closeMdiv(\""+info+"\");'></span></div>";
		newdiv.innerHTML += "<div><iframe style='border:0;' src='"+url+"' width='"+(width)+"' height='"+(height-36)+"'></div>";	
	}	

	if(txt)
	{
		var newdiv = document.createElement('div');
		newdiv.innerHTML  = "<div class=\"userInfoTitle\" style=\"padding-top:3px;cursor:move;\"><b>"+lang4+"</b><span style='float:right;cursor:pointer;'><img src='images/close.gif' onclick='closeMdiv(\""+info+"\");'></span></div>";
		newdiv.innerHTML += "<div style=\"min-height:135px;padding-top:10px;\">"+txt+"</div>";
		newdiv.innerHTML += "<div><input class=\"button\" type=\"button\" name=\"close\" value=\"Close Window\" onclick='closeMdiv(\""+info+"\");'></div>";
	}

	newdiv.setAttribute("id",info);
	newdiv.className = "innerInfo";
	newdiv.style.height = height+"px";
	newdiv.style.width = width+"px";
	//newdiv.style.top = top+"px";
	ni.style.height = height+"px";
	ni.style.width = width+"px";
	ni.style.top = top+"px";
	ni.style.left = (($(document).width()/2) - (parseInt(width) / 2))+'px';


	if(info == 'games' || info == 'shareFiles')
	{
		newdiv.style.overflow = "hidden";	
	}
	
	ni.appendChild(newdiv);

	//document.getElementById("oInfo").style.visibility = "visible";
	//document.getElementById(info).style.visibility = "visible";

	$("#" + info).show();
	$( "#oInfo" ).draggable({
		containment: 'window'
	}).show();
}
	
/*
* copyright
*
*/

function copyRight()
{
	var html = '';
		html += '<div style="text-align:left;padding-left:15px;padding-top:5px;padding-bottom:5px;">';
		html += 'Software: Text &amp; Audio/Video Chat Rooms<br>';
		html += 'Version: '+version+'<br>';
		html += 'Developers: Pro Chat Rooms<br><br>';
		html += 'Visit: <a href="http://prochatrooms.com" target="_blank">http://prochatrooms.com</a><br>';
		html += 'Support: <a href="http://support.prochatrooms.com" target="_blank">http://support.prochatrooms.com</a><br><br>';
		html += '&copy;Copyright 2007-'+new Date().getFullYear()+' All Rights Reserved.';
		html += '</div>';

	return html;
}

String.prototype.replaceAll = function (find, replace) {
    return this.replace(new RegExp(find, 'g'), replace);
}

String.prototype.stripNonAlpha = function() {
    return this.replace(/[^\w]+/g, '');
}

$.fn.textWidth = function(){
    var html_org = $(this).html();
    var html_calc = '<span>' + html_org + '</span>';
    $(this).html(html_calc);
    var width = $(this).find('span:first').width();
    $(this).html(html_org);
    return width;
};
var isScrolling = false;

var wantonWicked =  {
    difference: 0,
    serverTime: 0
};

$(function() {
    $(document)
        .on('mouseenter', '.scrollable', startScroll)
        .on('mouseleave', '.scrollable', endScroll)
        .on('click', '.scrollable', endScroll);
    $(document)
        .on('dblclick', '.userlist', openPmWindow);

    $(document)
        .on('click', '.chat-viewable', function() {
            $("#sub-panel").load($(this).attr('href'), function() {
                $(this).dialog({
                    width: 550,
                    height: 400,
                    title: 'View Detail'
                });
            });
            return false;
        });

    $("#toggle-userlist").click(function() {
        $("#rightContainer").toggle();
        if($("#rightContainer").css("display") == 'none') {
            $("#chatContainer").width($("#chatContainer").width() + 236);
            $("#optionsContainer").width($("#optionsContainer").width() + 236);
        }
        else {
            $("#chatContainer").width($("#chatContainer").width() - 236);
            $("#optionsContainer").width($("#optionsContainer").width() - 236);
        }
    });

    wantonWickedTime.runClock('#server-time');

	$("#user-tool-menu").menu();

    // patch for Firefox. Not sure why it's doing this.
    $('#userContainer').addClass('userContainer');
});

function startScroll() {
    var offsetAmount = (parseInt($(this).textWidth()) + 0) - parseInt($(this).parent('div').width());
    if((offsetAmount > 0) && (!isScrolling)){
        isScrolling = true;
        scrollDiv(-offsetAmount, $(this), 1000);
    }
}

function scrollDiv(offsetAmount, element, delay) {
    var offset = 0;
    if(offsetAmount < 0) {
        offset = offsetAmount
    }

    $(element).animate({
        left: offset
    },
    delay,
    function() {
        if(isScrolling) {
            scrollDiv(-offsetAmount, element, delay);
        }
        else {
            if(offset != 0) {
                scrollDiv(0, element, 0);
            }
        }
    });
}

function endScroll() {
    isScrolling = false;
    $(this).stop(true, true);
}

function openPmWindow() {
    //alert($(this).html());
    //createPChatDiv(userName,uUser,uuID,uID);
}
var iWarning = 0;

function doIntellibot(ursMessage, itoUserName)
{
	var iResponse = '';

	ursMessage = ursMessage.toLowerCase();

	// welcome messages

	var i

	for(i=0;i<uEntryResponse.length;i++)
	{
		if(ursMessage.search(uEntryResponse[i]) != '-1')
		{
			iResponse = iEntryResponse[Math.floor(Math.random()*iEntryResponse.length)];
		}
	}

	// exit messages
	for(i=0;i<uExitResponse.length;i++)
	{
		if(ursMessage.search(uExitResponse[i]) != '-1')
		{
			iResponse = iExitResponse[Math.floor(Math.random()*iExitResponse.length)];
		}

	}

	// help messages
	for(i=0;i<uHelpResponse.length;i++)
	{
		if(ursMessage.search(uHelpResponse[i]) != '-1')
		{
			iResponse = iHelpResponse[Math.floor(Math.random()*iHelpResponse.length)];
		}

	}

	// warning messages
	if (ursMessage.indexOf("****") != -1)
	{
		iResponse = lang5;

		iWarning += 1;
	}

	// intellibot response

	if(iResponse)
	{
		var toName = '';

		var iMessage = intelliBotAvi+"|"+stextColor+"|"+stextSize+"|"+stextFamily+"|"+iResponse;

		if(iWarning == 2)
		{
			createMessageDiv('0', '-1', 'chatContainer', showMessages+1, 'SILENCE', 'beep_high.mp3', intelliBotName, itoUserName, '');
		}

		if(iWarning >= 3)
		{
			createMessageDiv('0', '-1', 'chatContainer', showMessages+1, 'KICK', 'beep_high.mp3', intelliBotName, itoUserName, '');
		}

		sendIntellibotMessage('chatContainer','',iMessage);
	}

}

/*
* send intellibot message
*
*/

// define XmlHttpRequest
var sendBotReq = getXmlHttpRequestObject();

function sendIntellibotMessage(div,itoUserName,iMessage)
{
	var param = '?';

	param += '&uid=-1';
	param += '&umid='+div;
	param += '&uroom=' + roomID;
	param += '&toname=' + encodeURI(itoUserName);
	param += '&umessage=' + encodeURIComponent(iMessage);
	param += '&usfx=' + escape('beep_high.mp3');	

	// if ready to send message to DB
	if (sendBotReq.readyState == 4 || sendBotReq.readyState == 0) 
	{
		sendBotReq.open("POST", 'includes/sendData.php?rnd='+ Math.random(), true);
		sendBotReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sendBotReq.onreadystatechange = handleSendChat;
		sendBotReq.send(param);
	}

}/*
* user
* welcome messages
*/

var uEntryResponse = [];

uEntryResponse[0]="hey"; 
uEntryResponse[1]="hello";

/*
* intellibot
* welcome responses
*/

var iEntryResponse = [];

iEntryResponse[0]="hey"; 
iEntryResponse[1]="hello";
iEntryResponse[2]="hey there";
iEntryResponse[2]="welcome";

/*
* user
* goodbye responses
*/

var uExitResponse = [];

uExitResponse[0]="bye"; 
uExitResponse[1]="see yer";
uExitResponse[2]="outta here";

/*
* intellibot
* goodbye responses
*/

var iExitResponse = [];

iExitResponse[0]="bye"; 
iExitResponse[1]="safe journey!";
iExitResponse[2]="thanks for visiting";

/*
* user
* help responses
*/

var uHelpResponse = [];

uHelpResponse[0]="help"; 
uHelpResponse[1]="question"; 
uHelpResponse[2]="assist"; 

/*
* intellibot
* help responses
*/

var iHelpResponse = [];

iHelpResponse[0]="If you have a question about this chat room software, please visit http://prochatrooms.com or email sales@prochatrooms.com for assistance."; 
/*
* add message
*
*/

var message;
var isPrivate = '';
var hideAdmins = 1;
var autoApprove = 1;
var lastMessageTxt = '';

function addMessage(inputMDiv,displayMDiv)
{
	if(eCredits == '1' && displayMDiv.search(/pcontent_/i) != '-1')
	{
		var eCreditCount = document.getElementById('eCreditsID').innerHTML;

		if(eCreditCount <= 0)
		{
			/* create messages */
			var eCreditMessageA = "You have 0 eCredits left. Please purchase more eCredits to continue this private conversation.";
			var eCreditMessageB = " has 0 eCredits left.";
		
			/* show sender 'you have no ecredits' */
			message = "../images/notice.gif|"+getSafeColor(stextColor, defaultColor)+"|"+stextSize+"|"+stextFamily+"|"+eCreditMessageA+"|1";
			createMessageDiv('1',uID,displayMDiv,showMessages+1,message,'beep_high.mp3','','');
			
			/* show receiver that 'sender has no ecredits' */			
			message = "../images/notice.gif|"+getSafeColor(stextColor, defaultColor)+"|"+stextSize+"|"+stextFamily+"|"+eCreditMessageB+"|1";
            createMessageDiv('1',uID,displayMDiv,showMessages+1,message,'beep_high.mp3','','');

			/* send data */
			sendData(displayMDiv);

			/* clear message input field */
			clrMessageInput(inputMDiv);
			return false;
		}
	}
	
	if(groupChat == 0)
	{
		showInfoBox("system","220","300","200","",lang6);
		return false;		
	}

	if(groupPChat == 0 && document.getElementById('whisperID').value != '')
	{
		showInfoBox("system","220","300","200","",lang6);
		return false;		
	}

	if(isSilenced == 1)
	{
		showInfoBox("system","220","300","200","",lang7+" "+silent+" "+lang8);
		return false;
	}

	if(moderatedChat == 1)
	{
		autoApprove = 0;

		if(admin || moderator || speaker)
		{
			autoApprove = 1;	
		}

		if(autoApprove == 0)
		{
			showInfoBox("system","220","300","200","",lang9);
		}
	}

	// if user is trying to flood the chat room
	if(lastPost < floodChat)
	{
		showInfoBox("system","220","300","200","",lang10);
		return false;
	}
	
	// default sfx
	sfx = 'beep_high.mp3';

	// get user message
	message = document.getElementById(inputMDiv).value;
	var wrapMessageWithFormat = true;

	message = message.replace(/&/gi,"&amp;");
	message = message.replace(/</gi,"&#60;");
	message = message.replace(/>/gi,"&#62;");
	message = message.replace(/`/gi,"&#96;");
	message = message.replace(/'/gi,"&#39;");
	message = message.replace(/"/gi,"&#34;");
	message = message.replace(/%/gi,"&#37;");
	message = message.replace(/\|/gi,"&#127;");

	// if usergroup cannot post videos
	if(groupVideo == 0 && message.indexOf("http://youtu.be/") != -1)
	{
		showInfoBox("system","220","300","200","",lang6);
		return false;		
	}

	// check message length
	if(message.length > maxTextLength)
	{
		var maxChars  = "Your message contains <span style='color:red;'>"+message.length+"</span> characters, please shorten your message.";
		    maxChars += "<br><br>";				
		    maxChars += "Max Allowed Characters: <span style='color:green;'><?php echo $CONFIG['maxChars'];?></span>";
		
		showInfoBox("system","220","300","200","",maxChars);
		return false;
	}
	
	// anti spam filter for repeated messages
	if((admin != 1) && (moderator != 1) && (message == lastMessageTxt))
	{
		var antiSpam  = "Sorry, your message has been marked as spam and will not be sent. Please do not send the same message repeatedly.";
			
		showInfoBox("system","220","300","200","",antiSpam);
		return false;
	}	

	lastMessageTxt = message;
	
	// filter badwords in message
	message = filterBadword(message);	

	// check whisper contains a message
	if(message.replace(/\s/g,"") == "")
	{
		showInfoBox("system","220","300","200","",lang11);
		return false;
	}	

	var mStatus = 0;
	var iRC = '';

	// IRC commands
	var ircCommand = message.split(" ");

	// IRC action
	if((ircCommand[0] == '/me') || (ircCommand[0] == '/a'))
	{
		message  = " " + message.slice(ircCommand[0].length+1);
		if(!message.match(/[\w]/)) {
			showInfoBox('message', '220', '300', '200', '', 'No action specified');
			return false;
		}
		iRC = '1';
	}

	// IRC broadcast
	if(ircCommand[0] == '/broadcast')
	{
		if(admin || moderator)
		{
			sfx = 'beep_high.mp3';

			message  = "/BROADCAST " + encodeURI(message.slice(ircCommand[0].length));

			iRC = '1';
            wrapMessageWithFormat = false;
		}
		else
		{
			showInfoBox("system","220","300","200","",lang45);
			return false;
		}
	}

	// IRC ringbell
	if(ircCommand[0] == '/ringbell')
	{
		sfx = 'ringbell.mp3';

		message  = message.slice(ircCommand[0].length+1) + " "+lang12+" ... ";

		iRC = '1';
	}

	// IRC sfx
	if(ircCommand[0] == '/play')
	{
		// check /play contains a SFX 
		if(!ircCommand[1] || ircCommand[1].replace(/\s/g,"") == "")
		{
			showInfoBox("system","220","300","200","",lang13);
			return false;
		}

		// check the SFX exists
		// convert SFX array to string then search string for match
		if(mySFX.toString().lastIndexOf(ircCommand[1]+".mp3") == -1)
		{
			showInfoBox("system","200","300","200","",lang14);

			// display SFX window
			// now user can choose from list
			createSFX();toggleBox('sFXWin');

			return false;
		}

		sfx = "sfx/"+ircCommand[1]+".mp3";

		message  = "** "+message.slice(ircCommand[0].length+1)+" **";

	}

    // IRC madness
    if(ircCommand[0] == '/madness') {
        var madnesses = ['wakka wakka!', 'oggity boogity!'];
        var index = Math.floor(madnesses.length * Math.random(),0);
        sfx = "sfx/evil.mp3";
        message  = "** "+ madnesses[index] +" **";
    }

    if(ircCommand[0] == '/request') {
        // send to the request module
        request.handle(message);
        clrMessageInput(inputMDiv);
        return false;
    }

    if(ircCommand[0] == '/nick') {
        nick.change(message);
        clrMessageInput(inputMDiv);
        return false;
    }

    if(ircCommand[0] == '/dice') {
        if(ircCommand.length == 1) {
            dice.help();
            return false;
        }
        if(ircCommand[1].toLowerCase() == 'roll') {
            dice.roll(message, displayMDiv);
            clrMessageInput(inputMDiv);
            return false;
        }
        else if(ircCommand[1].toLowerCase() == 'list') {
            dice.listRolls();
            clrMessageInput(inputMDiv);
            return false;
        }
        else if(ircCommand[1].toLowerCase() == 'help') {
            dice.help();
            clrMessageInput(inputMDiv);
            return false;
        }
    }

    if(ircCommand[0] == '/roll') {
        if(ircCommand.length == 1) {
            dice.help();
            return false;
        }
        if(ircCommand[1].toLowerCase() == 'dice') {
            dice.roll(message, displayMDiv);
            clrMessageInput(inputMDiv);
            return false;
        }
        else if(ircCommand[1].toLowerCase() == 'list') {
            dice.listRolls();
            clrMessageInput(inputMDiv);
            return false;
        }
        else if(ircCommand[1].toLowerCase() == 'help') {
            dice.help();
            clrMessageInput(inputMDiv);
            return false;
        }
    }

    if(ircCommand[0] == '/ghost') {
        if(ircCommand.length == 1) {
            ghost.help();
            return false;
        }

        if(ircCommand[1].toLowerCase() == 'off') {
            ghost.off(inputMDiv);
            clrMessageInput(inputMDiv);
            return false;
        }
        else if(ircCommand[1].toLowerCase() == 'on') {
            ghost.on(inputMDiv);
            clrMessageInput(inputMDiv);
            return false;
        }
        else {
            ghost.help();
            return false;
        }
    }

    if((ircCommand[0] == '/scene') || (ircCommand[0] == '/scenes')) {
        if(ircCommand.length == 1) {
            scenes.help();
            return false;
        }

        if(ircCommand[1].toLowerCase() == 'list') {
            scenes.list();
            clrMessageInput(inputMDiv);
            return false;
        }
        else if(ircCommand[1].toLowerCase() == 'add') {
            scenes.add();
            clrMessageInput(inputMDiv);
            return false;
        }
        else {
            scenes.help();
            return false;
        }
    }

    if(wrapMessageWithFormat) {
        // add bold font
        if(mBold == 1)
        {
            message = "[b]"+message+" [/b]";
        }

        // add italic font
        if(mItalic == 1)
        {
            message = "[i]"+message+" [/i]";
        }

        // add italic font
        if(mUnderline == 1)
        {
            message = "[u]"+message+" [/u]";
        }
    }

	// search message for line breaks
	var addLineBreaks = 0;
	if(message.search(/(\r\n|\n|\r)/gm) != '-1')
	{
		addLineBreaks = 1;
	}

	// IRC whisper
	if(document.getElementById('whisperID').value != '')
	{
		isPrivate = document.getElementById('whisperID').value;

		sfx = 'beep_high.mp3';

		message  = " &#187; " + encodeURI(isPrivate) + ": " + encodeURI(message);

		iRC = '1';
	}
	
	message = userAvatar+"|"+getSafeColor(textColor, defaultColor)+"|"+textSize+"|"+textFamily+"|"+message+"|"+iRC+"|"+addLineBreaks;

	// update users text style (cookie)
	createCookie('myTextStyle',encodeURI(mBold+"|"+mItalic+"|"+mUnderline+"|"+textColor+"|"+textSize+"|"+textFamily),30);

	// send data to database
	sendData(displayMDiv);

	// create message
	if(autoApprove)
	{
		createMessageDiv(mStatus, uID, displayMDiv, showMessages+1, message, sfx, userName, '',(new Date().getTime()/1000));
	}

	// clear message input field
	clrMessageInput(inputMDiv);

	// restart flood counter
	lastPost = 1;
	
	// create bot reponse
	if(intelliBot == 1 && Number(intellibotRoomID) == Number(roomID) && displayMDiv == 'chatContainer')
	{
		doIntellibot(message,userName);
	}	

}

/*
* send message to database
*
*/

// define XmlHttpRequest
var sendReq = getXmlHttpRequestObject();

function sendData(displayMDiv)
{
	message = message.replace(/\+/gi,"&#43;");

	var param = '?';

	param += '&uid=' + uID;
	param += '&umid=' + displayMDiv;
	param += '&uroom=' + roomID;
	param += '&uname=' + encodeURI(userName);
	param += '&to_user_id=' + encodeURI(isPrivate);
	param += '&umessage=' + encodeURIComponent(message);
	param += '&usfx=' + escape(sfx);	

	// if ready to send message to DB
	if (sendReq.readyState == 4 || sendReq.readyState == 0) 
	{
		sendReq.open("POST", 'includes/sendData.php?rnd='+ Math.random(), true);
		sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sendReq.onreadystatechange = handleSendChat;
		sendReq.send(param);
	}

	// reset isPrivate 
	isPrivate = '';

}

/*
* send avatar to database
*
*/

function sendAvatarData()
{
	var param = '?';

	param += '&uname=' + encodeURI(userName);
	param += '&uavatar=' + escape(userAvatar);	

	// if ready to send message to DB
	if (sendReq.readyState == 4 || sendReq.readyState == 0) 
	{
		sendReq.open("POST", 'includes/sendData.php?rnd='+ Math.random(), true);
		sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sendReq.onreadystatechange = handleSendChat;
		sendReq.send(param);
	}

}

function handleSendChat()
{
	// empty
}

/*
* get message from database
*
*/

// define XmlHttpRequest
var receiveMesReq = getXmlHttpRequestObject();
var showHistory = 1;

// gets the messages
function getMessages() 
{
	var singleRoom = '';

	if(totalRooms == '1')
	{
		singleRoom = roomID;
	}

	/*if (receiveMesReq.readyState == 4 || receiveMesReq.readyState == 0)
	{*/
		receiveMesReq.open("GET", 'includes/getData.php?roomID='+roomID+'&u='+uID+'&history='+showHistory+'&last='+lastMessageID+'&s='+singleRoom+'&rnd='+ Math.random(), true);
		receiveMesReq.onreadystatechange = handleMessages;
		receiveMesReq.send(null);
	//}
			
}

// function for handling the messages

var mTimer = setInterval('getMessages();',refreshRate);
var xmlError = 0;
var roomNameStr;
var eCredits = 0;
var moderatedChat = 0;

function handleMessages() 
{
	if (receiveMesReq.readyState == 4) 
	{
        var xmldoc = receiveMesReq.responseXML;

        if(xmldoc == null)
		{
			if(xmlError < 5)
			{
				if(xmlError == 3)
				{
					// if error, alert user and try to reconnect to database
					showInfoBox("system","200","300","200","",lang16);
				}

				// update the error count
				xmlError += 1;

				// lets try and get the data again
				getMessages();
				return false;
			}
			else
			{
				// oops, connection has now failed 3 times
				// this could be for the following reasons,
				// a) incorrect data file name
				// b) database not responding
				// c) server under too much load to respond in time
				showInfoBox("system","220","300","200","",lang17);
				return false;
			}
		}
        xmlError = 0;

        if(xmldoc.getElementsByTagName('redirect').length > 0) {
            document.location = '/chat/index.php?roomID=' + xmldoc.getElementsByTagName('redirect')[0].childNodes[0].nodeValue;
        }

		// userroom data
		roomNameStr = '';
		var i;
        var roomList = [];
		for (i = 0; i < xmldoc.getElementsByTagName("userrooms").length;)
		{
			// for each room
			var userRooms = xmldoc.getElementsByTagName("userrooms")[i].childNodes[0].nodeValue;
			
			// split message data
			var userRoomsArray = userRooms.split("||");

			// moderated chat
			moderatedChat = userRoomsArray[8];

			createSelectRoomdiv(
						userRoomsArray[2], // name
						userRoomsArray[1], // id
						userRoomsArray[7] // delete
					);

			createRoomsdiv(
						userRoomsArray[2], // name
						userRoomsArray[1], // id
                        userRoomsArray[6] // icon name
					);
            roomList.push(userRoomsArray[1]);

			// create room name str
			roomNameStr = roomNameStr + "|" + userRoomsArray[2].toLowerCase() + "|";

			// if room is deleted, remove from userlist and select box
			if(userRoomsArray[7] == Number(1))
			{
				deleteDiv("select_"+userRoomsArray[1],'roomSelect');

				removeRoomsDiv("room_"+userRoomsArray[1]);

				roomNameStr = roomNameStr.replace("|" + userRoomsArray[2].toLowerCase() + "|","");
			}

			// update room users count
			if(document.getElementById("userCount_"+userRoomsArray[1]))
			{
				document.getElementById("userCount_"+userRoomsArray[1]).innerHTML = 0;
			}	
			
			// loop
			i++;
		}

        // remove unused rooms
        $("#userContainer").find(".roomheader").each(function(index, element) {
            var id = $(element).parent('div').attr('id');
            var roomId = id.substr(5);
            if(roomList.indexOf(roomId) == -1) {
                deleteDiv("select_"+roomId,'roomSelect');
                removeRoomsDiv("room_"+roomId);
                var roomName = $(".roomname", element).text();
                roomNameStr = roomNameStr.replace("|" + roomName.toLowerCase() + "|","");
            }
        });

        // sort rooms
		
		// userlist data
		if(intelliBot == 1)
		{
			// if single room mode, add intellibot to each room
			if(totalRooms == '1')
			{
				intellibotRoomID = roomID;
			}

			createUsersDiv('-1', '-1', intelliBotName, intelliBotAvi, '0', intellibotRoomID, intellibotRoomID, '1','','','',1,0);
		}
		
		for (var i = 0; i < xmldoc.getElementsByTagName("userlist").length;)
		{
			// for each room
			var userList = xmldoc.getElementsByTagName("userlist")[i].childNodes[0].nodeValue;
			
			// split message data
			var userListArray = userList.split("||");
	
			if(uID == userListArray[0])
			{
				groupCams = userListArray[16];
				groupWatch = userListArray[17];
				groupChat = userListArray[18];
				groupPChat = userListArray[19];
				groupRooms = userListArray[20];
				groupVideo = userListArray[21];
			}

			// enable eCredits
			eCredits = userListArray[13];

			// update eCredits total
			if(document.getElementById("eCreditsID") && userListArray[0] == uID)
			{
				document.getElementById("eCreditsID").innerHTML = userListArray[14];
			}

			if(eCredits == 0)
			{
				document.getElementById("iconeCredits").style.visibility = 'hidden';
			}

			if(uID == userListArray[0])
			{
				admin = Number(userListArray[8]);
				moderator = Number(userListArray[9]);
				speaker = Number(userListArray[10]);
                userTypeId = Number(userListArray[25]);
			}			
			
			// all users
			createUsersDiv(
						userListArray[0], // id
						userListArray[1], // userid (cms)
						userListArray[2], // username
                        userListArray[3], // display name
						userListArray[4], // icon
						userListArray[5], // webcam
						userListArray[7], // prev room
						userListArray[6], // room
						userListArray[11], // activity
						userListArray[12], // status
						userListArray[13], // watching
						userListArray[8], // admin
						userListArray[9], // moderator
						userListArray[10], // speaker
						userListArray[22], // active time
						userListArray[23], // last active time
						userListArray[24], // ip address
                        userListArray[25], // usertype
                        userListArray[26] // invisible
				);

			// loop
			i++;
		}

        $("#userContainer")
            .children('div')
                .each(function(count, item) {
                    // for each room
                    $(".userlist", item).sortElements(function(a, b) {
                        var aText = $(a).find('.username').text().toUpperCase();
                        var bText = $(b).find('.username').text().toUpperCase();
                        if($(a).find('.username').parent('span').hasClass('user-storyteller') && !$(b).find('.username').parent('span').hasClass('user-storyteller')) {
                            return -1;
                        }
                        if(!$(a).find('.username').parent('span').hasClass('user-storyteller') && $(b).find('.username').parent('span').hasClass('user-storyteller')) {
                            return 1;
                        }
                        return aText.localeCompare(bText);
                    });
            })
            .sortElements(function(a, b) {
                var aText = $(a).find('.roomname').text().toUpperCase();
                var bText = $(b).find('.roomname').text().toUpperCase();
                if(aText == 'LOBBY') {
                    return -1;
                }
                if(aText == 'ST LOUNGE') {
                    if(bText == 'LOBBY') {
                        return 1;
                    }
                    else {
                        return -1;
                    }
                }
                if(bText == 'LOBBY') {
                    return 1;
                }
                if(bText == 'ST LOUNGE') {
                    if(aText == 'LOBBY') {
                        return -1;
                    }
                    else {
                        return 1;
                    }
                }
                return aText.localeCompare(bText);
            })
        ;
		
		// message data
		for (var i = 0; i < xmldoc.getElementsByTagName("usermessage").length;)
		{
			// for each message
			var userMessage = xmldoc.getElementsByTagName("usermessage")[i].childNodes[0].nodeValue;	

			if(typeof(xmldoc.getElementsByTagName("usermessage")[i].textContent) != "undefined")
			{
				// firefox has a node limit of 4096 characters, so we 
				// use textContent.length instead to get the user message
				userMessage = xmldoc.getElementsByTagName("usermessage")[i].textContent;
			}

			// split message data
			var userMessageArray = userMessage.split("}{");
			
			// create message 
			createMessageDiv(
						'0', 
						userMessageArray[1], 
						userMessageArray[2], 
						showMessages+1, 
						userMessageArray[5], 
						userMessageArray[7], // FIX by JeffV
						userMessageArray[3],
						userMessageArray[4],
						userMessageArray[8] // Guess by JeffV
					);

			lastMessageID = userMessageArray[0];	

			// loop
			i++;			
		}
			
		if(showHistory == 1)
		{
			createMessageDiv(
							'0',
							uID,
							displayMDiv,
							-2,
							'entry.png|'+stextColor+'|'+stextSize+'|'+stextFamily+'|'+publicEntry,
							'beep_high.mp3',
							userName,
							'',
							new Date().getTime()/1000
						);

			showHistory = 0;
		}
	}
}

/*
* whisper user
*
*/

function whisperUser(touserName)
{
	// check if user is whispering to themselves :P
	if(touserName.toLowerCase() == decodeURI(userName.toLowerCase()))
	{
		showInfoBox("system","220","300","200","",lang18);
		return false;
	}

	// set message input
	document.getElementById('whisperID').value = decodeURI(touserName);
}

/*
* ring bell
*
*/

function ringBell(inputMDiv,displayMDiv,toUserId)
{
	// set message input
	document.getElementById(inputMDiv).value = "/ringbell";

    isPrivate = toUserId;

	// send message
	addMessage(inputMDiv,displayMDiv);
}

/*
* clear & focus message input field
*
*/

function clrMessageInput(displayMDiv)
{
	// clear message input
	document.getElementById(displayMDiv).value = '';

	// focus message input
	document.getElementById(displayMDiv).focus();
}


/*
* create message div 
*
*/

var initDoSilence;
var doTextAdverts = 0;

function createMessageDiv(mStatus, mUID, mDiv, mID, message, sfx, mUser, mToUser, mTime)
{
	message	= decodeURI(message);
    lastMessageText = message;
	
	// if message history is enabled 
	// dont load old command messages
   	if(
        showHistory && message.search("BROADCAST") != -1 ||
        showHistory && message.search("KICK") != -1 ||
        showHistory && message.search("WEBCAM_REQUEST") != -1 ||
        showHistory && message.search("WEBCAM_ACCEPT") != -1
	)
   	{
      	return false;
   	}	

	if(message == 'SILENCE' && mToUser == uID)
	{
		isSilenced = 1;
		showInfoBox("system","220","300","200","",lang7+" "+silent+" "+lang8);
		initDoSilence = setInterval('doSilence()',1000);
		return false;
	}

	if(message == 'SILENCE' && mToUser.toLowerCase() != userName.toLowerCase())
	{
		return false;
	}

	if((message == 'KICK') && (mToUser == uID))
	{
		logout('kick');
		return false;
	}

	if(message == 'KICK' && mToUser != uID)
	{
		return false;
	}

	if(message == 'BAN' && mToUser == uID)
	{
		logout('ban');
		return false;
	}

	if(message == 'BAN' && mToUser != uID)
	{
		return false;
	}

	if(mUser && blockedList.indexOf("|"+mUID+"|") != '-1')
	{
		return false;
	}

	// if user has receive PM disabled
	if(mDiv != 'chatContainer' && (userRPM == 'false' || userRPM == false))
	{
		return false;
	}

	// create private window if not open
	pDiv = mDiv.replace("pcontent_","");
	ppDiv = pDiv.split("_");

	// create private chat window if not exists
	if((mUID != uID) && (mDiv != 'chatContainer')) {
		// if message history is enabled
		// dont load old private messages
		if(showHistory)
		{
			//return false;
		}
	
		// if div isnt created
        var altPmDiv = 'pcontent_' + ppDiv[1]  + '_' + ppDiv[0];

		if(!document.getElementById(mDiv) && !document.getElementById(altPmDiv))
		{
			if(ppDiv[0] != uID)
			{
				// this user is receiver, new PM
				createPChatDiv(userName,mUser,uID,mUID);
			}
			else
			{
				// this user is sender (initilised PM)
				// eg. this user crashed or lost connection
				// catches any closed PM that a receiver still has open
				createPChatDiv(userName,mUser,uID,mUID);
			}
		}
        else {
            if(document.getElementById(mDiv)) {
                reopenPWin(ppDiv[0] + "_" + ppDiv[1]);
                if($("#" + ppDiv[0] + "_" + ppDiv[1]).show().outerHeight() == 26) {
                    minPwin(ppDiv[0] + "_" + ppDiv[1], ppDiv[0] + "_" + ppDiv[1])
                }
            }
            else {
                mDiv = altPmDiv;
                reopenPWin(ppDiv[1] + "_" + ppDiv[0]);
                if($("#" + ppDiv[1] + "_" + ppDiv[0]).show().outerHeight() == 26) {
                    minPwin(ppDiv[1] + "_" + ppDiv[0], ppDiv[1] + "_" + ppDiv[0])
                }
            }
        }
	}

	// create new message div
	if(!document.getElementById(mID))
	{
		// create div
		var ni = document.getElementById(mDiv);
		var newdiv = document.createElement('div');
		newdiv.setAttribute("id",mID);

		// normal message
		if(mStatus == 0)
		{
			newdiv.className = 'chatMessage';
		}

		// welcome message
		if(mStatus == 1)
		{
			newdiv.className = 'welcomeMessage';
		}

		// webcam options
		showStreamUID = message.split("||");

		if(showStreamUID[0] == 'WEBCAM_ACCEPT' && mToUser.toLowerCase() == userName.toLowerCase())
		{
			viewCam(mToUser,mUser,showStreamUID[1],mUID);
			return false;
		}

		// add avatar and HTML formatting
      	message = message.replace(/\[b\]/gi, "<b>");
      	message = message.replace(/\[\/b\]/gi, "</b>");
      	message = message.replace(/\[i\]/gi, "<i>");
      	message = message.replace(/\[\/i\]/gi, "</i>");
      	message = message.replace(/\[u\]/gi, "<u>");
      	message = message.replace(/\[\/u\]/gi, "</u>");
		message = message.replace(/\[\[/gi, "<");
		message = message.replace(/\]\]/gi, ">");

		messageArray = message.split("|");

		var showExternal = 0;
		if(admin && messageArray[4].search(/.(jpg|gif|png)$/) > -1) {
			messageArray[4] = '<br><img src="' + messageArray[4] +'" style="max-width: 800px;"/>';
			showExternal = 1;
		}

		// assign entry sfx
		if(messageArray[4].indexOf(publicEntry) != -1)
		{
			sfx = 'doorbell.mp3';
		}

		// assign exit sfx
		if(messageArray[4].indexOf(publicExit) != -1)
		{
			sfx = 'door_close.mp3';
		}

		// format smilies in message
		messageArray[4] = addSmilie(messageArray[4]);
		
		// show broadcast message
		var broadcast = messageArray[4];
		    broadcast = broadcast.replace("<b>","");
		    broadcast = broadcast.replace("<u>","");
		    broadcast = broadcast.replace("<i>","");
		    broadcast = broadcast.replace("</b>","");
		    broadcast = broadcast.replace("</u>","");
		    broadcast = broadcast.replace("</i>","");
		    broadcast = broadcast.split(" ");

		if(broadcast[0] == 'BROADCAST')
		{
            var alertMessage = '[' + mUser + ']' + "<br />" + decodeURI(messageArray[4].replace("BROADCAST",""));
			showInfoBox("system","220","300","200","",alertMessage);
			return false;
		}

		// check for emails
		if(enableEmail)
		{
			messageArray[4] = messageArray[4].replace(/([\w.-]+@[\w.-]+\.[\w]+)/gi, "<a href='mailto:$1'>$1</a>");
		}
		
		// enable youtube videos
		// url must contain youtu.be format   
		// eg. http://youtu.be/ctAu4DgSheI
      
		if(messageArray[4].search(/http:\/\/youtu.be\//gi) > -1)
		{
		    messageArray[4] = messageArray[4].replace("<b>","");
		    messageArray[4] = messageArray[4].replace("<u>","");
		    messageArray[4] = messageArray[4].replace("<i>","");
		    messageArray[4] = messageArray[4].replace("</b>","");
		    messageArray[4] = messageArray[4].replace("</u>","");
		    messageArray[4] = messageArray[4].replace("</i>","");				
		
			var getVideoID = messageArray[4].split("/");
			messageArray[4] = '<br><iframe width="420" height="315" src="http://www.youtube.com/embed/'+getVideoID[3]+'" frameborder="0" allowfullscreen></iframe>';
			showExternal = 1;
		}

		// check for urls
		if(enableUrl && !showExternal)
		{
			messageArray[4] = messageArray[4].replace(/(http[s]?:\/\/[\S]+)/gi, "<a href='$1' target='_blank'>$1</a>");
		}

		var displayName = mUser+":&nbsp;";
		var newMessage = "<img style='float: left; padding: 0 3px 3px 0;' src='avatars/40/"+messageArray[0]+"'>";
        if(parseInt(mTime) > 0) {
            //alert(mTime);
            var d = new Date(mTime*1000);
            //alert(date);
            var h = ("0"+d.getHours()).slice(-2);
            var m = ("0"+d.getMinutes()).slice(-2);
            var s = ("0"+d.getSeconds()).slice(-2);
            newMessage += "<span class='message-time'>[" + h + ':' + m + ':' + s + ']</span> ';
        }
		// if user is allowing anyone to view webcam
		if(messageArray[4] == 'WEBCAM_REQUEST' && mToUser.toLowerCase() == userName.toLowerCase() && (userRWebcam == 'true' || userRWebcam == true))
		{
			acceptViewWebcam(encodeURI(mUser));
			return false;
		}

		// if user requires permission to view webcam
		if(messageArray[4] == 'WEBCAM_REQUEST' && mToUser.toLowerCase() == userName.toLowerCase())
		{
			displayName = "";
			messageArray[4] = decodeURI(mUser) + "&nbsp;"+lang19+" <span style='cursor:pointer' onclick='acceptViewWebcam(\""+encodeURI(mUser)+"\");showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\""+lang20+" "+mUser+" "+lang21+"\");'>"+lang22+"</span> "+lang23+" <span style='cursor:pointer' onclick='showInfoBox(\"system\",\"200\",\"300\",\"200\",\"\",\""+lang24+" "+mUser+" "+lang21+"\");'>"+lang25+"</span> "+lang26;
		}

		// entry/exit message
		if(messageArray[0]=='1')
		{
			displayName = "";
			newMessage = "";
		}

		// welcome/IRC/whisper message
		if(messageArray[5]=='1')
		{
			displayName = mUser;
		}

		// format messages with line breaks
		if(messageArray[6]=='1')
		{
			messageArray[4] = "<pre style='white-space: pre-wrap;'>"+messageArray[4]+"</pre>";
		}

        var fontEmSize = textScale / 100;
		newMessage +=
            "<span id='username' style='cursor:pointer;font-weight: bold;'>" +
                '<span class="message-text-scale" style="font-size: ' + fontEmSize + 'em">' +
                    displayName +
                '</span>' +
            "</span>";
		newMessage +=
            "<span style='color:" + getSafeColor(messageArray[1],defaultColor) + ";font-size:" + messageArray[2] + ";font-family:" + messageArray[3] + ";'>" +
                '<span class="message-text-scale" style="font-size: ' + fontEmSize + 'em">' +
                    messageArray[4] +
                '</span>' +
            '</span>';

		// shout filter
		if(enableShoutFilter)
		{
			newMessage = newMessage.toLowerCase();
		}

		// show message
		newMessage = newMessage.replace(/&#127;/gi,"\|");
		newdiv.innerHTML = decodeURIComponent(newMessage);

		if(ni != null)
		{
			ni.appendChild(newdiv);
		}

		// update div count
		showMessages += 1;
	}

	// trim messages
	countMessages(mDiv);
	
	// control the auto scroll
	if(document.getElementById(mDiv) && document.getElementById('autoScrollID').checked==true)
	{
		var chat_div = document.getElementById(mDiv);
		chat_div.scrollTop = chat_div.scrollHeight;			
	}

	// if private window is minimised, show alert
	if(mDiv != 'chatContainer')
	{
		if(document.getElementById(pDiv) && document.getElementById(pDiv).style.visibility == 'visible')		
		{
			if(mUser.toLowerCase() != userName.toLowerCase() && document.getElementById("psendbox_"+pDiv).style.visibility != 'visible')
			{
				showAlert(pDiv);			
			}
		}
		
		if(document.getElementById(pDiv) && document.getElementById(pDiv).style.visibility != 'visible')
		{
			document.getElementById(pDiv).style.visibility = 'visible';
			document.getElementById("pcontent_"+pDiv).style.visibility = 'visible';
			document.getElementById("pmenuBar_"+pDiv).style.visibility = 'visible';
			document.getElementById("psendbox_"+pDiv).style.visibility = 'visible';
			document.getElementById("pmenuWin_"+pDiv).style.visibility = 'visible';
		}

	}

	// show text adverts
	if(textAdverts && !ppDiv[1])
	{
		if(doTextAdverts == showTextAdverts)
		{
			doTextAdverts = 0;

			var sta = setTimeout('showTextAdvertisement();',2000)
		}
		else
		{
			doTextAdverts += 1;
		}
	}

	// play sound
	var playSounds = 0;

	if(document.getElementById('soundsID').checked==true && sfx == 'beep_high.mp3')
	{
		doSound(sfx);
	}

	if(document.getElementById('entryExitID').checked==true && (sfx == 'doorbell.mp3' || sfx == 'door_close.mp3'))
	{
		doSound(sfx);
	}

	if(document.getElementById('sfxID').checked==true && mySFX.toString().lastIndexOf(sfx) == -1 && (sfx != 'doorbell.mp3' && sfx != 'door_close.mp3' && sfx != 'beep_high.mp3'))
	{
		doSound(sfx);
	}

}

/*
* show text adverts
* 
*/

function showTextAdvertisement()
{
	if(advertDesc[0])
	{
		createMessageDiv('0', '-1', 'chatContainer', showMessages+1, '../images/notice.gif|'+stextColor+'|'+stextSize+'|'+stextFamily+'|'+advertDesc[Math.floor(Math.random()*advertDesc.length)], 'beep_high.mp3', 'AdBot', '', '');	
	}
}

/*
* silence the user
* 
*/

var s = 0;
function doSilence()
{
	s += 1;

	if(s > (silent*60))
	{
		showInfoBox("system","220","300","200","",lang27);

		clearInterval(initDoSilence);

		s = 0;

		isSilenced = 0;
	}
}

/*
* highlight pm title bar (if pm is minimised)
* 
*/

function showAlert(pDiv)
{
	document.getElementById("ptitle_"+pDiv).style.backgroundColor = newPMmin;
}

/*
* trim total messages in chat window
*
*/

function countMessages(pDiv)
{
	if(document.getElementById(pDiv))
	{
		var parentCount = document.getElementById(pDiv);
		var childCount = parentCount.getElementsByTagName('div').length;

		// if message divs greater than total message divs
		if(childCount > totalMessages)
		{
			var trimMessages = document.getElementById(pDiv);
  			trimMessages.removeChild(trimMessages.firstChild);
		}
	}

}

/*
* delete message div 
*
*/

function removeMessageDiv(divID)
{
	// if div exists
	if(document.getElementById(divID))
	{
		// remove div
		var d = document.getElementById('chatContainer');
		var olddiv = document.getElementById(divID);
		d.removeChild(olddiv);
	}

}

/*
* use enter key as submit 
*
*/

function submitenter(myfield,e,inputMDiv,displayMDiv,pChat)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;

	if (keycode == 13)
	{
		// send message
		isPrivate = pChat;
		addMessage(inputMDiv,displayMDiv);
		return false;
	}
	else return true;
}

var request = {};
request.handle = function(commandLine) {
    var remainder = commandLine.substr(commandLine.indexOf(' ')+1);
    if((commandLine == remainder) || (remainder == "")) {
        alert("Show Request. Right now the two valid commands are 'list' and 'add'.");
    }
    else {
        var parameters = remainder.split(' ');
        if(parameters[0] == 'list') {
            window.open('/request.php?action=list&character_id=' + userID);
        }
        else if(parameters[0] == 'add') {
            window.open('/request.php?action=create&character_id=' + userID);
        }
        else {
            alert('Unrecognized command: ' + parameters[0]);
        }
    }
};

var nick = {};
nick.change = function(commandLine) {
    var remainder = commandLine.substr(commandLine.indexOf(' ')+1);
    if((commandLine == remainder) || (remainder == "")) {
        alert("Please type a new nick");
    }
    else {
        $.post(
            '/chat/includes/nick.php',
            {
                action: 'change',
                new_name: remainder
            },
            function(response) {
                if(response.status == true) {
                    // set nick information
                    userName = remainder;
                    updateDisplayName(uID, remainder, roomID, isInvisible);
                }
                else {
                    alert(response.message);
                }
            }
        );
    }
};

var dice = {
    roll:  function(commandLine, displayMDiv) {
        var remainder = commandLine.substr(commandLine.indexOf('roll ')+5);
        if((commandLine == remainder) || (remainder == "")) {
            this.helpRoll();
        }
        else {
            var div = displayMDiv;
            var action = 'roll';
            var parts = remainder.split(' ');
            if((parts[0].toLowerCase() == 'init') || (parts[0] == 'initiative')) {
                action = 'initiative'
            }
            $.post(
                '/chat/includes/dice.php',
                {
                    action: action,
                    command: remainder
                },
                function(response) {
                    if(response.status) {
                        // send the message
                        message = userAvatar+"|"+textColor+"|"+textSize+"|"+textFamily+"|"+response.message+"|1|0";
                        // send data to database
                        sendData(div);
                        createMessageDiv('0', uID, displayMDiv, showMessages+1, message, sfx, userName, '',(new Date().getTime()/1000));
                    }
                    else {
                        alert(response.message);
                    }
                }
            );
        }
    },
    listRolls: function() {
        $("#sub-panel").load('/dieroller.php?action=list', function() {
            $(this).dialog({
                width: 500,
                height: 500,
                title: 'Recent Rolls'
            });
        })
    },
    help: function() {
        alert('Valid options for the /dice command are:\n - roll\n - list');
    },
    helpRoll: function() {
        alert('The format for the command is /dice roll "my action" <dice> [WP] [Blood]');
    }
};

var ghost = {
	help: function() {
		alert('Valid options for the /dice command are:\n - off\n - on');
	},
    off: function() {
        $.post(
            '/chat/includes/ghost.php',
            {
                action: 'off'
            },
            function(response) {
                if(response.status) {
                    message = userAvatar+"|"+textColor+"|"+textSize+"|"+textFamily+"|"+' [b]Ghost Mode Off[/b]'+"|1|0";
                    createMessageDiv('0', uID, displayMDiv, showMessages+1, message, sfx, userName, '',(new Date().getTime()/1000));
                }
                else {
                    alert(response.message);
                }
            }
        );
    },
    on: function() {
        $.post(
            '/chat/includes/ghost.php',
            {
                action: 'on'
            },
            function(response) {
                if(response.status) {
                    message = userAvatar+"|"+textColor+"|"+textSize+"|"+textFamily+"|"+' [b]Ghost Mode On[/b]'+"|1|0";
                    createMessageDiv('0', uID, displayMDiv, showMessages+1, message, sfx, userName, '',(new Date().getTime()/1000));
                }
                else {
                    alert(response.message);
                }
            }
        );
    }
};

var scenes = {
	help: function() {
		alert('Valid options for the /scenes command are:\n - list\n - add');

	},

	list: function() {
		//$("#sub-panel").load('/scenes', function() {
		//	$(this).dialog({
		//		width: 500,
		//		height: 500,
		//		title: 'Upcoming Scenes'
		//	});
		//});

		window.open('/scenes');
	},

	add: function() {
		window.open('/scenes/add');
	}
};

/*
* init ajax object
*
*/

//Define XmlHttpRequest
var updateUserRooms = getXmlHttpRequestObject();

/*
* show create room div
*
*/

function newRoom($id)
{
	if(groupRooms == 0)
	{
		showInfoBox("system","220","300","200","",lang6);
		return false;			
	}

	if($id == '1')
	{
		// show create room
		document.getElementById("roomCreate").style.visibility = 'visible';
	}
	else
	{
		// hide create room
		document.getElementById("roomCreate").style.visibility = 'hidden';
	}
    return false;
}

/*
* add room
*
*/

function addRoom()
{
	// get new room name
	var newRoomName = "|" + document.getElementById("roomName").value.toLowerCase() + "|";
	
	// check if room already exists
	if(roomNameStr.indexOf(newRoomName)!= '-1')
	{
		showInfoBox("system","220","300","200","",lang28);

		return false;
	}

    // check for badwords/chars
    /*newRoomName = newRoomName.replaceAll(' ', '&nbsp;');
	var checkRoomName = filterBadword(newRoomName.replace(/\|/g,""));
		checkRoomName = checkRoomName.split("");

	for (i=0; i < checkRoomName.length; i++)
	{
		if(badChars.indexOf("|"+checkRoomName[i]+"|") != '-1')
		{
			// check for badwords
			if(checkRoomName[i] == '*')
			{
				checkRoomName[i] = '****';
			}

			// check for space
			if(checkRoomName[i] == ' ')
			{
				checkRoomName[i] = 'space';
			}

			showInfoBox("system","220","300","200","","Room name contains illegal characters [ "+checkRoomName[i]+" ]");

			return false;
		}
	}*/

    var params = {
        action: 'add',
        roomName: encodeURIComponent(document.getElementById("roomName").value),
        roomPass: document.getElementById("roomPass").value
    };

    // clr input fields
    document.getElementById("roomName").value = '';
    document.getElementById("roomPass").value = '';

    // hide room creator
    newRoom('0');
    showInfoBox("system","220","300","200","",lang29);

    $.post('includes/room.php', params, function(response) {
        closeMdiv('system');
        if(response.status) {
            document.location = 'index.php?roomID=' + response.roomId;
        }
        else {
            alert(response.message);
        }
    });
}

function handleSendBlock()
{	
	// empty
}/*
 * play sound
 *
 */

function doSound(playSound) {
    var flashvars = {};
    flashvars.sndfilename = playSound;
    var params = {};
    params.play = "true";
    params.loop = "false";
    params.menu = "false";
    params.scale = "noscale";
    // params.wmode = "transparent";
    params.height = "200";
    params.width = "200";
    params.bgcolor = "#FFFFFF";
    var attributes = {};
    attributes.align = "top";
    swfobject.embedSWF("swf/playSnd.swf", "playSndDiv", "100%", "1", "9.0.0", "swf/expressInstall.swf", flashvars, params, attributes);
}/*
 * create private chat div
 *
 */

var privateChat = 0;

function createPChatDiv(divPName, divToName, fromUserId, toUserId) {
    if (privateChat == 1) {
        showInfoBox("system", "220", "300", "200", "", lang30);
        return false;
    }

    // to user
    var uUser;
    if (divToName != userName) {
        uUser = divToName;
    }
    else {
        uUser = divPName;
    }

    // window title
    var pTitle = uUser;

    // div name
    if (parseInt(toUserId) > parseInt(fromUserId)) {
        divPName = fromUserId + "_" + toUserId;
    }
    else {
        divPName = toUserId + "_" + fromUserId;
    }

    // prevent duplicate private chat windows
    if (document.getElementById(divPName)) {
        reopenPWin(divPName);
        return false;
    }

    // if div doesn't exist
    if (!document.getElementById(divPName)) {
        // create div
        var ni = $("#pWin");

        var newdiv = $('<div>');
        newdiv.attr('id', divPName);
        newdiv.addClass('pChatWin');

        var pmContainer = $('<div>');

        // title
        pmContainer.append($("<div id='ptitle_" + divPName + "' class='ptitle' style='cursor:move;' onclick=doFocus(\"" + divPName + "\")> <span style='float:left;'>&nbsp;<img style='vertical-align:middle;' src='avatars/online.gif'>&nbsp;" + decodeURI(pTitle) + "</span> <span style='float:right;'><span style='cursor:pointer;' onclick='minPwin(\"" + divPName + "\",\"" + divPName + "\")'><img src='images/min.gif'></span>&nbsp;<span style='cursor:pointer;' onclick='closePWin(\"" + divPName + "\");eCreditsRequest(\"" + divPName + "\",\"off\");privateChatCount();'><img src='images/close.gif'></span>&nbsp;</div>"));

        // content
        pmContainer.append($("<div id='pcontent_" + divPName + "' class='pcontent'></div>"));

        // menu
        pmContainer.append($("<div id='pmenuBar_" + divPName + "' class='pmenuBar'></div>"));

        // sendbox
        pmContainer.append(
            $('<div id=\'psendbox_' + divPName + '\' class=\'psendbox\'>' +
                '<input type=\'text\' id=\'poptionsBar_' + divPName + '\' class="poptionsBar" onKeyPress="return submitenter(this,event,\'poptionsBar_' + divPName + '\',\'pcontent_' + divPName + '\',\'' + toUserId + '\');" onfocus="changeMessBoxStyle(\'poptionsBar_' + divPName + '\');">' +
                '</textarea>' +
                '<input id="poptionsSend" class="poptionsSend" type="button" value="' + lang31 + '" onclick="sendPMessage(\'' + toUserId + '\',\'poptionsBar_' + divPName + '\',\'pcontent_' + divPName + '\')">' +
            '</div>'));

        // menu win
        pmContainer.append($("<div id='pmenuWin_" + divPName + "'></div>"));


        if (ni.length != 0) {
            ni.append(newdiv.append(pmContainer));
        }
        // add menu
        optionsMenu('pmenuBar_' + divPName, 'poptionsBar_' + divPName, 'pcontent_' + divPName, 'pmenuWin_' + divPName, toUserId);

        // focus window
        doFocus(divPName);

        // drag window
        $("#" + divPName)
            .draggable({
                handle: '.ptitle',
                containment: 'document'
            })
            .resizable({
                minWidth: 400,
                minHeight: 300,
                resize: function (e, ui) {
                    $(".pcontent", ui.element).css('height', ui.element.innerHeight() - 90);
                    $(".pmenuBar", ui.element).css('width', ui.element.innerWidth() - 4);
                    $(".psendbox", ui.element).css('width', ui.element.innerWidth() - 4);
                    $(".poptionsBar", ui.element).css('width', ui.element.innerWidth() - 70);
                }
            });
    }

    // if eCredits is enabled
    if (eCredits == 1 && Number(toUserId) == Number(uID)) {
        eCreditsRequest(divPName, 'on');
        privateChat = 1;
    }
    return false;
}

/*
 * reset private window count
 *
 */

function privateChatCount() {
    privateChat = 0;
}

/*
 * send private message
 *
 */

function sendPMessage(uUser, divPName1, divPName2) {
    // send message
    isPrivate = uUser;
    addMessage(divPName1, divPName2);
}

/*
 * minimise private div
 *
 */

function minPwin(divID, uID) {
    toggleHeader(divID, uID);
}

/*
 * close private div
 *
 */

function reopenPWin(divID) {
    $('#' + divID).css('visibility', 'visible');
    $('#pcontent_' + divID).css('visibility', 'visible');
    $('#pmenuBar_' + divID).css('visibility', 'visible');
    $('#psendbox_' + divID).css('visibility', 'visible');
    $('#pmenuWin_' + divID).css('visibility', 'visible');
}

function closePWin(divID) {
    document.getElementById(divID).style.visibility = 'hidden';
    document.getElementById('pcontent_' + divID).style.visibility = 'hidden';
    document.getElementById('pmenuBar_' + divID).style.visibility = 'hidden';
    document.getElementById('psendbox_' + divID).style.visibility = 'hidden';
    document.getElementById('pmenuWin_' + divID).style.visibility = 'hidden';
}

/*
 * private chat is initiated
 * eCredits function
 */

function eCreditsRequest(uuID, status) {
    uuID = uuID.replace(uID, '');
    uuID = uuID.replace("_", '');

    var param = '?';
    param += '&eCreditID=' + escape(uuID);
    param += '&eCreditStatus=' + escape(status);

    // if ready to send message to DB
    if (sendReq.readyState == 4 || sendReq.readyState == 0) {
        sendReq.open("POST", 'includes/sendData.php?rnd=' + Math.random(), true);
        sendReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        sendReq.onreadystatechange = handleSendChat;
        sendReq.send(param);
    }

}/**
 * jQuery.fn.sortElements
 * --------------
 * @param Function comparator:
 *   Exactly the same behaviour as [1,2,3].sort(comparator)
 *
 * @param Function getSortable
 *   A function that should return the element that is
 *   to be sorted. The comparator will run on the
 *   current collection, but you may want the actual
 *   resulting sort to occur on a parent or another
 *   associated element.
 *
 *   E.g. $('td').sortElements(comparator, function(){
 *      return this.parentNode; 
 *   })
 *
 *   The <td>'s parent (<tr>) will be sorted instead
 *   of the <td> itself.
 */
jQuery.fn.sortElements = (function () {

    var sort = [].sort;

    return function (comparator, getSortable) {

        getSortable = getSortable || function () {
            return this;
        };

        var placements = this.map(function () {

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

            // Since the element itself will change position, we have
            // to have some way of storing its original position in
            // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function () {

                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }

                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);

            };

        });

        return sort.call(this, comparator).each(function (i) {
            placements[i].call(getSortable.call(this));
        });

    };

})();/* SWFObject v2.1 <http://code.google.com/p/swfobject/>
	Copyright (c) 2007-2008 Geoff Stearns, Michael Williams, and Bobby van der Sluis
	This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
*/
var swfobject=function(){var b="undefined",Q="object",n="Shockwave Flash",p="ShockwaveFlash.ShockwaveFlash",P="application/x-shockwave-flash",m="SWFObjectExprInst",j=window,K=document,T=navigator,o=[],N=[],i=[],d=[],J,Z=null,M=null,l=null,e=false,A=false;var h=function(){var v=typeof K.getElementById!=b&&typeof K.getElementsByTagName!=b&&typeof K.createElement!=b,AC=[0,0,0],x=null;if(typeof T.plugins!=b&&typeof T.plugins[n]==Q){x=T.plugins[n].description;if(x&&!(typeof T.mimeTypes!=b&&T.mimeTypes[P]&&!T.mimeTypes[P].enabledPlugin)){x=x.replace(/^.*\s+(\S+\s+\S+$)/,"$1");AC[0]=parseInt(x.replace(/^(.*)\..*$/,"$1"),10);AC[1]=parseInt(x.replace(/^.*\.(.*)\s.*$/,"$1"),10);AC[2]=/r/.test(x)?parseInt(x.replace(/^.*r(.*)$/,"$1"),10):0}}else{if(typeof j.ActiveXObject!=b){var y=null,AB=false;try{y=new ActiveXObject(p+".7")}catch(t){try{y=new ActiveXObject(p+".6");AC=[6,0,21];y.AllowScriptAccess="always"}catch(t){if(AC[0]==6){AB=true}}if(!AB){try{y=new ActiveXObject(p)}catch(t){}}}if(!AB&&y){try{x=y.GetVariable("$version");if(x){x=x.split(" ")[1].split(",");AC=[parseInt(x[0],10),parseInt(x[1],10),parseInt(x[2],10)]}}catch(t){}}}}var AD=T.userAgent.toLowerCase(),r=T.platform.toLowerCase(),AA=/webkit/.test(AD)?parseFloat(AD.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,q=false,z=r?/win/.test(r):/win/.test(AD),w=r?/mac/.test(r):/mac/.test(AD);/*@cc_on q=true;@if(@_win32)z=true;@elif(@_mac)w=true;@end@*/return{w3cdom:v,pv:AC,webkit:AA,ie:q,win:z,mac:w}}();var L=function(){if(!h.w3cdom){return }f(H);if(h.ie&&h.win){try{K.write("<script id=__ie_ondomload defer=true src=//:><\/script>");J=C("__ie_ondomload");if(J){I(J,"onreadystatechange",S)}}catch(q){}}if(h.webkit&&typeof K.readyState!=b){Z=setInterval(function(){if(/loaded|complete/.test(K.readyState)){E()}},10)}if(typeof K.addEventListener!=b){K.addEventListener("DOMContentLoaded",E,null)}R(E)}();function S(){if(J.readyState=="complete"){J.parentNode.removeChild(J);E()}}function E(){if(e){return }if(h.ie&&h.win){var v=a("span");try{var u=K.getElementsByTagName("body")[0].appendChild(v);u.parentNode.removeChild(u)}catch(w){return }}e=true;if(Z){clearInterval(Z);Z=null}var q=o.length;for(var r=0;r<q;r++){o[r]()}}function f(q){if(e){q()}else{o[o.length]=q}}function R(r){if(typeof j.addEventListener!=b){j.addEventListener("load",r,false)}else{if(typeof K.addEventListener!=b){K.addEventListener("load",r,false)}else{if(typeof j.attachEvent!=b){I(j,"onload",r)}else{if(typeof j.onload=="function"){var q=j.onload;j.onload=function(){q();r()}}else{j.onload=r}}}}}function H(){var t=N.length;for(var q=0;q<t;q++){var u=N[q].id;if(h.pv[0]>0){var r=C(u);if(r){N[q].width=r.getAttribute("width")?r.getAttribute("width"):"0";N[q].height=r.getAttribute("height")?r.getAttribute("height"):"0";if(c(N[q].swfVersion)){if(h.webkit&&h.webkit<312){Y(r)}W(u,true)}else{if(N[q].expressInstall&&!A&&c("6.0.65")&&(h.win||h.mac)){k(N[q])}else{O(r)}}}}else{W(u,true)}}}function Y(t){var q=t.getElementsByTagName(Q)[0];if(q){var w=a("embed"),y=q.attributes;if(y){var v=y.length;for(var u=0;u<v;u++){if(y[u].nodeName=="DATA"){w.setAttribute("src",y[u].nodeValue)}else{w.setAttribute(y[u].nodeName,y[u].nodeValue)}}}var x=q.childNodes;if(x){var z=x.length;for(var r=0;r<z;r++){if(x[r].nodeType==1&&x[r].nodeName=="PARAM"){w.setAttribute(x[r].getAttribute("name"),x[r].getAttribute("value"))}}}t.parentNode.replaceChild(w,t)}}function k(w){A=true;var u=C(w.id);if(u){if(w.altContentId){var y=C(w.altContentId);if(y){M=y;l=w.altContentId}}else{M=G(u)}if(!(/%$/.test(w.width))&&parseInt(w.width,10)<310){w.width="310"}if(!(/%$/.test(w.height))&&parseInt(w.height,10)<137){w.height="137"}K.title=K.title.slice(0,47)+" - Flash Player Installation";var z=h.ie&&h.win?"ActiveX":"PlugIn",q=K.title,r="MMredirectURL="+j.location+"&MMplayerType="+z+"&MMdoctitle="+q,x=w.id;if(h.ie&&h.win&&u.readyState!=4){var t=a("div");x+="SWFObjectNew";t.setAttribute("id",x);u.parentNode.insertBefore(t,u);u.style.display="none";var v=function(){u.parentNode.removeChild(u)};I(j,"onload",v)}U({data:w.expressInstall,id:m,width:w.width,height:w.height},{flashvars:r},x)}}function O(t){if(h.ie&&h.win&&t.readyState!=4){var r=a("div");t.parentNode.insertBefore(r,t);r.parentNode.replaceChild(G(t),r);t.style.display="none";var q=function(){t.parentNode.removeChild(t)};I(j,"onload",q)}else{t.parentNode.replaceChild(G(t),t)}}function G(v){var u=a("div");if(h.win&&h.ie){u.innerHTML=v.innerHTML}else{var r=v.getElementsByTagName(Q)[0];if(r){var w=r.childNodes;if(w){var q=w.length;for(var t=0;t<q;t++){if(!(w[t].nodeType==1&&w[t].nodeName=="PARAM")&&!(w[t].nodeType==8)){u.appendChild(w[t].cloneNode(true))}}}}}return u}function U(AG,AE,t){var q,v=C(t);if(v){if(typeof AG.id==b){AG.id=t}if(h.ie&&h.win){var AF="";for(var AB in AG){if(AG[AB]!=Object.prototype[AB]){if(AB.toLowerCase()=="data"){AE.movie=AG[AB]}else{if(AB.toLowerCase()=="styleclass"){AF+=' class="'+AG[AB]+'"'}else{if(AB.toLowerCase()!="classid"){AF+=" "+AB+'="'+AG[AB]+'"'}}}}}var AD="";for(var AA in AE){if(AE[AA]!=Object.prototype[AA]){AD+='<param name="'+AA+'" value="'+AE[AA]+'" />'}}v.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+AF+">"+AD+"</object>";i[i.length]=AG.id;q=C(AG.id)}else{if(h.webkit&&h.webkit<312){var AC=a("embed");AC.setAttribute("type",P);for(var z in AG){if(AG[z]!=Object.prototype[z]){if(z.toLowerCase()=="data"){AC.setAttribute("src",AG[z])}else{if(z.toLowerCase()=="styleclass"){AC.setAttribute("class",AG[z])}else{if(z.toLowerCase()!="classid"){AC.setAttribute(z,AG[z])}}}}}for(var y in AE){if(AE[y]!=Object.prototype[y]){if(y.toLowerCase()!="movie"){AC.setAttribute(y,AE[y])}}}v.parentNode.replaceChild(AC,v);q=AC}else{var u=a(Q);u.setAttribute("type",P);for(var x in AG){if(AG[x]!=Object.prototype[x]){if(x.toLowerCase()=="styleclass"){u.setAttribute("class",AG[x])}else{if(x.toLowerCase()!="classid"){u.setAttribute(x,AG[x])}}}}for(var w in AE){if(AE[w]!=Object.prototype[w]&&w.toLowerCase()!="movie"){F(u,w,AE[w])}}v.parentNode.replaceChild(u,v);q=u}}}return q}function F(t,q,r){var u=a("param");u.setAttribute("name",q);u.setAttribute("value",r);t.appendChild(u)}function X(r){var q=C(r);if(q&&(q.nodeName=="OBJECT"||q.nodeName=="EMBED")){if(h.ie&&h.win){if(q.readyState==4){B(r)}else{j.attachEvent("onload",function(){B(r)})}}else{q.parentNode.removeChild(q)}}}function B(t){var r=C(t);if(r){for(var q in r){if(typeof r[q]=="function"){r[q]=null}}r.parentNode.removeChild(r)}}function C(t){var q=null;try{q=K.getElementById(t)}catch(r){}return q}function a(q){return K.createElement(q)}function I(t,q,r){t.attachEvent(q,r);d[d.length]=[t,q,r]}function c(t){var r=h.pv,q=t.split(".");q[0]=parseInt(q[0],10);q[1]=parseInt(q[1],10)||0;q[2]=parseInt(q[2],10)||0;return(r[0]>q[0]||(r[0]==q[0]&&r[1]>q[1])||(r[0]==q[0]&&r[1]==q[1]&&r[2]>=q[2]))?true:false}function V(v,r){if(h.ie&&h.mac){return }var u=K.getElementsByTagName("head")[0],t=a("style");t.setAttribute("type","text/css");t.setAttribute("media","screen");if(!(h.ie&&h.win)&&typeof K.createTextNode!=b){t.appendChild(K.createTextNode(v+" {"+r+"}"))}u.appendChild(t);if(h.ie&&h.win&&typeof K.styleSheets!=b&&K.styleSheets.length>0){var q=K.styleSheets[K.styleSheets.length-1];if(typeof q.addRule==Q){q.addRule(v,r)}}}function W(t,q){var r=q?"visible":"hidden";if(e&&C(t)){C(t).style.visibility=r}else{V("#"+t,"visibility:"+r)}}function g(s){var r=/[\\\"<>\.;]/;var q=r.exec(s)!=null;return q?encodeURIComponent(s):s}var D=function(){if(h.ie&&h.win){window.attachEvent("onunload",function(){var w=d.length;for(var v=0;v<w;v++){d[v][0].detachEvent(d[v][1],d[v][2])}var t=i.length;for(var u=0;u<t;u++){X(i[u])}for(var r in h){h[r]=null}h=null;for(var q in swfobject){swfobject[q]=null}swfobject=null})}}();return{registerObject:function(u,q,t){if(!h.w3cdom||!u||!q){return }var r={};r.id=u;r.swfVersion=q;r.expressInstall=t?t:false;N[N.length]=r;W(u,false)},getObjectById:function(v){var q=null;if(h.w3cdom){var t=C(v);if(t){var u=t.getElementsByTagName(Q)[0];if(!u||(u&&typeof t.SetVariable!=b)){q=t}else{if(typeof u.SetVariable!=b){q=u}}}}return q},embedSWF:function(x,AE,AB,AD,q,w,r,z,AC){if(!h.w3cdom||!x||!AE||!AB||!AD||!q){return }AB+="";AD+="";if(c(q)){W(AE,false);var AA={};if(AC&&typeof AC===Q){for(var v in AC){if(AC[v]!=Object.prototype[v]){AA[v]=AC[v]}}}AA.data=x;AA.width=AB;AA.height=AD;var y={};if(z&&typeof z===Q){for(var u in z){if(z[u]!=Object.prototype[u]){y[u]=z[u]}}}if(r&&typeof r===Q){for(var t in r){if(r[t]!=Object.prototype[t]){if(typeof y.flashvars!=b){y.flashvars+="&"+t+"="+r[t]}else{y.flashvars=t+"="+r[t]}}}}f(function(){U(AA,y,AE);if(AA.id==AE){W(AE,true)}})}else{if(w&&!A&&c("6.0.65")&&(h.win||h.mac)){A=true;W(AE,false);f(function(){var AF={};AF.id=AF.altContentId=AE;AF.width=AB;AF.height=AD;AF.expressInstall=w;k(AF)})}}},getFlashPlayerVersion:function(){return{major:h.pv[0],minor:h.pv[1],release:h.pv[2]}},hasFlashPlayerVersion:c,createSWF:function(t,r,q){if(h.w3cdom){return U(t,r,q)}else{return undefined}},removeSWF:function(q){if(h.w3cdom){X(q)}},createCSS:function(r,q){if(h.w3cdom){V(r,q)}},addDomLoadEvent:f,addLoadEvent:R,getQueryParamValue:function(v){var u=K.location.search||K.location.hash;if(v==null){return g(u)}if(u){var t=u.substring(1).split("&");for(var r=0;r<t.length;r++){if(t[r].substring(0,t[r].indexOf("="))==v){return g(t[r].substring((t[r].indexOf("=")+1)))}}}return""},expressInstallCallback:function(){if(A&&M){var q=C(m);if(q){q.parentNode.replaceChild(M,q);if(l){W(l,true);if(h.ie&&h.win){M.style.display="block"}}M=null;l=null;A=false}}}}}();/*
* share
*
*/

var share = 0;

/*
* create users div 
*
*/

function createUsersDiv(uuID, userID, uUser, uDisplay, uAvatar, uWebcam, uPrevRoom, uRoom, uActivity, uStatus, uWatch, uAdmin, uModerator, uSpeaker, uActive, uLastActive, uIP, userTypeId, invisible)
{
	// sender has closed webcam window
	if(document.getElementById("cam_"+uuID) && uWebcam == 0)
	{
		// close viewers window
		deleteWebcamDiv("cam_"+uuID,"pWin",uuID);
	}

	var showAdminID = '';
	var uBlock = 1;

    var userClass = 0;
    if((userTypeId == 4) || (userTypeId == 5)) {
        userClass = 'user-storyteller';
        showAdminID = ' (ST) ';
        uBlock = 0;
    }
    if(userTypeId == 6) {
        userClass = 'user-storyteller user-admin';
        showAdminID = ' (Admin) ';
        uBlock = 0;
    }
    if(userTypeId == 7) {
        userClass = 'user-wiki'
    }

	// user is active
	if(uActivity == 1)
	{
		// if div exists
		if(!document.getElementById("userlist_"+uuID+uRoom))
		{
			// create div
			var ni = document.getElementById("room_"+uRoom);

			var newdiv = document.createElement('div');
			newdiv.setAttribute("id","userlist_"+uuID+uRoom);
			newdiv.className = "userlist";
			
			// show username
			if(webcamsOn)
			{
				newdiv.innerHTML = "<div class='scrollable-container'><div class='scrollable user-div'><img id='avatar_"+uuID+"' style='vertical-align:middle;' src='avatars/"+uAvatar+"'>&nbsp;<span onclick='userPanel(\""+userName+"\",\""+uUser+"\",\""+uuID+"\",\""+uRoom+"\",\""+userID+"\",\""+uAvatar+"\",\""+uBlock+"\",\""+uIP+"\")'>"+decodeURI(uUser)+showAdminID+showModeratorID+showSpeakerID+"</span><span id='ustatusID_"+uuID+"'></span></div><span style='float:right;'><img id='watch_"+uuID+"' style='vertical-align:middle;' src='images/inv.gif'><img id='webcam_"+uuID+"' style='vertical-align:middle;' src='images/inv.gif'></span></div>";
			}
			else
			{
                newdiv.innerHTML =
                    "<div class='scrollable-container user-div'>" +
                        "<div class='scrollable'>" +
                            "<img id='avatar_" + uuID + "' style='vertical-align:middle;' src='avatars/" + uAvatar + "'>" +
                            "&nbsp;" +
                            "<span class='" + userClass + "' " +
                                "onclick='userPanel(\"" + userName + "\",\"" + uUser + "\",\"" + uuID + "\",\"" + uRoom + "\",\"" + userID + "\",\"" + uAvatar + "\",\"" + uBlock + "\",\"" + uIP + "\")' ondblclick='createPChatDiv(\"" + userName + "\",\"" + uUser + "\",\"" + uID + "\",\"" + uuID + "\");deleteDiv(\"userpanel_" + uuID + uID + "\", \"userlist_" + uuID + uID + "\");'>" +
                                "<span class='username'>" +
                                    decodeURI(uUser) +
                                "</span>" +
                                showAdminID +
                            "</span>" +
                            "<span id='ustatusID_" + uuID + "'></span>" +
                        "</div>" +
                    "</div>";
			}

            if(ni != null) {
			    ni.appendChild(newdiv);
            }
		}

		// remove user(s) divs from previous room
		if(uPrevRoom != uRoom && document.getElementById("userlist_"+uuID+uPrevRoom))
		{
			// remove user from userlist
			deleteDiv("userlist_"+uuID+uPrevRoom,"room_"+uPrevRoom);
		}

		// update user room count
		updateUserRoomCount(uRoom, '1');

		// update users avatar
		updateAvatar(uuID, uAvatar, uRoom);

        // update user display name
        updateDisplayName(uuID, uDisplay, uRoom, invisible);

		// show blocked user icon
		if(uUser && blockedList.indexOf("|"+uuID+"|") != '-1')
		{
			updateAvatar(uuID, 'block.gif', uRoom);
		}

		// show watching webcam icon
		if(webcamsOn)
		{
			if(uWatch && uWatch.indexOf("|"+uID+"|") != '-1')
			{
				updateWatchingWebcamStatus(uuID, '1');
			}
			else
			{
				updateWatchingWebcamStatus(uuID, '0');
			}
		}

		// update webcam status
		if(webcamsOn)
		{
			updateWebcamStatus(uuID,uWebcam);
		}

		// update user status message
		updateUserStatusMes(uuID,uStatus);

	}
	else
	{
		// remove user from userlist
		deleteDiv("userlist_"+uuID+uRoom,"room_"+uRoom);

		// logout user
		if(Number(uID) == Number(uuID))
		{
			logout();
		}
	}

	// check if user has sent a message, if not
	// the idle period exceeded, show user is idle
	if(document.getElementById("ustatusID_"+uuID) && (Number(uActive) - Number(uLastActive)) > Number(idleTimeout))
	{
		document.getElementById("ustatusID_"+uuID).innerHTML = " [Idle]";
	}

	// if user exceeds inactive auto logout value
	if((Number(uActive) - Number(uLastActive)) > Number(idleLogoutTimeout))
	{
		// remove user from userlist
		deleteDiv("userlist_"+uuID+uRoom,"room_"+uRoom);

		// logout user
		if(Number(uID) == Number(uuID))
		{
			logout();
		}
	}

}

/*
* update watching webcam status
*
*/ 

function updateWatchingWebcamStatus(id,status)
{
	if(status == '1')
	{
		document.getElementById("watch_"+id).src = 'plugins/webcams/images/eyes.gif';
	}
	else
	{
		document.getElementById("watch_"+id).src = 'images/inv.gif';
	}
}

/*
* update user status
*
*/ 

function updateUserStatusMes(id,status)
{
	var showStatus = '';

	if(status != userStatusMes[0] && status != userStatusMes[1] && status != '0' && status != '1' && id != '-1')
	{
		showStatus = "&nbsp;["+decodeURI(userStatusMes[status])+"]";
	}

    if(document.getElementById("ustatusID_"+id) != null) {
	    document.getElementById("ustatusID_"+id).innerHTML = showStatus;
    }
    else {
        //console.debug("User ID: " + id + " does not exist.");
    }
}

/*
* update webcam status
*
*/ 

function updateWebcamStatus(id,status)
{
	if(status == '1')
	{
		document.getElementById("webcam_"+id).src = 'plugins/webcams/images/mini.gif';
	}
	else
	{
		document.getElementById("webcam_"+id).src = 'images/inv.gif';
	}
}

/*
* update user room count
*
*/

function updateUserRoomCount(uRoom, value)
{
    if(document.getElementById("room_"+uRoom) == null) {
        //console.debug('Room: ' + uRoom + ' does not exist.');
    }
    else {
        document.getElementById("userCount_"+uRoom).innerHTML =
            (document.getElementById("room_" + uRoom).children.length - 1).toString();
    }
}

/*
* update avatar
*
*/

function updateAvatar(uID, uAvatar, uRoom)
{
	// get path to current avatar
	// split path into array
    if(document.getElementById("avatar_"+uID) != null) {
	    var avatarFilePath = document.getElementById("avatar_"+uID).src.split("/");
    }
    else {
        //console.debug("User ID: " + uID + " does not exist.");
        return false;
    }

	// get length of path array
	var avatarFileName = avatarFilePath.length;

	// get the avatar file name
	avatarFileName = Number(avatarFileName)-1;

	// if avatar has changed, update avatar
	if(avatarFilePath[avatarFileName] != uAvatar)
	{
		document.getElementById("avatar_"+uID).src = "avatars/"+uAvatar;
	}
}

/*
* update displayName
*
*/

function updateDisplayName(userId, displayName, roomID, invisible)
{
    var userListEntry = $("#userlist_"+userId+roomID);
    userListEntry.find('.username').html(displayName);
    if(invisible == 1) {
        userListEntry.find('.username').addClass('ghost');
    }
    else {
        userListEntry.find('.username').removeClass('ghost');
    }
}

/*
* delete users div 
*
*/

function removeUsersDiv(uID, uRoom)
{
	// if div exists
	if(document.getElementById("userlist_"+uID))
	{
		// remove div
		var d = document.getElementById("room_"+uRoom);
		var oldDiv = document.getElementById("userlist_"+uID);
		d.remove(oldDiv);
	}

}

/*
* create select room list
*
*/

function createSelectRoomdiv(room, roomid, roomdel)
{
	var sel = document.getElementById('roomSelect');

	if(!document.getElementById("select_"+roomid))
	{
		var opt = document.createElement("option");
		opt.setAttribute("id","select_"+roomid);
		opt.value = roomid;
		opt.text = decodeURIComponent(room.replace("+"," "));

		if(roomID == roomid)
		{
			opt.setAttribute('selected','selected');	
		}

  		try 
		{
			// standards compliant; doesn't work in IE
    		sel.add(opt, null); 
  		}
  		catch(ex)
		{
			// IE only
    		sel.add(opt);
  		}

	}

}

/*
* create room list
*
*/

function createRoomsdiv(room,roomid,icon)
{
	// if div does not exist
	if(!document.getElementById("room_"+roomid))
	{
		// create div
		var ni = document.getElementById('userContainer');
		var newdiv = document.createElement('div');

        var roomName = decodeURIComponent(room.replace("+"," "));
		newdiv.setAttribute("id","room_"+roomid);
		newdiv.innerHTML =
            '<div class="roomheader" title="' + roomName +'" onclick=toggleHeader("room_'+roomid+'");>' +
                '<div class="room-div">' +
                    '<span style="float:left;">' +
                        '<img style="vertical-align:middle;" src="images/'+icon+'">&nbsp;' +
                        '<span class="roomname">' +
                            roomName +
                        '</span>'+
                    '</span>' +
                '</div> ' +
                '<span style="float:right;" class="usercount">' +
                    '[<span id="userCount_'+roomid+'">0</span>]' +
                '</span>' +
            '</div>';

        if(ni != null) {
		    ni.appendChild(newdiv);
        }

		if(roomid != roomID)
		{
			document.getElementById("room_"+roomid).style.height = "24px";
			document.getElementById("room_"+roomid).style.overflow = "hidden";
		}
	}
}

/*
* delete room div 
*
*/

function removeRoomsDiv(divID)
{
	// if div exists
	if(document.getElementById(divID))
	{
		// remove div
		var d = document.getElementById('userContainer');
		var olddiv = document.getElementById(divID);
		d.removeChild(olddiv);
	}
}

/*
* userlist - user panel
* appears when you click a username
*/

function userPanel(userName,targetUserName,targetUserId,roomId,userID,uAvatar,uBlock,uIP)
{
	// if user is Intelli-bot, disable options
	if(targetUserName.toLowerCase() == intelliBotName.toLowerCase())
	{
		return false;
	}
	// if div exists
	if(!document.getElementById("userpanel_"+targetUserId+roomId))
	{
		// create div
		var ni = document.getElementById("userlist_"+targetUserId+roomId);

		var newdiv = document.createElement('div');
		newdiv.setAttribute("id","userpanel_"+targetUserId+roomId);
		newdiv.className = "userInfo";

		// header
		newdiv.innerHTML =
            "<div class='userInfoTitle'>" +
                "<div style='float:left;width: 140px;overflow: hidden;height: 24px;' title='" + decodeURI(targetUserName) + "'>" +
                    "<img style='vertical-align:middle;' src='avatars/"+uAvatar+"'>&nbsp;"+decodeURI(targetUserName)+
                "</div>" +
                "<span style='float:right;' onclick='deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")'>" +
                    "<img src='images/close.gif'>&nbsp;" +
                "</span>" +
            "</div>";

		// used for style formatting only
		newdiv.innerHTML += "<div style='height:2px;'>&nbsp;</div>";

		// private chat
		if(privateOn && uID != targetUserId)
		{		
			// if user has no eCredits
			// disable option to send PM requests
			if(eCredits == 1 && document.getElementById("eCreditsID").innerHTML == '0')
			{
				newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\""+lang32+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/private.gif'><span style='padding-left:11px;'>"+lang33+"</span></div>";
			}
			else
			{
				if(groupPChat == 0)
				{
					newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\""+lang6+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/private.gif'><span style='padding-left:11px;'>"+lang33+"</span></div>";
				}
				else
				{
					newdiv.innerHTML +=
                        "<div onmouseover=\"this.className='highliteOn'\" " +
                            "onmouseout=\"this.className='highliteOff'\" " +
                            "onclick='clearWhisper();createPChatDiv(\""+userName+"\",\""+targetUserName+"\",\""+uID+"\",\""+targetUserId+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")'" +
                            " class='highliteOff'>" +
                                "<img style='vertical-align:middle;' src='images/usermenu/private.gif'>" +
                                "<span style='padding-left:11px;'>"+lang33+"</span>" +
                        "</div>";
				}
			}
		}
		
		// whisper
        // disable whispering completely.
		if(false)//whisperOn && uID != targetUserId)
		{
			// if user has no eCredits
			// disable option to send webcam requests
			if(eCredits == 1 && document.getElementById("eCreditsID").innerHTML == '0')
			{
				newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\""+lang32+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/private.gif'><span style='padding-left:10px;'>"+lang34+"</span></div>";
			}
			else
			{
				if(groupPChat == 0)
				{
					newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\""+lang6+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/private.gif'><span style='padding-left:10px;'>"+lang34+"</span></div>";
				}
				else
				{
					newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='whisperUser(\""+targetUserName+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/private.gif'><span style='padding-left:10px;'>"+lang34+"</span></div>";
				}

			}

		}

		// webcam
		if(webcamsOn && uID != targetUserId)
		{

			// if user has no eCredits
			// disable option to send webcam requests
			if(eCredits == 1 && document.getElementById("eCreditsID").innerHTML == '0')
			{
				newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\""+lang32+"\")' class='highliteOff'><img style='vertical-align:middle;' src='plugins/webcams/images/mini.gif'><span style='padding-left:6px;'>"+lang35+"</span></div>";
			}
			else
			{
				if(groupWatch == 0)
				{
					newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\""+lang6+"\")' class='highliteOff'><img style='vertical-align:middle;' src='plugins/webcams/images/mini.gif'><span style='padding-left:6px;'>"+lang35+"</span></div>";
				}
				else
				{
					newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='requestViewWebcam(\""+targetUserName+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='plugins/webcams/images/mini.gif'><span style='padding-left:6px;'>"+lang35+"</span></div>";
				}
			}
		}
		
		// profile
        var profileID;
		if(profileRef)
		{
			profileID = targetUserName;
		}
		else
		{
			profileID = targetUserId;
		}

		if(profileOn)
		{
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='viewProfile(\""+profileID+"\",\""+targetUserName+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/profile.gif'><span style='padding-left:10px;'>"+lang36+"</span></div>";
		}

        if((uID == targetUserId) && userTypeId == 3) {
            newdiv.innerHTML +=
                "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='viewSheet(\""+userID+"\",\""+targetUserName+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'>" +
                    "<img style='vertical-align:middle;' src='images/usermenu/profile.gif'>" +
                    "<span style='padding-left:10px;'>View Sheet</span>" +
                    "</div>" +
                    "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='viewDice(\""+userID+"\",\""+targetUserName+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'>" +
                    "<img style='vertical-align:middle;' src='images/usermenu/profile.gif'>" +
                    "<span style='padding-left:10px;'>Dice Roller</span>" +
                    "</div>" +
                    "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='viewRequests(\""+userID+"\",\""+targetUserName+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'>" +
                    "<img style='vertical-align:middle;' src='images/usermenu/profile.gif'>" +
                    "<span style='padding-left:10px;'>Requests</span>" +
                    "</div>";
        }

        if((admin && uID != targetUserId) || (moderator && uID != targetUserId))
        {
            // view sheet
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('/characters/stView/"+userID+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>View Sheet</span></div>";

            // view requests
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('/request.php?action=st_list&character_id="+userID+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>View Requests</span></div>";

            // view bluebooks
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('/bluebook.php?action=st_list&character_id="+userID+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>View Bluebook</span></div>";

            // view log
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('/character.php?action=log&character_id="+userID+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>View Log</span></div>";
        }

        if(uID != targetUserId && share)
		{
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"shareFiles\",\"280\",\"300\",\"200\",\"plugins/share/?shareWithUserId="+targetUserId+"\",\"\");' class='highliteOff'><img style='vertical-align:middle;' src='images/share.gif'><span style='padding-left:7px;'>Share Files</span></div>";
		}

		if(uID != targetUserId && uBlock == 1)
		{
			// block user
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='blockUsers(\"block\",\""+targetUserId+"\");showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\"You have blocked "+decodeURI(targetUserName)+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/block.gif'><span style='padding-left:10px;'>"+lang37+"</span></div>";

			// unblock user
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='blockUsers(\"unblock\",\""+targetUserId+"\");showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\"You have unblocked "+decodeURI(targetUserName)+"\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/unblock.gif'><span style='padding-left:10px;'>"+lang38+"</span></div>";

			// report abuse
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"report\",\"280\",\"360\",\"200\",\"templates/"+styleFolder+"/report.php?id="+targetUserId+"\",\"\");;deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/report.gif'><span style='padding-left:7px;'>"+lang39+"</span></div>";
		}

		if(admin && uID != targetUserId || moderator && uID != targetUserId)// || roomOwner && uID != uuID)
		{
			// silence
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='adminControls(\""+targetUserId+"\",\"SILENCE\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>"+lang40+"</span></div>";

			// kick
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='adminControls(\""+targetUserId+"\",\"KICK\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>"+lang41+"</span></div>";

            // ban
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='adminControls(\""+targetUserId+"\",\"BAN\");deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>"+lang42+"</span></div>";
		}

        if((admin && uID != targetUserId) || (moderator && uID != targetUserId))
        {
            // show IP
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('http://www.infosniper.net/index.php?ip_address="+uIP+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>IP: "+uIP+"</span></div>";
        }

		if((moderator || admin) && uID == targetUserId) {
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('http://wantonwicked.gamingsandbox.com/dieroller.php?action=ooc') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>Dice Roller</span></div>";
		}

		if(admin && uID == targetUserId)
		{
			// admin area
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('admin/') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>Admin Area</span></div>";
		}

		if(moderatedChat == '1' && admin && uID == targetUserId || moderatedChat == '1' && moderator && uID == targetUserId)
		{
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"mc\",\"400\",\"600\",\"100\",\"plugins/moderated_chat/index.php\",\"\");;deleteDiv(\"userpanel_"+targetUserId+roomId+"\",\"userlist_"+targetUserId+roomId+"\")' class='highliteOff'><img style='vertical-align:middle;' src='plugins/moderated_chat/images/moderatedchat.gif'><span style='padding-left:10px;'>"+lang43+"</span></div>";
		}

        if(ni != null) {
		    ni.appendChild(newdiv);
        }

	}

}

/*
* Clear whisper box when new private chat window is opened
*
*/

function clearWhisper()
{
   document.getElementById("whisperID").value = "";
}

/*
* admin functions
*
*/

function adminControls(targetUserId,doAction)
{
	var param = '?';
	param += '&uid=' + escape(uID);
	param += '&uname=' + escape(userName);
	param += '&to_user_id=' + escape(targetUserId);
	param += '&umessage=' + escape(doAction);	
	param += '&uroom=' + roomID;
	param += '&usfx=' + escape(sfx);
	param += '&umid=' + displayMDiv;	

	// if ready to send message to DB
	if (sendReq.readyState == 4 || sendReq.readyState == 0) 
	{
		if(admin && userName != targetUserId || moderator && userName != targetUserId || roomOwner && userName != targetUserId)
		{
			sendReq.open("POST", 'includes/sendData.php?rnd='+ Math.random(), true);
			sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			sendReq.onreadystatechange = handleSendChat;
			sendReq.send(param);
		}
		else
		{
			showInfoBox("system","220","300","200","",lang44);
		}

	}

}

/*
* userlist - view profile
*
*/

function viewProfile(uID,uUser) {
	window.open('/wiki/?n=Players.'+uUser.stripNonAlpha());//profileUrl+uID,'','');
}

function viewSheet(characterId) {
    window.open('/characters/viewOwn/'+characterId);
}

function viewDice(characterId) {
    window.open('/dieroller.php?action=character&character_id='+characterId);
}

function viewRequests(characterId) {
    window.open('/request.php?action=list&character_id='+characterId);
}

/*
* delete div
* 
*/

function deleteDiv(divID,divContainer)
{
	// if div exists
	if(document.getElementById(divID))
	{
		// remove div
		var d = document.getElementById(divContainer);
		var olddiv = document.getElementById(divID);
		d.removeChild(olddiv);
	}

}
/*
* Gets the browser specific XmlHttpRequest Object 
*
*/

function getXmlHttpRequestObject() 
{
	if (window.XMLHttpRequest) 
	{
		return new XMLHttpRequest();
	} 
	else if(window.ActiveXObject) 
	{
		return new ActiveXObject("Microsoft.XMLHTTP");
	} 
	else 
	{
		alert("Status: Cound not create XmlHttpRequest Object.  Consider upgrading your browser.");
        return false;
	}
}