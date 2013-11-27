var difference = 0;
function UpdateTime() {
    setTimeout("UpdateTime();", 1000);
    $("#server-time").html(MakeTime());
}

/**
 * @return {string}
 */
function MakeTime() {
    var timer = new Date(new Date().getTime() - difference);

    var hhN = timer.getHours();
    var hh, AP;
    if (hhN > 12) {
        hh = String(hhN - 12);
        AP = "pm";
    }
    else if (hhN == 12) {
        hh = "12";
        AP = "pm";
    }
    else if (hhN == 0) {
        hh = "12";
        AP = "am";
    }
    else {
        hh = String(hhN);
        AP = "am";
    }
    var mm = String(timer.getMinutes());
    var ss = String(timer.getSeconds());
    return "Server Time: " + hh + ((mm < 10) ? ":0" : ":") + mm + ((ss < 10) ? ":0" : ":") + ss + AP;
}

function showClock() {
    UpdateTime();
}

$(function () {
    $.get('/server_time.php', null, function(time) {
        var serverTime = new Date(time);
        difference = new Date().getTime() - serverTime.getTime();
        showClock();
    });

    $("#logo").click(function() {
        document.location = "/";
    });
});

function giveFavor(characterId) {
    $("#favorPaneContent").load("/favors.php?action=give&character_id=" + characterId, function () {
        $("#favorPane").css("display", "block")
    });
    return false;
}

function viewFavor(favorId) {
    $("#favorPaneContent").load("/favors.php?action=view&favor_id=" + favorId, function () {
        $("#favorPane").css("display", "block")
    });
    return false;
}
function transferFavor(favorId) {
    $("#favorPaneContent").load("/favors.php?action=transfer&favor_id=" + favorId, function () {
        $("#favorPane").css("display", "block")
    });
    return false;
}
function dischargeFavor(favorId) {
    if (confirm('Are you sure you want to discharge the favor?')) {
        $.ajax({
            url: "/favors.php?action=discharge&favorId=" + favorId,
            type: "post",
            dataType: "html",
            success: function (response, status, request) {
                alert(response);
                //window.location.reload();
            },
            error: function (request, message, exception) {
                alert('There was an error submitting the request. Please try again.');
            }
        });
    }
    return false;
}

function breakFavor(favorId) {
    if (confirm('Are you sure you want to break the favor?')) {
        $.ajax({
            url: "/favors.php?action=break&favorId=" + favorId,
            type: "post",
            dataType: "html",
            success: function (response, status, request) {
                alert(response);
                //window.location.reload();
            },
            error: function (request, message, exception) {
                alert('There was an error submitting the request. Please try again.');
            }
        });
    }
    return false;
}

function createTerritory() {
    $("#territoryPaneContent").load("/territory.php?action=add", function () {
        $("#territoryPane").css("display", "block")
    });
    return false;
}

function viewTerritory(id) {
    $("#territoryPaneContent").load("/territory.php?action=view&id=" + id, function () {
        $("#territoryPane").css("display", "block")
    });
    return false;
}

function adminAddCharacterToTerritory(id) {
    $("#territoryPaneContent").load("/territory.php?action=admin_add_character&id=" + id, function () {
        $("#territoryPane").css("display", "block")
    });
    return false;
}

function RefreshAdminTerritoryCharacterList(id) {
    $("#associatedCharacters").load("/territory.php?action=get_admin_associated_characters&id=" + id);
}

function adminRemoveCharacterFromTerritory(characterId, territoryId, characterName) {
    if (confirm("Do you want to remove " + characterName + "?")) {
        $.get(
            "/territory.php?action=admin_remove_character&id=" + characterId,
            function (data) {
                alert(data);
                RefreshAdminTerritoryCharacterList(territoryId);
            }
        );
    }
    return false;
}

function poachTerritory(territoryId, characterId, territoryName, link) {
    if (confirm("Do you want to poach from " + territoryName + "?")) {
        $.get(
            "/territory.php?action=poach&id=" + territoryId + "&character_id=" + characterId,
            function (data) {
                alert(data);
                link.style.visibility = 'hidden';
            }
        );
    }
}

function feedFromTerritory(territoryId, characterId, territoryName, link) {
    if (confirm("Do you want to feed from " + territoryName + "?")) {
        $.get(
            "/territory.php?action=feed&id=" + territoryId + "&character_id=" + characterId,
            function (data) {
                alert(data);
                link.style.visibility = 'hidden';
            }
        );
    }
}

function leaveTerritory(characterId, territoryId, territoryName, link) {
    if (confirm("Do you want to stop feeding from " + territoryName + "?")) {
        $.get(
            "/territory.php?action=admin_remove_character&id=" + characterId,
            function (data) {
                alert(data);
                link.style.visibility = 'hidden';
            }
        );
    }
    return false;
}

function refreshAbpRuleList(data) {
    if (data) {
        alert(data);
    }
    $("#abpRuleList").load("/abp.php?action=get_abp_rule_list");
}

