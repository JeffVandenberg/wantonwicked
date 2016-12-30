<?php
/* @var View $this */
$this->set('title_for_layout', 'Create Character');

?>
<form method="post">
    <?php echo $this->Character->edit(); ?>
    <div class="row">
        <div class="small-12 columns text-center">
            <?php echo $this->Form->button('Save', [
                'class' => 'button',
                'id' => 'save',
                'name' => 'action'
            ]); ?>
        </div>
    </div>
</form>
<div class="reveal" id="character-modal" data-reveal>
    <div id="character-modal-content">
        Here is some text!
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<script>
    function validateName() {
        $.get(
            '/characters/validate_name',
            {
                name: $(this).val(),
                city: $("#chronicle").val()
            },
            function (response) {
                console.debug(response);
                alert('back');
            }
        )
    }

    $(function () {
        $("#character_name").blur(validateName);

        $("#character_type").change(function () {
            alert($(this).val());
        });

        $("#add-specialty").click(function () {
            var column = $("#specialty-column"),
                lastRow = column.find('.row').last().clone();
            $("input, select", lastRow).val('');
            lastRow.show();
            column.append(lastRow);
        });

        $(document).on('click', '.remove-specialty', function () {
            var row = $(this).closest('.row');
            row.addClass('callout small secondary');
            setTimeout(function() {
                if (confirm('Are you sure you want to remove this specialty?')) {
                    $('select', this).val('');
                    row.hide();
                } else {
                    row.removeClass('callout small secondary')
                }
            }, 10);
        });

        $("form").submit(function () {
            return false;
        });
    });
</script>
