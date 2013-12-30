/*
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

}