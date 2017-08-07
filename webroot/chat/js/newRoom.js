
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

    var params = {
        action: 'add',
        roomName: encodeURIComponent(document.getElementById("roomName").value),
        roomPass: document.getElementById("roomPass").value,
        user_id: uID
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
            document.location = 'index.php?roomID=' + response.roomId + '&userId=' + uID;
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
