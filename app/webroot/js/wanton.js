
$(function () {
    $(".menu")
        .menubar();

    wantonWickedTime.runClock('#server-time');
    $(document).ajaxStart(
        function() {
            $("#busy-indicator").fadeIn();
        });
    $(document).ajaxComplete(
        function() {
            $("#busy-indicator").fadeOut();
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
    $.get("/favors.php?action=view&favor_id=" + favorId, function(content) {
        $("#favorPaneContent").html(content).dialog({
            title: 'View Favor'
        });
    });
    return false;
}
function transferFavor(favorId) {
    $.get("/favors.php?action=transfer&favor_id=" + favorId, function(content) {
        $("#favorPaneContent").html(content).dialog({
            title: 'Transfer Favor'
        });
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
        $.get("/favors.php?action=break&favorId=" + favorId, function(content) {
            alert(content);
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
