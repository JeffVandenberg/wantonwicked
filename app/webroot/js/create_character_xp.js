// base variables
var edit_xp = false;
var page_action = "new";
var character_id = 0;

// element types
var attribute = 1;
var skill = 2;
var merit = 3;
var supernatural = 4;
var power_trait = 5;
var morality = 6;

// lists
var attribute_list = [];
var i;
for (i = 0; i < 9; i++) {
    attribute_list.push('attribute' + i);
}
var attributes = {
    intelligence: 'attribute0',
    wits        : 'attribute3',
    resolve     : 'attribute6',
    strength    : 'attribute1',
    dexterity   : 'attribute4',
    stamina     : 'attribute7',
    presence    : 'attribute2',
    manipulation: 'attribute5',
    composure   : 'attribute8',
    getId: function(attributeName) {
        var name = attributeName.toLowerCase();
        return this[name];
    }
};

var skill_list = [];
for (i = 0; i < 24; i++) {
    skill_list.push('skill' + i);
}
var attributesList = ['intelligence', 'strength', 'presence', 'wits', 'dexterity', 'manipulation', 'resolve', 'stamina', 'composure'];
var skill_list_proper = ["Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry"];
var skill_list_proper_mage = ["Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry", "Rote Specialty"];
var renown_list = ["purity", "glory", "honor", "wisdom", "cunning"];

// experience
var attribute_xp_base = 135;
var attribute_xp = 135;
var skill_xp_base = 105;
var purified_skill_xp_base = 141;
var skill_xp = 105;
var merit_xp_base = 32;
var purified_merit_xp_base = 44;
var merit_xp = 32;
var general_xp_base = 35;
var general_xp = 35;
var supernatural_xp = 0;
var vampire_xp_base = 30;
var werewolf_xp_base = 16;
var mage_xp_base = 75;
var ghoul_xp_base = 30;
var promethean_xp_base = 30;
var changeling_xp_base = 40;
var geist_xp_base = 44;
var purified_xp_base = 38;
var possessed_xp_base = 40;
var changing_breed_xp_base = 76;

$(function() {
    general_xp_base = parseInt($("#general_xp").attr('value'));
    general_xp = general_xp_base;

    $(document).on('change', '#character-type', function() {
        changeSheet($(this).val());
    });
    $(document).on('change', '.specialty-skill-update-create', function() {
        updateSkillXP();
    });
    $("#xp-spent").blur(function() {
        var amount = parseInt($("#xp-spent").val());
        if(isNaN(amount)) {
            $(this).val('0');
        }
        else {
            if(amount <= 0) {
                $(this).val('0')
            }
            else {
                if($("#xp-gained").val() != '0') {
                    alert('You may not add and remove XP at the same time.');
                    $(this).val('0');
                }
                if(amount > parseInt($("#current-experience").val())) {
                    alert('You may not spend more than their current experience.');
                    $(this).val('0');
                }
            }
        }
    });
    $("#xp-gained").blur(function() {
        var amount = parseInt($("#xp-gained").val());
        if(isNaN(amount)) {
            $(this).val('0');
        }
        else {
            if(amount <= 0) {
                $(this).val('0')
            }
            else {
                if($("#xp-spent").val() != '0') {
                    alert('You may not add and remove XP at the same time.');
                    $(this).val('0');
                }
                var allowedBonus = parseInt($("#bonus-xp-cap").val()) - parseInt($("#bonus-received").val());
                if((amount != 0) && (amount > allowedBonus)) {
                    alert('You may not give more than ' + allowedBonus);
                    $(this).val('0');
                }
            }
        }
    });

    //$("input.autocomplete").autocomplete({
    //    source: function(request, response) {
    //        $.ajax({
    //            url: '/character/searchPowers',
    //            data: {
    //                term: request.term,
    //                type: $(this)[0].element.attr('powertype')
    //            },
    //            success: function(data) {
    //                response(data);
    //            }
    //        });
    //    },
    //    search: function(e, ui) {
    //        alert('hello');
    //    }
    //});

    $("form").submit(function() {
        // check if ST edit mode
        if(page_action == "st_view") {
            if(($("#xp-spent").val() != '0') || ($("#xp-gained").val() != '0')) {
                if($.trim($("#xp-note").val()) === '') {
                    alert('You must provide a note explaining the XP Modification');
                    return false;
                }

                if(parseInt($('#xp-gained').val()) + parseInt($("#bonus-received").val()) > parseInt($("#bonus-xp-cap").val())) {
                    alert('You can\'t go above the character\'s monthly bonus cap.');
                    return false;
                }
            }

        }
        return true;
    });
});

function changeDots(element_type, element_name, value, number_of_dots, remove) {
    // if is the same value then set to 0
    var element = $("#" + element_name);
    if ((value == element.val()) && remove) {
        if ((element_type == attribute) || (element_type == power_trait) || (element_type == morality)) {
            value = 1;
        }
        else {
            value = 0;
        }
    }

    // determine character type
    var character_type = getCharacterType().toLowerCase().replace(/\s/g, '_');

    // cycle through the dots to fill up the values up to the selected value
    for (var i = 1; i <= Number(number_of_dots); i++) {
        if (i <= value) {
            $("#" + element_name + "-dot" + i).attr('src', "img/" + character_type + "_filled.gif");
        }
        else {
            $("#" + element_name + "-dot" + i).attr('src', "img/empty.gif");
        }
    }

    element.val(value);
}

function updateTraits() {
    // willpower
    var resolve = Number($("#" + attributes.resolve).val()) + checkBonusAttribute(attributes.resolve);
    var composure = Number($("#" + attributes.composure).val()) + checkBonusAttribute(attributes.composure);

    changeDots(0, "willpower-perm", (resolve + composure), 10, false);
    changeDots(0, "willpower-temp", (resolve + composure), 10, false);

    // health
    var stamina = Number($("#" + attributes.stamina).val()) + checkBonusAttribute(attributes.stamina);
    var size = Number($("#size").val());
    changeDots(0, "health", (stamina + size), 15, false);

    // defense
    var wits = Number($("#" + attributes.wits).val()) + checkBonusAttribute(attributes.wits);
    var dexterity = Number($("#" + attributes.dexterity).val()) + checkBonusAttribute(attributes.dexterity);
    var defense = wits;

    if (dexterity < wits) {
        defense = dexterity;
    }
    $("#defense").val(defense);

    // initiative
    $("#initiative_mod").val((dexterity + composure));

    // speed
    var strength = Number($("#" + attributes.strength).val()) + checkBonusAttribute(attributes.strength);

    $("#speed").val((size + strength + dexterity));
}

function checkBonusAttribute(attribute) {
    var attributeId = attributesList.indexOf($('#bonus_attribute').val().toLowerCase());
    if ('attribute'+attributeId == attribute) {
        return 1;
    }
    else {
        return 0;
    }
}

function updateXP(element_type) {
    switch (element_type) {
        case attribute:
            updateAttributeXP();
            displayBonusDot();
            break;
        case skill:
            updateSkillXP();
            break;
        case merit:
            updateMeritXP();
            break;
        case supernatural:
            updateSupernaturalXP();
            break;
        case power_trait:
            updateMeritXP();
            break;
        case morality:
            break;
        default:
            alert('unknown element type: ' + element_type);
    }
    updateGeneralXP();
}

function updateAttributeXP() {
    var i;
    attribute_xp = attribute_xp_base;
    for (i = 0; i < attribute_list.length; i++) {
        var attribute_value = $("#"+attribute_list[i]).val();
        attribute_xp -= ((Number(attribute_value) * (Number(attribute_value) + 1)) * 5) / 2 - 5;
    }

    if (attribute_xp > 0) {
        $('#attribute_xp').val(attribute_xp);
    }
    else {
        $('#attribute_xp').val(0);
    }
}

