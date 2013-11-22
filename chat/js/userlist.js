/*
* share
*
*/

var share = 0;

/*
* create users div 
*
*/

function createUsersDiv(uuID, userID, uUser, uAvatar, uWebcam, uPrevRoom, uRoom, uActivity, uStatus, uWatch, uAdmin, uModerator, uSpeaker, uActive, uLastActive, uIP, userTypeId)
{
    var uUserOriginal = uUser;
    uUser = uUser.replaceAll('\'', '&#39;');
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
        showAdminID = ' (Admin) ';
        uBlock = 0;
    }
    if(userTypeId == 6) {
        userClass = 'user-admin';
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
                    "<div>" +
                        "<div class='scrollable-container user-div'>" +
                            "<div class='scrollable'>" +
                                "<img id='avatar_" + uuID + "' style='vertical-align:middle;' src='avatars/" + uAvatar + "'>" +
                                "&nbsp;" +
                                "<span class='" + userClass + " username' onclick='userPanel(\"" + userName + "\",\"" + uUser + "\",\"" + uuID + "\",\"" + uRoom + "\",\"" + userID + "\",\"" + uAvatar + "\",\"" + uBlock + "\",\"" + uIP + "\")' ondblclick='createPChatDiv(\"" + userName + "\",\"" + uUser + "\",\"" + uuID + "\",\"" + uID + "\");deleteDiv(\"userpanel_" + uuID + uID + "\", \"userlist_" + uuID + uID + "\");'>" +
                                decodeURI(uUser) + showAdminID + "</span>" +
                                "<span id='ustatusID_" + uuID + "'></span>" +
                            "</div>" +
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
        console.debug("User ID: " + id + " does not exist.");
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
        console.debug('Room: ' + uRoom + ' does not exist.');
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
        console.debug("User ID: " + uID + " does not exist.");
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
		opt.text = decodeURI(room.replace("+"," "));

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

function createRoomsdiv(room,roomid,roomdel)
{
	// if div does not exist
	if(!document.getElementById("room_"+roomid))
	{
		// create div
		var ni = document.getElementById('userContainer');
		var newdiv = document.createElement('div');

		newdiv.setAttribute("id","room_"+roomid);
		newdiv.className = "";
		newdiv.innerHTML =
            '<div class="roomheader" onclick=toggleHeader("room_'+roomid+'");>' +
                '<div class="room-div">' +
                    '<span style="float:left;">' +
                        '<img style="vertical-align:middle;" src="images/mini.gif">' +
                        '&nbsp;' +
                        '<span class="roomname">'+
                        decodeURI(room.replace("+"," "))+
                        '</span>&nbsp;' +
                    '</span>' +
                '</div> ' +
                '<span style="float:right;" class="usercount">' +
                    '[<span id="userCount_'+roomid+'">0</span>]' +
                '</span> ' +
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

function userPanel(userName,uUser,uuID,uRoom,userID,uAvatar,uBlock,uIP)
{
	// if user is Intelli-bot, disable options
	if(uUser.toLowerCase() == intelliBotName.toLowerCase())
	{
		return false;
	}
	// if div exists
	if(!document.getElementById("userpanel_"+uuID+uRoom))
	{
		// create div
		var ni = document.getElementById("userlist_"+uuID+uRoom);

		var newdiv = document.createElement('div');
		newdiv.setAttribute("id","userpanel_"+uuID+uRoom);
		newdiv.className = "userInfo";

		// header
		newdiv.innerHTML =
            "<div class='userInfoTitle'>" +
                "<span style='float:left;'>" +
                    "<img style='vertical-align:middle;' src='avatars/"+uAvatar+"'>&nbsp;"+decodeURI(uUser)+
                "</span>" +
                "<span style='float:right;' onclick='deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")'>" +
                    "<img src='images/close.gif'>&nbsp;" +
                "</span>" +
            "</div>";

		// used for style formatting only
		newdiv.innerHTML += "<div style='height:2px;'>&nbsp;</div>";

		// private chat
		if(privateOn && uID != uuID)
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
                        "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='clearWhisper();deleteDiv(\""+uID+"_"+uuID+"\",\"pWin\");createPChatDiv(\""+userName+"\",\""+uUser+"\",\""+uuID+"\",\""+uID+"\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/private.gif'><span style='padding-left:11px;'>"+lang33+"</span></div>";
				}
			}
		}
		
		// whisper
		if(whisperOn && uID != uuID)
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
					newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='whisperUser(\""+uUser+"\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/private.gif'><span style='padding-left:10px;'>"+lang34+"</span></div>";
				}

			}

		}

		// webcam
		if(webcamsOn && uID != uuID)
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
					newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='requestViewWebcam(\""+uUser+"\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='plugins/webcams/images/mini.gif'><span style='padding-left:6px;'>"+lang35+"</span></div>";
				}

			}

		}
		
		// profile
		if(profileRef)
		{
			var profileID = uUser;
		}
		else
		{
			var profileID = uuID;
		}

		if(profileOn)
		{
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='viewProfile(\""+profileID+"\",\""+uUser+"\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/profile.gif'><span style='padding-left:10px;'>"+lang36+"</span></div>";
		}

		if(uID != uuID && share)
		{
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"shareFiles\",\"280\",\"300\",\"200\",\"plugins/share/?shareWithUser="+uUser+"\",\"\");' class='highliteOff'><img style='vertical-align:middle;' src='images/share.gif'><span style='padding-left:7px;'>Share Files</span></div>";
		}

		if(uID != uuID && uBlock == 1)
		{
			// block user
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='blockUsers(\"block\",\""+uuID+"\");showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\"You have blocked "+decodeURI(uUser)+"\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/block.gif'><span style='padding-left:10px;'>"+lang37+"</span></div>";

			// unblock user
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='blockUsers(\"unblock\",\""+uuID+"\");showInfoBox(\"system\",\"220\",\"300\",\"200\",\"\",\"You have unblocked "+decodeURI(uUser)+"\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/unblock.gif'><span style='padding-left:10px;'>"+lang38+"</span></div>";

			// report abuse
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"report\",\"280\",\"360\",\"200\",\"templates/"+styleFolder+"/report.php?id="+uUser+"\",\"\");;deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/report.gif'><span style='padding-left:7px;'>"+lang39+"</span></div>";
		}

		if(admin && uID != uuID || moderator && uID != uuID)// || roomOwner && uID != uuID)
		{
			// silence
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='adminControls(\""+uUser+"\",\"SILENCE\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>"+lang40+"</span></div>";

			// kick
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='adminControls(\""+uUser+"\",\"KICK\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>"+lang41+"</span></div>";

			if(admin && uID != uuID || moderator && uID != uuID)
			{
				// ban
				newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='adminControls(\""+uUser+"\",\"BAN\");deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>"+lang42+"</span></div>";
			}
			
		}
		
		if(admin && uID == uuID || moderator && uID != uuID)
		{
            // view sheet
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('/view_sheet.php?action=st_view_xp&view_character_id="+userID+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>View Sheet</span></div>";

            // view requests
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('/request.php?action=st_list&character_id="+userID+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>View Requests</span></div>";

            // view log
            newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('/character.php?action=log&character_id="+userID+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>View Log</span></div>";

			// show IP
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('http://www.infosniper.net/index.php?ip_address="+uIP+"') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>IP: "+uIP+"</span></div>";
		}

		if(admin && uID == uuID)
		{
			// admin area
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick=newWin('admin/') class='highliteOff'><img style='vertical-align:middle;' src='images/usermenu/tool.gif'><span style='padding-left:10px;'>Admin Area</span></div>";
		}

		if(moderatedChat == '1' && admin && uID == uuID || moderatedChat == '1' && moderator && uID == uuID)
		{
			newdiv.innerHTML += "<div onmouseover=\"this.className='highliteOn'\" onmouseout=\"this.className='highliteOff'\" onclick='showInfoBox(\"mc\",\"400\",\"600\",\"100\",\"plugins/moderated_chat/index.php\",\"\");;deleteDiv(\"userpanel_"+uuID+uRoom+"\",\"userlist_"+uuID+uRoom+"\")' class='highliteOff'><img style='vertical-align:middle;' src='plugins/moderated_chat/images/moderatedchat.gif'><span style='padding-left:10px;'>"+lang43+"</span></div>";
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

function adminControls(tUser,doAction)
{
	var param = '?';
	param += '&uid=' + escape(uID);
	param += '&uname=' + escape(userName);
	param += '&toname=' + escape(tUser);
	param += '&umessage=' + escape(doAction);	
	param += '&uroom=' + roomID;
	param += '&usfx=' + escape(sfx);
	param += '&umid=' + displayMDiv;	

	// if ready to send message to DB
	if (sendReq.readyState == 4 || sendReq.readyState == 0) 
	{
		if(admin && userName != tUser || moderator && userName != tUser || roomOwner && userName != tUser)
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

function viewProfile(uID,uUser)
{
	var win = window.open('/wiki/?n=Players.'+uUser.stripNonAlpha());//profileUrl+uID,'','');
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