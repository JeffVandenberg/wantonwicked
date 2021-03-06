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
		showInfoBox("system","220","300","200","",'You may not post Youtube videos.');
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

    if(ircCommand[0] == '/beats') {
		if(admin || moderator) {
			beats.openSt();
		} else {
			beats.openPlayer();
		}
        clrMessageInput(inputMDiv);
		return false;
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

	param += '&uid=' + encodeURI(uID);
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
		var lastSound = null;
		for (i = 0; i < xmldoc.getElementsByTagName("usermessage").length;)
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
						null, // sound
						userMessageArray[3], // user
						userMessageArray[4], // to user
						userMessageArray[8] // Time
					);
			lastSound = userMessageArray[7];

			lastMessageID = userMessageArray[0];	

			// loop
			i++;			
		}

		if(lastSound) {
			doSound(lastSound);
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

		// check version
		var remoteVersion = parseInt(xmldoc.getElementsByTagName("version")[0].childNodes[0].nodeValue);
		if(remoteVersion > version) {
			$.toast({
				text: "There has been an update to the site. <a href='#' onclick='window.location.reload();'>Reload?</a>",
				heading: 'New Site Version',
				position: {top:20, right:70},
				hideAfter: false,
				icon: 'info',
                allowToastClose: true
			});
			version = remoteVersion;
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

		if((mDiv == 'chatContainer') && (message.indexOf('@' + userName) > -1)) {
			newdiv.className = 'chatMessage user-mention';
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

    doSound(sfx);
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
				user_id: uID,
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
					user_id: uID,
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

var beats = {
	help: function () {
		alert('Type /beats to open up the beat tracker for your character.');
	},

	openPlayer: function() {
		window.open('/characters/beats/' + userID)
	},

	openSt: function() {
		window.open('/characters/stBeats')
	}
};