function updateSkillXP() {
    var i;
    var character_type = getCharacterType();
    if (character_type === 'Purified') {
        skill_xp = purified_skill_xp_base;
    }
    else {
        skill_xp = skill_xp_base;
    }

    for (i = 0; i < skill_list.length; i++) {
        var skill_value = document.getElementById(skill_list[i]).value;
        skill_xp -= getSkillCost(i, skill_value, character_type);
    }

    i = 0;
    var specialties = 0;
    while (document.getElementById('skill_spec' + i)) {
        if ((document.getElementById('skill_spec' + i ).value != '')
            && (document.getElementById('skill_spec' + i + '_selected').value != 'Rote Specialty')) {
            specialties++;
        }
        i++;
    }

    if ((character_type != 'Werewolf'
        && character_type != 'Changeling' )
        && (character_type != 'Hunter')) {
        if (specialties > 3) {
            skill_xp -= ((specialties - 3) * 3);
        }
    }
    else {
        if (getSplat1() != 'Beast') {
            if (specialties > 4) {
                skill_xp -= ((specialties - 4) * 3);
            }
        }
        else {
            if (specialties > 5) {
                skill_xp -= ((specialties - 5) * 3);
            }
        }
    }

    if (skill_xp > 0) {
        $('#skill_xp').val(skill_xp);
    }
    else {
        $('#skill_xp').val(0);
    }
}

function updateMeritXP() {
    i = 0;
    var character_type = getCharacterType();
    if (character_type === 'Purified') {
        merit_xp = purified_merit_xp_base;
    }
    else {
        merit_xp = merit_xp_base;
    }

    var dots;
    var merit_cost;
    while (document.getElementById('merit' + i)) {
        dots = $('#merit' + i).val();
        merit_xp -= getMeritCost(i, dots, character_type);
        i++;
    }

    if (document.getElementById('power-trait')) {
        // power stat
        var power_trait_value = $('#power-trait').val();
        merit_xp -= ((Number(power_trait_value) * (Number(power_trait_value) + 1)) * 8) / 2 - 8;
    }

    if (character_type == 'Werewolf') {
        var rites_multiplier;
        if (getSplat1() == 'Ithaeur') {
            rites_multiplier = 1;
        }
        else {
            rites_multiplier = 2;
        }

        // Rites
        i = 0;
        var merit_xp_before_rituals = merit_xp;
        while (document.getElementById('ritual' + i)) {
            var ritual_value = $('#ritual' + i).val();
            merit_xp -= (Number(ritual_value) * rites_multiplier);
            i++;
        }

        // refund xp for rituals dots
        if (document.getElementById('rituals').value > 0) {
            var rituals = $('#rituals').val();
            merit_xp += (Number(rituals) * (Number(rituals) + 1 )) / 2 * rites_multiplier;

            if (merit_xp > merit_xp_before_rituals) {
                merit_xp = merit_xp_before_rituals;
            }
        }

        // give bonus to merit XP
    }

    i = 0;
    if (character_type == "Thaumaturge") {
        merit_xp += 20;
        // cycle through psychic merits
        while (document.getElementById('thaumaturgemerit' + i)) {
            merit_xp -= getMeritCost(i, $('#thaumaturgemerit' + i).val(), character_type);
            i++;
        }
    }

    i = 0;
    if (character_type == "Psychic") {
        // cycle through psychic merits
        while (document.getElementById('psychicmerit' + i)) {
            dots = $('#psychicmerit' + i).val();
            merit_xp -= getMeritCost(i, dots, character_type);
            i++;
        }
    }

    if (character_type == "Hunter") {
        i = 0;
        while (document.getElementById('endowment' + i)) {
            dots = $('#endowment' + i).val();
            merit_xp -= getMeritCost(i, dots, character_type);
            i++;
        }

        i = 0;
        while (document.getElementById('tactic' + i + '_cost')) {
            merit_xp -= Number(document.getElementById('tactic' + i + '_cost').value);;
            i++;
        }
    }

    if (character_type == "Geist") {
        var i = 0;
        while (document.getElementById('ceremony' + i)) {
            var value = $('#ceremony' + i).val();
            merit_xp -= getMeritCost(i, value, character_type);
            i++;
        }
    }

    if (merit_xp > 0) {
        $('#merit_xp').val(merit_xp);
    }
    else {
        $('#merit_xp').val(0);
    }
}

function updateSupernaturalXP() {
    var character_type = getCharacterType();

    switch (character_type) {
        case 'Vampire':
            updateVampireXP(1);
            break;
        case 'Werewolf':
            updateWerewolfXP();
            break;
        case 'Mage':
            updateMageXP();
            break;
        case 'Ghoul':
            updateVampireXP(2);
            break;
        case 'Mortal':
        case 'Sleepwalker':
        case 'Wolfblooded':
        case 'Hunter':
        case 'Psychic':
        case 'Thaumaturge':
            break;
        case 'Promethean':
            updatePrometheanXP();
            break;
        case 'Changeling':
            updateChangelingXP();
            break;
        case 'Geist':
            updateGeistXP();
            break;
        case 'Purified':
            updatePurifiedXP();
            break;
        case 'Possessed':
            updateVampireXP(3);
            break;
        case 'Changing Breed':
            updateChangingBreedXp();
            break;
        default:
            alert('Unknown Character Type: ' + character_type);
    }
}

function updateVampireXP(character_type) {
    var i = 0;
    var multiplier = 1;

    if (character_type == 1) {
        supernatural_xp = vampire_xp_base;
    }
    else if (character_type == 2) {
        multiplier = 2;
        supernatural_xp = ghoul_xp_base;
    }
    else if (character_type == 3) {
        multiplier = 2;
        supernatural_xp = possessed_xp_base;
    }

    while (document.getElementById('icdisc' + i)) {
        var icdisc_value = document.getElementById('icdisc' + i).value;
        var icdisc_cost = ((Number(icdisc_value) * (Number(icdisc_value) + 1)) * 5) / 2;
        supernatural_xp -= (icdisc_cost * multiplier);
        i++;
    }

    i = 0;
    while (document.getElementById('oocdisc' + i)) {
        var oocdisc_value = document.getElementById('oocdisc' + i).value;
        var oocdisc_cost = ((Number(oocdisc_value) * (Number(oocdisc_value) + 1)) * 7) / 2;
        supernatural_xp -= (oocdisc_cost * multiplier);
        i++;
    }

    i = 0;
    while (document.getElementById('devotion' + i + '_name')) {
        var devotion_cost = document.getElementById('devotion' + i).value;
        supernatural_xp -= Number(devotion_cost);
        i++;
    }

    if (supernatural_xp > 0) {
        document.getElementById('supernatural_xp').value = supernatural_xp;
    }
    else {
        document.getElementById('supernatural_xp').value = 0;
    }
}

function updateWerewolfXP() {
    supernatural_xp = werewolf_xp_base;
    var penalty;
    var powers_in_list;
    var renownCounts = [0, 0, 0, 0, 0, 0];

    var multiplier;
    var renown_cost;
    for (var i = 0; i < renown_list.length; i++) {
        var renown_value = $('#' + renown_list[i]).val();
        for (var j = renown_value; j > 0; j--) {
            renownCounts[j] = renownCounts[j] + 1;
        }
        multiplier = 8;
        if ((isAffinityRenown(getSplat1(), renown_list[i])) || (isAffinityRenown(getSplat2(), renown_list[i]))) {
            multiplier = 6;
        }
        renown_cost = ((Number(renown_value) * (Number(renown_value) + 1)) * multiplier) / 2 - getFreeRenownDotsCost(renown_list[i]);
        if (renown_cost < 0) {
            renown_cost = 0;
        }
        supernatural_xp -= renown_cost;
    }

    i = 0;
    var value = 0;
    while ($('#affgift' + i).length > 0) {
        value = $('#affgift' + i).val();
        powers_in_list = getNumOfPowersInList("affgift", $('#affgift' + i + '_note').val());
        penalty = (value - powers_in_list) * 3;
        if (penalty < 0) {
            penalty = 0;
        }

        if((renownCounts[value] > 0) && (penalty == 0)) {
            renownCounts[value] = renownCounts[value] - 1;
        }
        else {
            console.debug(renownCounts[value], penalty);
            supernatural_xp -= Number(value) * 5 + penalty;
        }
        i++;
    }

    i = 0;
    while ($('#nonaffgift' + i).length > 0) {
        value = $('#nonaffgift' + i).val();
        powers_in_list = getNumOfPowersInList("nonaffgift", $('#nonaffgift' + i + '_note').val());
        penalty = (value - powers_in_list) * 3;
        if (penalty < 0) {
            penalty = 0;
        }

        if((renownCounts[value] > 0) && (penalty == 0)) {
            renownCounts[value] = renownCounts[value] - 1;
        }
        else {
            supernatural_xp -= Number(value) * 5 + penalty;
        }
        i++;
    }

    // Rituals
    var rituals_multiplier;
    //var rites_multiplier;
    if (getSplat1() == 'Ithaeur') {
        rituals_multiplier = 4;
    }
    else {
        rituals_multiplier = 5;
    }

    var rituals_value = document.getElementById('rituals').value;

    supernatural_xp -= (Number(rituals_value) * (Number(rituals_value) + 1) * rituals_multiplier) / 2;

    if (supernatural_xp > 0) {
        $('#supernatural_xp').val(supernatural_xp);
    }
    else {
        $('#supernatural_xp').val(0);
    }
}

