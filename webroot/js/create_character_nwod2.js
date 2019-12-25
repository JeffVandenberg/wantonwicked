/**
 * Created by JeffVandenberg on 1/20/2017.
 */
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
    const table = $("#" + tableId),
        removedTable = $("#removed-" + tableId);
    let row;
    if (table.find('tbody > tr').length > 0) {
        row = table.find('tr').last().clone();
    } else {
        row = removedTable.find('tr').last().clone();
    }

    const count = table.find('tbody > tr').length + removedTable.find('tbody > tr').length;
    $("input[type=text], select, input[type=hidden]", row).each(function () {
        const currentName = $(this).attr('name');
        $(this).attr('name', currentName.replace(/\[[0-9]+]/, "[" + count + "]"));
        $(this).val('');
    });
    $("input[type=checkbox]", row).each(function () {
        const currentName = $(this).attr('name');
        $(this).attr('name', currentName.replace(/\[[0-9]+]/, "[" + count + "]"));
        $(this).attr('checked', false)
    });
    table.append(row);
}

function addFoundationRow(sectionId) {
    const section = $("#" + sectionId),
        lastRow = section.find('.row').last().clone(),
        count = section.find('.row').length;

    $("input, select", lastRow).each(function () {
        const currentName = $(this).attr('name');
        $(this).attr('name', currentName.replace(/\[[0-9]+]/, "[" + count + "]"));
        $(this).val('');
    });
    lastRow.show();
    section.append(lastRow);
}

function validateCharacterName(form, submitForm) {
    new Promise((resolve, reject) => {
        $.ajax({
            method: 'get',
            url: '/characters/validateName.json',
            data: {
                id: $('#character_id').val(),
                name: $('#character_name').val(),
                city: $("#city").val()
            },
            success: function (response) {
                if (response.success) {
                    resolve(response);
                } else {
                    reject(new Error('Error validating your character name.'));
                }
            }
        });
    })
        .then(data => {
                if (data.in_use) {
                    throw new Error('Character Name in use');
                } else {
                    form.data().isValid = true;
                    if (submitForm) {
                        form.submit();
                        $.toast({
                            text: "Submitting Character",
                            position: 'top-right',
                            icon: 'info',
                            allowToastClose: true
                        });
                    }
                }
            }
        )
        .catch(error => {
            form.data().isValid = false;
            $.toast({
                text: error.message,
                position: 'top-right',
                icon: 'error',
                allowToastClose: true,
                hideAfter: false
            });
        })
        .finally(() => {
            $('#save-character-button').removeClass('disabled').attr('disabled', false);
        });
}

function validateForm(form, submitForm) {
    $.toast({text: 'Validating character', position: 'top-right'});
    if ($("#character_name").length > 0) {
        validateCharacterName(form, submitForm);
    }
}

$(() => {
    $(document).on('blur', '#character_name', e => {
        validateForm($(e.currentTarget).closest('form'), false);
    });
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
        const row = $(this).closest('.row');
        row.addClass('callout small secondary');
        setTimeout(function () {
            if (confirm('Are you sure you want to remove this specialty?')) {
                $('select', row).val('');
                row.removeClass('callout small secondary');
                row.hide();
            } else {
                row.removeClass('callout small secondary')
            }
        }, 10);
    });

    $(document).on('click', ".add-character-row", function () {
        const target = $(this).data().targetTable;
        if (target) {
            addCharacterRow($(this).data().targetTable);
        } else {
            console.error("No data-target-table specified");
        }
    });

    $(document).on('click', '.remove-character-row', function () {
        const row = $(this).closest('tr'),
            target = 'removed-' + $(this).data().targetTable;

        removeCharacterRow(row, 'Are you sure you want to remove this?', "#" + target);
    });

    $(document).on('click', '.add-foundation-row', function () {
        const target = $(this).data().targetTable;
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
        const row = $(this).closest('tr');
        removeCharacterRow(row, 'Are you sure you want to remove this merit?', "#removed-merits");
    });

    $(document).on('click', '#add-misc-power', function () {
        addCharacterRow('misc-abilities');
    });
    $(document).on('click', '.remove-misc-power', function () {
        const row = $(this).closest('tr');
        removeCharacterRow(row, 'Are you sure you want to remove this power?', "#removed-misc-abilities");
    });

    $(document).on('click', '#add-equipment-button', function () {
        addCharacterRow("equipment");
        return false;
    });
    $(document).on('click', '.remove-equipment', function () {
        const row = $(this).closest('tr');
        removeCharacterRow(row, 'Are you sure you want to remove this equipment?', "#removed-equipment");
    });

    $("form").submit(e => {
        let form = $(e.currentTarget);
        $('#save-character-button').addClass('disabled').attr('disabled', true);
        if ('isValid' in form.data() && form.data().isValid) {
            return true;
        }
        validateForm(form, true);
        e.preventDefault();
    });
});
