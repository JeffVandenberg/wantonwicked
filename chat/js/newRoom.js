
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
}