function updateMageXP() {
    var i;
    supernatural_xp = mage_xp_base;

    i = 0;
    while (document.getElementById('rulingarcana' + i)) {
        var rulingarcana_value = document.getElementById('rulingarcana' + i).value;
        supernatural_xp -= (Number(rulingarcana_value) * (Number(rulingarcana_value) + 1) * 6) / 2;
        i++;
    }

    i = 0;
    while (document.getElementById('commonarcana' + i)) {
        var commonarcana_value = document.getElementById('commonarcana' + i).value;
        supernatural_xp -= (Number(commonarcana_value) * (Number(commonarcana_value) + 1) * 7) / 2;
        i++;
    }

    i = 0;
    while (document.getElementById('inferiorarcana' + i)) {
        var inferiorarcana_value = document.getElementById('inferiorarcana' + i).value;
        supernatural_xp -= (Number(inferiorarcana_value) * (Number(inferiorarcana_value) + 1) * 8) / 2;
        i++;
    }

    i = 0;
    while (document.getElementById('rote' + i)) {
        var rote_value = document.getElementById('rote' + i).value;
        supernatural_xp -= Number(rote_value) * 2;
        i++;
    }

    if (supernatural_xp > 0) {
        document.getElementById('supernatural_xp').value = supernatural_xp;
    }
    else {
        document.getElementById('supernatural_xp').value = 0;
    }

}

function updatePrometheanXP() {
    var i;
    supernatural_xp = promethean_xp_base;

    i = 0;
    while (document.getElementById('bestowment' + i + '_name')) {
        var bestowment_cost = document.getElementById('bestowment' + i + '_cost').value;
        supernatural_xp -= Number(bestowment_cost);
        i++;
    }

    i = 0;
    while (document.getElementById('afftrans' + i)) {
        var afftrans_value = document.getElementById('afftrans' + i).value;
        var powers_in_list = getNumOfPowersInList("afftrans", document.getElementById('afftrans' + i + '_note').value);
        var penalty = (afftrans_value - powers_in_list) * 3;
        if (penalty < 0) {
            penalty = 0;
        }
        var afftrans_cost = (Number(afftrans_value) * 5) + penalty;
        supernatural_xp -= afftrans_cost;
        i++;
    }

    i = 0;
    while (document.getElementById('nonafftrans' + i)) {
        var nonafftrans_value = document.getElementById('nonafftrans' + i).value;
        var powers_in_list = getNumOfPowersInList("nonafftrans", document.getElementById('nonafftrans' + i + '_note').value);
        var penalty = (nonafftrans_value - powers_in_list) * 3;
        if (penalty < 0) {
            penalty = 0;
        }
        var nonafftrans_cost = (Number(nonafftrans_value) * 7) + penalty;
        supernatural_xp -= nonafftrans_cost;
        i++;
    }

    if (supernatural_xp > 0) {
        document.getElementById('supernatural_xp').value = supernatural_xp;
    }
    else {
        document.getElementById('supernatural_xp').value = 0;
    }
}

function updateChangelingXP() {
    supernatural_xp = changeling_xp_base;

    var i = 0;
    var contracts = loadContracts('affcont', {});

    while (document.getElementById('affcont' + i)) {
        var affcont_name = document.getElementById('affcont' + i + '_name').value.toLowerCase();
        var affcont_value = document.getElementById('affcont' + i).value;
        var affcont_cost = ((Number(affcont_value) * (Number(affcont_value) + 1)) * 4) / 2;
        if (( affcont_name.indexOf('element') > -1 ) || ( affcont_name.indexOf('fang and talon') > -1 )) {
            if (contractDiscounted(affcont_name.toLowerCase(), affcont_value, contracts)) {
                affcont_cost = affcont_cost / 2;
            }
        }
        supernatural_xp -= affcont_cost;
        i++;
    }

    i = 0;
    contracts = loadContracts('nonaffcont', {});
    while (document.getElementById('nonaffcont' + i)) {
        var nonaffcont_name = document.getElementById('nonaffcont' + i + '_name').value.toLowerCase();
        var nonaffcont_value = document.getElementById('nonaffcont' + i).value;
        var multiplier = 6;
        if ((getSubSplat() == "Manikin") && (nonaffcont_name.toLowerCase().indexOf('artifice') > -1)) {
            multiplier = 5;
        }
        var nonaffcont_cost = ((Number(nonaffcont_value) * (Number(nonaffcont_value) + 1)) * multiplier) / 2;
        if (( nonaffcont_name.indexOf('element') > -1 ) || ( nonaffcont_name.indexOf('fang and talon') > -1 )) {
            if (contractDiscounted(nonaffcont_name.toLowerCase(), nonaffcont_value, contracts)) {
                nonaffcont_cost = nonaffcont_cost / 2;
            }
        }
        supernatural_xp -= nonaffcont_cost;
        i++;
    }

    i = 0;
    while (document.getElementById('gobcont' + i)) {
        var gobcont_value = document.getElementById('gobcont' + i).value;
        var gobcont_cost = (Number(gobcont_value) * 3);
        supernatural_xp -= Number(gobcont_cost);
        i++;
    }

    if (supernatural_xp > 0) {
        document.getElementById('supernatural_xp').value = supernatural_xp;
    }
    else {
        document.getElementById('supernatural_xp').value = 0;
    }
}

function updateGeistXP() {
    var i = 0;
    supernatural_xp = geist_xp_base;

    // keys
    while (document.getElementById('key' + i + '_name')) {
        if (document.getElementById('key' + i + '_name').value != '') {
            supernatural_xp -= 10;
        }
        i++;
    }

    // manifestations
    i = 0;
    while (document.getElementById('manifestation' + i)) {
        var value = document.getElementById('manifestation' + i).value;
        var cost = ((Number(value) * (Number(value) + 1)) * 6) / 2;
        supernatural_xp -= Number(cost);
        i++;
    }

    if (supernatural_xp > 0) {
        document.getElementById('supernatural_xp').value = supernatural_xp;
    }
    else {
        document.getElementById('supernatural_xp').value = 0;
    }
}

function updatePurifiedXP() {
    var i = 0;
    supernatural_xp = purified_xp_base;

    // numina
    while (document.getElementById('numina' + i + '_name')) {
        if (document.getElementById('numina' + i + '_name').value != '') {
            supernatural_xp -= 10;
        }
        i++;
    }

    // Siddhi
    i = 0;
    while (document.getElementById('siddhi' + i)) {
        var value = document.getElementById('siddhi' + i).value;
        var cost = ((Number(value) * (Number(value) + 1)) * 7) / 2;
        supernatural_xp -= Number(cost);
        i++;
    }

    if (supernatural_xp > 0) {
        document.getElementById('supernatural_xp').value = supernatural_xp;
    }
    else {
        document.getElementById('supernatural_xp').value = 0;
    }
}

