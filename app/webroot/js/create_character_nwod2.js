/**
 * Created by JeffVandenberg on 1/20/2017.
 */
Foundation.Abide.defaults.validators['character_name'] = function ($el, required, parent) {
    if (!required) {
        return true;
    }

    if ($.trim($el.val()) === '') {
        parent.find('.form-error').text('Character Name is required');
        return false;
    }

    var result = false;
    $.ajax({
        method: 'get',
        url: '/characters/validateName',
        data: {
            id: $('#character_id').val(),
            name: $el.val(),
            city: $("#city").val()
        },
        async: false,
        success: function (response) {
            response = JSON.parse(response);
            if (response.success) {
                parent.find('.form-error').text('Character Name is already in use.');
                result = !response.in_use;
            } else {
                alert('Error validating your character name');
            }
        }
    });

    return result;
};

function removeCharacterRow(row, message, secondaryTable) {
    row.find('td').addClass('callout secondary');
    setTimeout(function () {
        if (confirm(message)) {
            row.find('input').first().val('');
            row.detach();
            $(secondaryTable).append(row);
        } else {
        }
        row.find('td').removeClass('callout secondary');
    }, 10);
}

function addCharacterRow(tableId) {
    var table = $("#" + tableId),
        removedTable = $("#removed-" + tableId),
        row;
    if (table.find('tbody > tr').length > 0) {
        row = table.find('tr').last().clone();
    } else {
        row = removedTable.find('tr').last().clone();
    }

    var count = table.find('tbody > tr').length + removedTable.find('tbody > tr').length;
    $("input[type=text], select, input[type=hidden]", row).each(function () {
        var currentName = $(this).attr('name');
        $(this).attr('name', currentName.replace(/\[[0-9]+\]/, "[" + count + "]"));
        $(this).val('');
    });
    $("input[type=checkbox]", row).each(function () {
        var currentName = $(this).attr('name');
        $(this).attr('name', currentName.replace(/\[[0-9]+\]/, "[" + count + "]"));
        $(this).attr('checked', false)
    });
    table.append(row);
}

function addFoundationRow(sectionId) {
    var section = $("#" + sectionId),
        lastRow = section.find('.row').last().clone(),
        count = section.find('.row').length;

    $("input, select", lastRow).each(function () {
        var currentName = $(this).attr('name');
        $(this).attr('name', currentName.replace(/\[[0-9]+\]/, "[" + count + "]"));
        $(this).val('');
    });
    lastRow.show();
    section.append(lastRow);
}

$(function () {
    $(document).on('change', '#character_type', function () {
        document.location = document.location.pathname + addUrlParam(
                document.location.search,
                'character_type',
                $(this).val()
            );
        return false;
    });

    $(document).on('click', "#add-specialty", function () {
        addFoundationRow('specialties');
    });

    $(document).on('click', '.remove-specialty', function () {
        var row = $(this).closest('.row');
        row.addClass('callout small secondary');
        setTimeout(function () {
            if (confirm('Are you sure you want to remove this specialty?')) {
                $('select', row).val('');
                row.removeClass('callout small secondary')
                row.hide();
            } else {
                row.removeClass('callout small secondary')
            }
        }, 10);
    });

    $(document).on('click', ".add-character-row", function () {
        var target = $(this).data().targetTable;
        if (target) {
            addCharacterRow($(this).data().targetTable);
        } else {
            console.error("No data-target-table specified");
        }
    });

    $(document).on('click', '.remove-character-row', function() {
        var row = $(this).closest('tr'),
            target = 'removed-' + $(this).data().targetTable;

        removeCharacterRow(row, 'Are you sure you want to remove this?', "#" + target);
    });

    $(document).on('click', '.add-foundation-row', function () {
        var target = $(this).data().targetTable;
        if (target) {
            addFoundationRow($(this).data().targetTable);
        } else {
            console.error("No data-target-table specified");
        }
    });

    $(document).on('click', '#add-aspiration', function () {
        addCharacterRow('aspirations')
    });
    $(document).on('click', '#add-merit', function () {
        addCharacterRow('merits');
    });
    $(document).on('click', '.remove-merit', function () {
        var row = $(this).closest('tr');
        removeCharacterRow(row, 'Are you sure you want to remove this merit?', "#removed-merits");
    });

    $(document).on('click', '#add-misc-power', function () {
        addCharacterRow('misc-abilities');
    });
    $(document).on('click', '.remove-misc-power', function () {
        var row = $(this).closest('tr');
        removeCharacterRow(row, 'Are you sure you want to remove this power?', "#removed-misc-abilities");
    });

    $(document).on('click', '#add-equipment-button', function () {
        addCharacterRow("equipment");
        return false;
    });
    $(document).on('click', '.remove-equipment', function () {
        var row = $(this).closest('tr');
        removeCharacterRow(row, 'Are you sure you want to remove this equipment?', "#removed-equipment");
    });

    $("form").submit(function () {
    });
});
