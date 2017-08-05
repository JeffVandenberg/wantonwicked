$(function () {
    $(document).foundation();

    wantonWickedTime.runClock('#server-time');
    $(document).ajaxStart(
        function () {
            $("#busy-indicator").fadeIn();
        });
    $(document).ajaxComplete(
        function () {
            $("#busy-indicator").fadeOut();
        });
    $("#logo").click(function () {
        document.location.href = "/";
    });

    // general method for removing required properties when cancelling out of a form
    $('input[value="Cancel"], button[value="cancel"], button[value=Cancel]').click(function () {
        $('input[required],textarea[required],select[required]').attr('required', false);
    });

    $('form').submit(function() {
        $(this).find('.tinymce-textarea').attr('required', false)
    });

    $("input[type=submit]").addClass("button");
});

function giveFavor(characterId) {
    $("#favorPaneContent").load("/favors.php?action=give&character_id=" + characterId, function () {
        $("#favorPane").css("display", "block")
    });
    return false;
}

function viewFavor(favorId) {
    $.get("/favors.php?action=view&favor_id=" + favorId, function (content) {
        $("#favorPaneContent").html(content).dialog({
            title: 'View Favor'
        });
    });
    return false;
}
function transferFavor(favorId) {
    $.get("/favors.php?action=transfer&favor_id=" + favorId, function (content) {
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
        $.get("/favors.php?action=break&favorId=" + favorId, function (content) {
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

// Strips HTML and PHP tags from a string
// returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
// example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
// returns 2: '<p>Kevin van Zonneveld</p>'
// example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
// returns 3: '<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>'
// example 4: strip_tags('1 < 5 5 > 1');
// returns 4: '1 < 5 5 > 1'
function strip_tags(str, allowed_tags) {

    var key = '', allowed = false;
    var matches = [];
    var allowed_array = [];
    var allowed_tag = '';
    var i = 0;
    var k = '';
    var html = '';
    var replacer = function (search, replace, str) {
        return str.split(search).join(replace);
    };
    // Build allowes tags associative array
    if (allowed_tags) {
        allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }
    str += '';

    // Match tags
    matches = str.match(/(<\/?[\S][^>]*>)/gi);
    // Go through all HTML tags
    for (key in matches) {
        if (isNaN(key)) {
            // IE7 Hack
            continue;
        }

        // Save HTML tag
        html = matches[key].toString();
        // Is tag not in allowed list? Remove from str!
        allowed = false;

        // Go through all allowed tags
        for (k in allowed_array) {            // Init
            allowed_tag = allowed_array[k];
            i = -1;

            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + '>');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + ' ');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('</' + allowed_tag);
            }

            // Determine
            if (i == 0) {
                allowed = true;
                break;
            }
        }
        if (!allowed) {
            str = replacer(html, "", str); // Custom replace. No regexing
        }
    }
    return str;
}

tinymce.baseURL = '/js/tinymce';
tinymce.suffix = '.min';
tinymce.init({
    selector: "textarea.tinymce-textarea",
    menubar: false,
    height: 200,
    paste_preprocess: function (pl, o) {
        //example: keep bold,italic,underline and paragraphs
        //o.content = strip_tags( o.content,'<b><u><i><p>' );

        // remove all tags => plain text
        o.content = strip_tags(o.content, '<br>');
    },
    setup: function(editor) {
        editor.on('blur', function() {
            editor.save();
        });
    },
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace wordcount visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste textcolor placeholder"
    ],
    toolbar: "undo redo | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
});

var addUrlParam = function(search, key, val){
    var newParam = encodeURIComponent(key) + '=' + encodeURIComponent(val),
        params = '?' + newParam;

    // If the "search" string exists, then build params from it
    if (search) {
        // Try to replace an existance instance
        params = search.replace(new RegExp('([?&])' + encodeURIComponent(key) + '[^&]*'), '$1' + newParam);

        // If nothing was replaced, then add the new param to the end
        if (params === search) {
            params += '&' + newParam;
        }
    }

    return params;
};

function copyToClipboard(selector, callback) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(selector).text()).select();
    document.execCommand("copy");
    $temp.remove();

    if(callback) {
        callback()
    }
}