function updateChangingBreedXp() {
    supernatural_xp = changing_breed_xp_base;

    var penalty;
    var powers_in_list;
    var renownCounts = [0, 0, 0, 0, 0, 0];

    var multiplier;
    var renown_cost;
    $.each(renown_list, function(item) {
        var renown_value = $('#' + renown_list[item]).val();
        for (var j = renown_value; j > 0; j--) {
            renownCounts[j] = renownCounts[j] + 1;
        }
        multiplier = 6;
        renown_cost = ((Number(renown_value) * (Number(renown_value) + 1)) * multiplier) / 2;
        if (renown_cost < 0) {
            renown_cost = 0;
        }
        supernatural_xp -= renown_cost;
    });

    $("#affgift_list").find('.trait-value').each(function() {
        var value = $('#affgift' + i).val();
        powers_in_list = getNumOfPowersInList("affgift", $('#affgift' + i + '_note').val());
        penalty = (value - powers_in_list) * 3;
        if (penalty < 0) {
            penalty = 0;
        }

        if((renownCounts[value] > 0) && (penalty == 0)) {
            renownCounts[value] = renownCounts[value] - 1;
        }
        else {
            supernatural_xp -= Number(value) * 5 + penalty;
        }
    });

    $("#aspect_list").find(".trait-value").each(function() {
        var value = $(this).val();
        var cost = ((Number(value) * (Number(value) + 1)) * 5) / 2;
        supernatural_xp -= Number(cost);
    });

    if (supernatural_xp > 0) {
        $('#supernatural_xp').val(supernatural_xp);
    }
    else {
        $('#supernatural_xp').val(0);
    }
}

function loadContracts(list, hashtable) {
    var i = 0;
    while (document.getElementById(list + i)) {
        var name = document.getElementById(list + i + "_name").value.toLowerCase();
        var value = document.getElementById(list + i).value;
        if (hashtable[name]) {
            if ((Number(value) * 10) > Number(hashtable[name])) {
                hashtable[name] = Number(value) * 10;
            }
        }
        else {
            if (name != '') {
                hashtable[name] = Number(value) * 10;
            }
        }
        i++;
    }

    return hashtable;
}

function contractDiscounted(list, level, hashtable) {
    var give_discount = true;
    var max_level = Math.floor(Number(hashtable[list]) / 10);
    var max_charged = Number(hashtable[list]) % 10;

    if ((Number(level) == max_level) && (max_charged == 0)) {
        give_discount = false;
        hashtable[list] = Number(hashtable[list]) + 1;
    }

    return give_discount;
}

function updateGeneralXP() {
    general_xp = general_xp_base;

    if (attribute_xp < 0) {
        general_xp += attribute_xp;
    }
    if (skill_xp < 0) {
        general_xp += skill_xp;
    }
    if (merit_xp < 0) {
        general_xp += merit_xp;
    }
    if (supernatural_xp < 0) {
        general_xp += supernatural_xp;
    }

    var morality_base = 7;
    if (getCharacterType() == 'Ghoul') {
        morality_base = 6;
    }

    if ($('#morality').val() < morality_base) {
        general_xp += ((morality_base - Number($('#morality').val())) * 5);
    }
    else {
        var morality_value = $('#morality').val();
        var morality_base_cost = ((Number(morality_base) * (Number(morality_base) + 1)) * 3) / 2;
        general_xp -= (((Number(morality_value) * (Number(morality_value) + 1)) * 3) / 2 - morality_base_cost);
    }


    $('#general_xp').val(general_xp);
}

function getSkillCost(index, dots, character_type) {
    return ((Number(dots) * (Number(dots) + 1)) * 3) / 2;
}

function getMeritCost(index, dots, character_type) {
    var cost;
    switch (character_type) {
        case 'Vampire':
            cost = getVampireMeritCost(index, dots);
            break;
        case 'Mage':
            cost = getMageMeritCost(index, dots);
            break;
        case 'Werewolf':
            cost = getWerewolfMeritCost(index, dots);
            break;
        case 'Changeling':
            cost = getChangelingMeritcost(index, dots);
            break;
        default:
            cost = ((Number(dots) * (Number(dots) + 1)) * 2) / 2;
    }
    return cost;
}

function getVampireMeritCost(index, dots) {
    var merit_cost = ((Number(dots) * (Number(dots) + 1)) * 2) / 2;
    /*if (getSplat2().indexOf("Carthian") > -1) {
     switch (getMeritName(index).toLowerCase()) {
     case 'allies':
     case 'contacts':
     case 'haven':
     case 'herd':
     if (hasStatus(getSplat2())) {
     merit_cost = merit_cost / 2;
     }
     break;
     }
     }
     if (getSplat2() == "Invictus") {
     switch (getMeritName(index).toLowerCase()) {
     case 'herd':
     case 'mentor':
     case 'resources':
     case 'retainer':
     if (hasStatus(getSplat2())) {
     merit_cost = merit_cost / 2;
     }
     break;
     }
     }*/

    return merit_cost;
}

function getWerewolfMeritCost(index, dots) {
    var merit_cost;
    if (getMeritName(index) == 'Totem') {
        merit_cost = Number(dots) * 3;
    }
    else {
        merit_cost = ((Number(dots) * (Number(dots) + 1)) * 2) / 2;
    }

    return merit_cost;
}

function getMageMeritCost(index, dots) {
    var merit_cost;
    if ((getMeritName(index).toLowerCase() == 'high speech') && (hasStatus(getSplat2()))) {
        merit_cost = 0;
    }
    else {
        merit_cost = ((Number(dots) * (Number(dots) + 1)) * 2) / 2;
    }

    return merit_cost;
}

function getChangelingMeritcost(index, dots) {
    var merit_cost = ((Number(dots) * (Number(dots) + 1)) * 2) / 2;

    if (isCourtMantle(index)) {
        merit_cost = (((Number(dots) * (Number(dots) + 1)) * 2) / 2) - 2;
        if (merit_cost < 0) {
            merit_cost = 0;
        }
    }

    if ((getSplat2() == 'Spring') && hasMerit("mantle", "spring", 3)) {
        var merit_name = document.getElementById('merit' + index + '_name').value.toLowerCase();
        switch (merit_name) {
            case 'allies':
            case 'contacts':
                merit_cost = merit_cost / 2;
                break;
        }
    }

    return merit_cost;
}

function isCourtMantle(index) {
    if (getMeritName(index).toLowerCase() == 'mantle') {
        var merit_note = $('#merit' + index + '_note').val();
        if ((merit_note != '') && (getSplat2().toLowerCase().indexOf(merit_note.toLowerCase()) > -1)) {
            return true;
        }
    }
    return false;
}

function hasMerit(name, note, level) {
    var i = 0;
    var found_merit = false;

    while ($('#merit' + i).length > 0 && !found_merit) {
        if ($('#merit' + i + '_name').val().toLowerCase() == name.toLowerCase()) {
            if (note.toLowerCase().indexOf($('#merit' + i + '_note').val().toLowerCase()) > -1) {
                if (document.getElementById('merit' + i).value >= level) {
                    found_merit = true;
                    break;
                }
            }
        }
        i++;
    }

    return found_merit;
}

function hasStatus(group_name) {
    var i = 0;
    var found_status = false;
    //noinspection JSJQueryEfficiency
    while (($('#merit' + i).length > 0) && !found_status) {
        if ($('#merit' + i + '_name').val().toLowerCase() == 'status') {
            if (group_name.toLowerCase().indexOf($('#merit' + i + '_note').val().toLowerCase()) > -1) {
                if ($('#merit' + i).value > 0) {
                    found_status = true;
                    break;
                }
            }
            else {
                //alert('Is "' + document.getElementById('merit' + i + '_note').value + '" the right status for ' + group_name + '?');
            }
        }
        i++;
    }

    return found_status;
}

function getFreeRenownDotsCost(renown) {
    var dots = getNumberOfFreeRenownDots(renown);
    return ((Number(dots) * (Number(dots) + 1)) * 6) / 2;
}

function getNumOfPowersInList(power_list, list_name) {
    var index = 0;
    var number_of_powers = 0;

    while ($("#"+power_list + index).length > 0) {
        if ($("#"+power_list + index + '_note').val() == list_name) {
            number_of_powers++;
        }
        index++;
    }

    return number_of_powers;
}

function displayFreeWerewolfRenown() {
    if ($("#character-id").val() == 0) {
        var i;
        var dots;
        for (i = 0; i < renown_list.length; i++) {
            dots = getNumberOfFreeRenownDots(renown_list[i]);
            changeDots(supernatural, renown_list[i], dots, 7, false);
        }
    }
}

function getNumberOfFreeRenownDots(renown) {
    var dots = 0;
    if (isAffinityRenown(getSplat1(), renown)) {
        dots++;
    }
    if (isAffinityRenown(getSplat2(), renown)) {
        dots++;
    }
    return dots;
}

function isAffinityRenown(splat, renown) {
    var is_affinity = false;
    switch (splat) {
        case 'Rahu':
        case 'Hunters in Darkness':
            if (renown == 'purity') {
                is_affinity = true;
            }
            break;

        case 'Cahalith':
        case 'Blood Talons':
            if (renown == 'glory') {
                is_affinity = true;
            }
            break;

        case 'Elodoth':
        case 'Storm Lords':
            if (renown == 'honor') {
                is_affinity = true;
            }
            break;

        case 'Ithaeur':
        case 'Bone Shadows':
            if (renown == 'wisdom') {
                is_affinity = true;
            }
            break;

        case 'Irraka':
        case 'Iron Masters':
            if (renown == 'cunning') {
                is_affinity = true;
            }
            break;

        default:
    }

    return is_affinity;
}

function isProfessionSkill(profession, skill) {
    var isBonusSkill = false;
    var skill1 = "";
    var skill2 = "";

    switch (profession) {
        case "Academic":
            skill1 = "Academics";
            skill2 = "Science";
            break;
        case "Artist":
            skill1 = "Crafts";
            skill2 = "Expression";
            break;
        case "Athlete":
            skill1 = "Athletics";
            skill2 = "Medicine";
            break;
        case "Cop":
            skill1 = "Streetwise";
            skill2 = "Firearms";
            break;
        case "Criminal":
            skill1 = "Larceny";
            skill2 = "Streetwise";
            break;
        case "Detective":
            skill1 = "Empathy";
            skill2 = "Investigation";
            break;
        case "Doctor":
            skill1 = "Empathy";
            skill2 = "Medicine";
            break;
        case "Engineer":
            skill1 = "Crafts";
            skill2 = "Science";
            break;
        case "Hacker":
            skill1 = "Computer";
            skill2 = "Science";
            break;
        case "Hitman":
            skill1 = "Firearms";
            skill2 = "Stealth";
            break;
        case "Journalist":
            skill1 = "Expression";
            skill2 = "Investigation";
            break;
        case "Laborer":
            skill1 = "Athletics";
            skill2 = "Crafts";
            break;
        case "Occultist":
            skill1 = "Investigation";
            skill2 = "Occult";
            break;
        case "Outdoorsman":
            skill1 = "Animal Ken";
            skill2 = "Survival";
            break;
        case "Professional":
            skill1 = "Academics";
            skill2 = "Persuasion";
            break;
        case "Religious Leader":
            skill1 = "Academics";
            skill2 = "Occult";
            break;
        case "Scientist":
            skill1 = "Investigation";
            skill2 = "Science";
            break;
        case "Socialiate":
            skill1 = "Politics";
            skill2 = "Science";
            break;
        case "Soldier":
            skill1 = "Firearms";
            skill2 = "Survival";
            break;
        case "Technician":
            skill1 = "Crafts";
            skill2 = "Investigation";
            break;
        case "Vagrant":
            skill1 = "Streetwise";
            skill2 = "Survival";
            break;
    }

    isBonusSkill = ((skill.replace("_", " ") == skill1.toLowerCase()) || (skill.replace("_", " ") == skill2.toLowerCase()));

    return isBonusSkill;
}

function updateBonusAttribute() {
    var character_type = getCharacterType();
    var splat1 = getSplat1();

    if (character_type == 'Vampire') {
        var option1;
        var option2;
        var bonus_attribute_select = $('#bonus_attribute_select');
        bonus_attribute_select.empty();

        switch (splat1) {
            case 'Daeva':
                option1 = "Dexterity";
                option2 = "Manipulation";
                break;
            case 'Gangrel':
                option1 = "Composure";
                option2 = "Stamina";
                break;
            case 'Mekhet':
                option1 = "Intelligence";
                option2 = "Wits";
                break;
            case 'Nosferatu':
                option1 = "Composure";
                option2 = "Strength";
                break;
            case 'Ventrue':
                option1 = "Presence";
                option2 = "Resolve";
                break;
        }

        var newOption = $("<option></option>");
        newOption.val(option1);
        newOption.text(option1);
        bonus_attribute_select.append(newOption);
        newOption = $("<option></option>");
        newOption.val(option2);
        newOption.text(option2);
        bonus_attribute_select.append(newOption);

        bonus_attribute_select.val($('#bonus_attribute').val());
    }

    if (character_type == 'Mage') {
        document.getElementById('bonus_attribute').value = getBonusMageAttribute();
    }

    displayBonusDot();
}

function displayBonusDot() {
    if ((getCharacterType() == 'Vampire') || (getCharacterType() == 'Mage')) {
        // remove old dot
        var bonus_attribute = $("#bonus_attribute");
        var old_bonus = bonus_attribute.val().toLowerCase();
        var attribute_level;
        if (old_bonus != '') {
            attribute_level = Number($("#"+attributes.getId(old_bonus)).val()) + 1;
            $("#"+attributes.getId(old_bonus) + '-dot' + attribute_level).attr('src', "img/empty.gif");
        }

        // set bonus_attribute value
        var new_bonus;
        if (getCharacterType() == 'Vampire') {
            new_bonus = $('#bonus_attribute_select').val();
        }
        else {
            new_bonus = getMageBonusAttribute();
            addMageBonusAttibuteText(new_bonus);
        }

        $('#bonus_attribute').val(new_bonus);

        new_bonus = new_bonus.toLowerCase();
        // add new dot
        attribute_level = Number($("#"+attributes.getId(new_bonus)).val()) + 1;
        if (attribute_level < 8) {
            $("#"+attributes.getId(new_bonus) + '-dot' + attribute_level).attr('src', 'img/bonus_filled.gif');
        }

        updateTraits();
    }
}

function getCharacterType() {
    var oldType = $("#character_type").val(),
        newType = $("#character-type").val();
    if(oldType == null) {
        return newType;
    }
    return oldType;
}

function getSplat1() {
    return $("#splat1").val();
}

function getSplat2() {
    return $("#splat2").val();
}

function getSubSplat() {
    return $("#subsplat").val();
}

function getMeritName(index) {
    return $('#merit' + index + '_name').val();
}

function addMerit() {
    var merit_list = document.getElementById('merit_list');
    var row_id = merit_list.rows.length;
    var index = row_id - 2;
    var newRow = merit_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + merit + ")\" ";
    }
    newNameCell.innerHTML = "<input type=\"text\" name=\"merit" + index + "_name\" id=\"merit" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newNoteCell = newRow.insertCell(1);
    newNoteCell.innerHTML = "<input type=\"text\" name=\"merit" + index + "_note\" id=\"merit" + index + "_note\" size=\"20\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newDotsCell = newRow.insertCell(2);
    newDotsCell.innerHTML = makeDotsXP("merit" + index, merit, getCharacterType(), 7, 0, true, false, edit_xp);
}

function addFlaw() {
    var flaw_list = document.getElementById('flaw_list');
    var row_id = flaw_list.rows.length;
    var index = row_id - 2;
    var newRow = flaw_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);

    newNameCell.innerHTML = "<input type=\"text\" name=\"flaw" + index + "_name\" id=\"flaw" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" >";
}

function addMiscTrait() {
    var list = document.getElementById('misc_list');
    var row_id = list.rows.length;
    var index = row_id - 2;
    var newRow = list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + merit + ")\" ";
    }
    newNameCell.innerHTML = "<input type=\"text\" name=\"misc" + index + "_name\" id=\"misc" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" />";
    var newNoteCell = newRow.insertCell(1);
    newNoteCell.innerHTML = "<input type=\"text\" name=\"misc" + index + "_note\" id=\"misc" + index + "_note\" size=\"20\" maxlength=\"40\" class=\"normal_input\" />";
    var newCostCell = newRow.insertCell(2);
    newCostCell.innerHTML = "<input type=\"text\" name=\"misc" + index + "\" id=\"misc" + index + "\" size=\"3\" maxlength=\"2\" class=\"normal_input\" value=\"0\" />";
}

function addMisc() {
    var misc_list = document.getElementById('misc_list');
    var row_id = misc_list.rows.length;
    var index = row_id - 2;
    var newRow = misc_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + merit + ")\" ";
    }

    newNameCell.innerHTML = "<input type=\"text\" name=\"misc" + index + "_name\" id=\"misc" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" >";

    var newCostCell = newRow.insertCell(1);
    newCostCell.innerHTML = "<input type=\"text\" name=\"misc" + index + "_level\" id=\"misc" + index + "_level\" size=\"3\" maxlength=\"3\" class=\"normal_input\" " + js + ">";
}

function addSpecialty() {
    var specialties_list = document.getElementById('specialties_list');
    var row_id = specialties_list.rows.length;
    var index = row_id - 2;
    var newRow = specialties_list.insertRow(row_id);
    var newSkillCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + skill + ")\" ";
    }

    if (getCharacterType() == 'Mage') {
        newSkillCell.innerHTML = buildSelect("", skill_list_proper_mage, skill_list_proper_mage, "skill_spec" + index + "_selected", "class=\"normal_input\" " + js);
    }
    else {
        newSkillCell.innerHTML = buildSelect("", skill_list_proper, skill_list_proper, "skill_spec" + index + "_selected", "class=\"normal_input\" " + js);
    }

    var newSpecialtyCell = newRow.insertCell(1);
    newSpecialtyCell.innerHTML = "<input type=\"text\" name=\"skill_spec" + index + "\" id=\"skill_spec" + index + "\" class=\"normal_input\" " + js + ">";
    newSpecialtyCell.innerHTML += "<input type=\"hidden\" name=\"skill_spec" + index + "_id\" id=\"skill_spec" + index + "_id\" value=\"0\">";

}

function addDisc(type) {
    var disc_list = document.getElementById(type + '_list');
    var row_id = disc_list.rows.length;
    var index = row_id - 2;
    var newRow = disc_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_name\" id=\"" + type + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newLevelCell = newRow.insertCell(1);
    newLevelCell.innerHTML = makeDotsXP(type + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
    newLevelCell.innerHTML += "<input type=\"hidden\" name=\"" + type + index + "_id\" id=\"" + type + index + "_id\" value=\"0\">";
}

function addDevotion() {
    var devotion_list = document.getElementById('devotion_list');
    var row_id = devotion_list.rows.length;
    var index = row_id - 2;
    var newRow = devotion_list.insertRow(row_id);

    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"devotion" + index + "_name\" id=\"devotion" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newCostCell = newRow.insertCell(1);
    var supernatural_xp_js = "";
    if (edit_xp) {
        supernatural_xp_js = " onChange=\"updateXP(" + supernatural + ")\" ";
    }
    newCostCell.innerHTML = "<input type=\"text\" name=\"devotion" + index + "\" id=\"devotion" + index + "\" size=\"3\" maxlength=\"2\" class=\"normal_input\" " + supernatural_xp_js + ">";
    newCostCell.innerHTML += "<input type=\"hidden\" name=\"devotion" + index + "\" id=\"devotion" + index + "\" value=\"0\" " + supernatural_xp_js + ">";
}

function addGift(type) {
    var gift_list = document.getElementById(type + '_list');
    var row_id = gift_list.rows.length;
    var index = row_id - 2;
    var newRow = gift_list.insertRow(row_id);

    var newListCell = newRow.insertCell(0);
    newListCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_note\" id=\"" + type + index + "_note\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newNameCell = newRow.insertCell(1);
    newNameCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_name\" id=\"" + type + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newLevelCell = newRow.insertCell(2);
    newLevelCell.innerHTML = makeDotsXP(type + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
    newLevelCell.innerHTML += "<input type=\"hidden\" name=\"" + type + index + "_id\" id=\"" + type + index + "_id\" value=\"0\">";
}

function addRitual() {
    var ritual_list = document.getElementById('ritual_list');
    var row_id = ritual_list.rows.length;
    var index = row_id - 3;
    var newRow = ritual_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"ritual" + index + "_name\" id=\"ritual" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";
    var newDotsCell = newRow.insertCell(1);
    newDotsCell.innerHTML = makeDotsXP("ritual" + index, merit, getCharacterType(), 7, 0, true, false, edit_xp);
    newDotsCell.innerHTML += "<input type=\"hidden\" name=\"ritual" + index + "_id\" id=\"ritual" + index + "_id\" value=\"0\">";
}

function addAspect() {
    var list = document.getElementById('aspect_list');
    var row_id = list.rows.length;
    var index = row_id - 2;
    var newRow = list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"aspect" + index + "_name\" id=\"aspect" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";
    var newDotsCell = newRow.insertCell(1);
    newDotsCell.innerHTML = makeDotsXP("aspect" + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
    newDotsCell.innerHTML += "<input type=\"hidden\" name=\"ritual" + index + "_id\" id=\"ritual" + index + "_id\" value=\"0\">";
}

function addArcana(type) {
    var arcana_list = document.getElementById(type + '_list');
    var row_id = arcana_list.rows.length;
    var index = row_id - 2;
    var newRow = arcana_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_name\" id=\"" + type + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newLevelCell = newRow.insertCell(1);
    newLevelCell.align = "center";
    newLevelCell.innerHTML = makeDotsXP(type + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
    newLevelCell.innerHTML += "<input type=\"hidden\" name=\"" + type + index + "_id\" id=\"" + type + index + "_id\" value=\"0\">";
}

function addRote() {
    var rote_list = document.getElementById('rote_list');
    var row_id = rote_list.rows.length;
    var index = row_id - 2;
    var newRow = rote_list.insertRow(row_id);

    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"rote" + index + "_name\" id=\"rote" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newNoteCell = newRow.insertCell(1);
    newNoteCell.innerHTML = "<input type=\"text\" name=\"rote" + index + "_note\" id=\"rote" + index + "_note\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newCostCell = newRow.insertCell(2);
    newCostCell.align = "center";
    var supernatural_xp_js = "";
    if (edit_xp) {
        supernatural_xp_js = " onChange=\"updateXP(" + supernatural + ")\" ";
    }
    newCostCell.innerHTML = "<input type=\"text\" name=\"rote" + index + "\" id=\"rote" + index + "\" size=\"3\" maxlength=\"2\" class=\"normal_input\" " + supernatural_xp_js + ">";
    newCostCell.innerHTML += "<input type=\"hidden\" name=\"rote" + index + "_id\" id=\"rote" + index + "_id\" value=\"0\">";
}

function addPsychicMerit() {
    var merit_list = document.getElementById('psychic_merit_list');
    var row_id = merit_list.rows.length;
    var index = row_id - 2;
    var newRow = merit_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + merit + ")\" ";
    }
    newNameCell.innerHTML = "<input type=\"text\" name=\"psychicmerit" + index + "_name\" id=\"psychicmerit" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newNoteCell = newRow.insertCell(1);
    newNoteCell.innerHTML = "<input type=\"text\" name=\"psychicmerit" + index + "_note\" id=\"psychicmerit" + index + "_note\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newDotsCell = newRow.insertCell(2);
    newDotsCell.align = "center";
    newDotsCell.innerHTML = makeDotsXP("psychicmerit" + index, merit, getCharacterType(), 7, 0, true, false, edit_xp);
}

function addThaumaturgeMerit() {
    var merit_list = document.getElementById('thaumaturge_merit_list');
    var row_id = merit_list.rows.length;
    var index = row_id - 2;
    var newRow = merit_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + merit + ")\" ";
    }
    newNameCell.innerHTML = "<input type=\"text\" name=\"thaumaturgemerit" + index + "_name\" id=\"thaumaturgemerit" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newNoteCell = newRow.insertCell(1);
    newNoteCell.innerHTML = "<input type=\"text\" name=\"thaumaturgemerit" + index + "_note\" id=\"thaumaturgemerit" + index + "_note\" size=\"20\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newDotsCell = newRow.insertCell(2);
    newDotsCell.align = "center";
    newDotsCell.innerHTML = makeDotsXP("thaumaturgemerit" + index, merit, getCharacterType(), 7, 0, true, false, edit_xp);
}

function addBestowment() {
    var bestowment_list = document.getElementById('bestowment_list');
    var row_id = bestowment_list.rows.length;
    var index = row_id - 2;
    var newRow = bestowment_list.insertRow(row_id);

    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"bestowment" + index + "_name\" id=\"bestowment" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newCostCell = newRow.insertCell(1);
    var supernatural_xp_js = "";
    if (edit_xp) {
        supernatural_xp_js = " onChange=\"updateXP(" + supernatural + ")\" ";
    }
    newCostCell.innerHTML = "<input type=\"text\" name=\"bestowment" + index + "_cost\" id=\"bestowment" + index + "_cost\" size=\"3\" maxlength=\"2\" class=\"normal_input\" " + supernatural_xp_js + ">";
    newCostCell.innerHTML += "<input type=\"hidden\" name=\"bestowment" + index + "\" id=\"bestowment" + index + "\" value=\"0\" " + supernatural_xp_js + ">";
}

function addTrans(type) {
    var trans_list = document.getElementById(type + '_list');
    var row_id = trans_list.rows.length;
    var index = row_id - 2;
    var newRow = trans_list.insertRow(row_id);

    var newListCell = newRow.insertCell(0);
    newListCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_note\" id=\"" + type + index + "_note\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newNameCell = newRow.insertCell(1);
    newNameCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_name\" id=\"" + type + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newLevelCell = newRow.insertCell(2);
    newLevelCell.innerHTML = makeDotsXP(type + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
    newLevelCell.innerHTML += "<input type=\"hidden\" name=\"" + type + index + "_id\" id=\"" + type + index + "_id\" value=\"0\">";
}

function addContract(type) {
    var cont_list = document.getElementById(type + '_list');
    var row_id = cont_list.rows.length;
    var index = row_id - 2;
    var newRow = cont_list.insertRow(row_id);

    var newNameCell = null,
        newLevelCell = null;
    if (type == "gobcont") {
        newNameCell = newRow.insertCell(0);
        newNameCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_name\" id=\"" + type + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

        newLevelCell = newRow.insertCell(1);
        newLevelCell.innerHTML = makeDotsXP(type + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
        newLevelCell.innerHTML += "<input type=\"hidden\" name=\"" + type + index + "_id\" id=\"" + type + index + "_id\" value=\"0\">";
    }
    else {
        var newListCell = newRow.insertCell(0);
        newListCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_name\" id=\"" + type + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

        newNameCell = newRow.insertCell(1);
        newNameCell.innerHTML = "<input type=\"text\" name=\"" + type + index + "_note\" id=\"" + type + index + "_note\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

        newLevelCell = newRow.insertCell(2);
        newLevelCell.innerHTML = makeDotsXP(type + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
        newLevelCell.innerHTML += "<input type=\"hidden\" name=\"" + type + index + "_id\" id=\"" + type + index + "_id\" value=\"0\">";
    }
}

function addKey() {
    var key_list = document.getElementById('key_list');
    var row_id = key_list.rows.length;
    var index = row_id - 2;
    var newRow = key_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + supernatural + ")\" ";
    }

    newNameCell.innerHTML = "<input type=\"text\" name=\"key" + index + "_name\" id=\"key" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + " >";
    newNameCell.innerHTML += "<input type=\"hidden\" name=\"key" + index + "_id\" id=\"key" + index + "_id\" value=\"0\">";
}

function addManifestation() {
    var list = document.getElementById('manifestation_list');
    var row_id = list.rows.length;
    var index = row_id - 2;
    var newRow = list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"manifestation" + index + "_name\" id=\"manifestation" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newLevelCell = newRow.insertCell(1);
    newLevelCell.innerHTML = makeDotsXP('manifestation' + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
    newLevelCell.innerHTML += "<input type=\"hidden\" name=\"manifestation" + index + "_id\" id=\"manifestation" + index + "_id\" value=\"0\">";
}

function addMomento() {
    var list = document.getElementById('momento_list');
    var row_id = list.rows.length;
    var index = row_id - 2;
    var newRow = list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + supernatural + ")\" ";
    }

    newNameCell.innerHTML = "<input type=\"text\" name=\"momento" + index + "_name\" id=\"momento" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + " >";
}

function addCeremony() {
    var list = document.getElementById('ceremony_list');
    var row_id = list.rows.length;
    var index = row_id - 2;
    var newRow = list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"ceremony" + index + "_name\" id=\"ceremony" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newLevelCell = newRow.insertCell(1);
    newLevelCell.innerHTML = makeDotsXP('ceremony' + index, merit, getCharacterType(), 7, 0, true, false, edit_xp);
    newLevelCell.innerHTML += "<input type=\"hidden\" name=\"ceremony" + index + "_id\" id=\"ceremony" + index + "_id\" value=\"0\">";
}

function addNumina() {
    var key_list = document.getElementById('numina_list');
    var row_id = key_list.rows.length;
    var index = row_id - 2;
    var newRow = key_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + supernatural + ")\" ";
    }

    newNameCell.innerHTML = "<input type=\"text\" name=\"numina" + index + "_name\" id=\"numina" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + " >";
    newNameCell.innerHTML += "<input type=\"hidden\" name=\"numina" + index + "_id\" id=\"numina" + index + "_id\" value=\"0\">";
}

function addSiddhi() {
    var list = document.getElementById('siddhi_list');
    var row_id = list.rows.length;
    var index = row_id - 2;
    var newRow = list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"siddhi" + index + "_name\" id=\"siddhi" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newLevelCell = newRow.insertCell(1);
    newLevelCell.innerHTML = makeDotsXP('siddhi' + index, supernatural, getCharacterType(), 7, 0, true, false, edit_xp);
    newLevelCell.innerHTML += "<input type=\"hidden\" name=\"siddhi" + index + "_id\" id=\"siddhi" + index + "_id\" value=\"0\">";
}


function addVampAttributeSelect() {
    document.getElementById('attribute_div').innerHTML =
        "-- Bonus Attribute: " +
            "<select name=\"bonus_attribute_select\" id=\"bonus_attribute_select\" onChange=\"displayBonusDot();\">" +
            "</select>";
    updateBonusAttribute();
}

function addMageBonusAttibuteText(attribute) {
    document.getElementById('attribute_div').innerHTML = "-- Bonus Attribute: " + attribute;
}

function getMageBonusAttribute() {
    switch (getSplat1()) {
        case 'Acanthus':
            return 'Composure';
        case 'Mastigos':
            return 'Resolve';
        case 'Moros':
            return 'Composure';
        case 'Obrimos':
            return 'Resolve';
        case 'Thyrsus':
            return 'Composure';
    }
}

function addThaumaturgeDefiningMerit() {
    if (document.getElementById("character_id").value == 0) {
        var splat1 = getSplat1();
        var merit_name;
        switch (getSplat1()) {
            case 'Ceremonial Magician':
                merit_name = "Luck Magic";
                break;
            case 'Hedge Witch':
                merit_name = "Enchantment";
                break;
            case 'Shaman':
                merit_name = "Visionary Trances";
                break;
            case 'Taoist Alchemist':
                merit_name = "Alchemy";
                break;
            case 'Vodoun':
                merit_name = "Invocation";
                break;
            case 'Apostle':
                merit_name = "Communion";
                break;
            default:
                merit_name = "";
                break;
        }

        var merit_list = document.getElementById('thaumaturge_merit_list');
        var i;
        for (i = 0; i < merit_list.rows.length; i++) {
            document.getElementById("thaumaturgemerit" + i + "_name").value = "";
            document.getElementById("thaumaturgemerit" + i + "_note").value = "";
            changeDots(merit, "thaumaturgemerit" + i, 0, 7, true);

            if (i == 0) {
                document.getElementById("thaumaturgemerit" + i + "_name").value = merit_name;
                changeDots(merit, "thaumaturgemerit" + i, 4, 7, true);
            }
        }
    }
}

function addEndowment() {
    var power_list = document.getElementById('endowments_list');
    var row_id = power_list.rows.length;
    var index = row_id - 2;
    var newRow = power_list.insertRow(row_id);
    var newNameCell = newRow.insertCell(0);
    var js = "";
    if (edit_xp) {
        js = " onChange=\"updateXP(" + merit + ")\" ";
    }
    newNameCell.innerHTML = "<input type=\"text\" name=\"endowment" + index + "_name\" id=\"endowment" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newNoteCell = newRow.insertCell(1);
    newNoteCell.innerHTML = "<input type=\"text\" name=\"endowment" + index + "_note\" id=\"endowment" + index + "_note\" size=\"15\" maxlength=\"40\" class=\"normal_input\" " + js + ">";
    var newDotsCell = newRow.insertCell(2);
    newDotsCell.align = "center";
    newDotsCell.innerHTML = makeDotsXP("endowment" + index, merit, getCharacterType(), 7, 0, true, false, edit_xp);
}

function addTactic() {
    var power_list = document.getElementById('tactics_list');
    var row_id = power_list.rows.length;
    var index = row_id - 2;
    var newRow = power_list.insertRow(row_id);

    var newNameCell = newRow.insertCell(0);
    newNameCell.innerHTML = "<input type=\"text\" name=\"tactic" + index + "_name\" id=\"tactic" + index + "_name\" size=\"15\" maxlength=\"40\" class=\"normal_input\">";

    var newCostCell = newRow.insertCell(1);
    var supernatural_xp_js = "";
    if (edit_xp) {
        supernatural_xp_js = " onChange=\"updateXP(" + merit + ")\" ";
    }
    newCostCell.align = "center";
    newCostCell.innerHTML = "<input type=\"text\" name=\"tactic" + index + "_cost\" id=\"tactic" + index + "_cost\" size=\"3\" maxlength=\"2\" class=\"normal_input\" " + supernatural_xp_js + ">";
    newCostCell.innerHTML += "<input type=\"hidden\" name=\"tactic" + index + "\" id=\"tactic" + index + "\" value=\"0\" " + supernatural_xp_js + ">";
}

function SubmitCharacter() {
    if (document.character_sheet.character_name.value.match(/\w/g)) {
        window.document.character_sheet.submit();
    }
    else {
        alert('Please Enter a Character Name');
    }
}

function setXpEdit(xp_edit) {
    edit_xp = xp_edit;
}
function setPageAction(action) {
    page_action = action;
}

function loadNew(xp_edit) {
    edit_xp = xp_edit;
    var url = "view_sheet.php?action=get&type=new&character_type=Mortal";
    $("#charSheet").load(url, drawSheet);
}

function loadCharacter(view_character_id, xp_edit) {
    edit_xp = xp_edit;
    page_action = "view_own";
    character_id = view_character_id;
    var url = "view_sheet.php?action=get&type=view_own&character_id=" + character_id;
    $("#charSheet").load(url, drawSheet);
}


function loadCharacterSTView(view_character_id, xp_edit) {
    edit_xp = xp_edit;
    page_action = "st_view";
    character_id = view_character_id;
    var url = "view_sheet.php?action=get&type=st_view&character_id=" + character_id;

    $("#charSheet").load(url, drawSheet);
}

function changeSheet(character_type) {
    var sheet = $("#charSheet").html(
        '<div style="text-align:center;font-weight:bold;padding:5px;background-color:#cbb;border:solid 1px #400;border-radius:4px;">Loading Sheet..</div>'
    );

    $.get(
        "view_sheet.php?action=get&type=" + page_action + "&character_type=" + encodeURIComponent(character_type) + "&character_id=" + $("#character_id").val(),
        function(response) {
            sheet.html(response);
            drawSheet();
    });
}

function drawSheet() {
    var character_type = getCharacterType();
    if (edit_xp) {
        if (character_type == 'Vampire') {
            addVampAttributeSelect();
        }

        if (character_type == 'Mage') {
            displayBonusDot();
        }

        if (character_type == 'Werewolf') {
            displayFreeWerewolfRenown();
        }

        if (character_type == 'Thaumaturge') {
            addThaumaturgeDefiningMerit();
        }

        updateXP(supernatural);
        updateXP(attribute);
        updateXP(skill);
        updateXP(merit);
    }
}

function getValue(elementId) {
    return document.getElementById(elementId).value;
}

function buildSelect(selected, values_list, names_list, select_name, extra_tags) {
    if (values_list.length != names_list.length) {
        return "Error: lists were of different sizes";
    }

    if (values_list.length == 0) {
        return "No Values to Select.";
    }

    var select = "<select name=\"" + select_name + "\" id=\"" + select_name + "\" " + extra_tags + ">";

    for (var i = 0; i < names_list.length; i++) {
        if (selected == values_list[i]) {
            select += "<option value=\"" + values_list[i] + "\" selected>" + names_list[i] + "</option>";
        }
        else {
            select += "<option value=\"" + values_list[i] + "\">" + names_list[i] + "</option>";
        }
    }

    select += "</select>";

    return select;
}

function makeDotsXP(element_name, element_type, character_type, number_of_dots, value, edit, update_traits, update_xp) {
    var return_value = "";
    var element_id = element_name.replace('_', '-');

    character_type = character_type.toLowerCase();

    for (var i = 1; i <= number_of_dots; i++) {
        var js = "";
        if (edit) {
            js += "changeDots(" + element_type + ", '" + element_name + "'," + i + "," + number_of_dots + ", true);updateXP(" + element_type + ");";
        }

        if (update_traits) {
            js += "updateTraits();";
        }

        if (update_xp) {
            js += "updateXP(" + element_type + ");";
        }

        if (js != "") {
            js = "onClick=\"" + js + "\"";
        }


        if (i <= value) {
            return_value += "<img src=\"img/" + character_type + "_filled.gif\" id=\"" + element_id + "-dot" + i + "\" style=\"border:none;\" " + js + "/>";
        }
        else {
            return_value += "<img src=\"img/empty.gif\" id=\"" + element_id + "-dot" + i + "\" style=\"border:none;\" " + js + "/>";
        }

        if ((i % 10) == 0) {
            return_value += "<br />";
        }
    }

    return_value += "<input type=\"hidden\" name=\"" + element_name + "\" id=\"" + element_id + "\" value=\"" + value + "\">";

    return return_value;
}

function showHelp(element, event) {
    var posx;
    var posy;
    if (event.pageX || event.pageY) {
        posx = event.pageX + 1;
        posy = event.pageY + 1;
    }
    else if (event.clientX || event.clientY) {
        posx = event.clientX + document.body.scrollLeft
            + document.documentElement.scrollLeft;
        posy = event.clientY + document.body.scrollTop
            + document.documentElement.scrollTop;
    }
    document.getElementById(element).style.visibility = "visible";
    document.getElementById(element).style.left = posx + "px";
    document.getElementById(element).style.top = posy + "px";
}

function hideHelp(element) {
    document.getElementById(element).style.visibility = "hidden";
}