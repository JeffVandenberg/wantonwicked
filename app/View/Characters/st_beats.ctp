<?php
/* @var View $this */
/* @var array $beatList */
$this->set('title_for_layout', 'Grant Beats');
?>

<form method="post">
    <div class="row">
        <div class="row align-middle">
            <div class="small-12 medium-4 column">
                <label for="character_name">Character</label>
                <input type="text" name="character_name" id="character_name"/>
                <input type="hidden" name="character_id" id="character_id"/>
            </div>
            <div class="small-12 medium-2 column">
                <label>City
                    <?php echo $this->Form->select('city', $cities, [
                        'label' => 'City',
                        'empty' => false
                    ]); ?>
                </label>
            </div>
            <div class="small-6 medium-4 column">
                <label>
                    Beat Type
                    <?php echo $this->Form->select(
                        'beat_type_id',
                        $beatList,
                        [
                            'label' => false,
                            'empty' => false
                        ]); ?>
                </label>
            </div>
            <div class="small-6 medium-2 column bottom">
                <?php echo $this->Form->button('Add', [
                    'type' => 'submit',
                    'value' => 'Add',
                    'class' => 'button',
                    'name' => 'action',
                ]); ?>
            </div>
            <div class="small-12 column">
                Note
                <?php echo $this->Form->textarea('note', [
                    'rows' => 5
                ]); ?>
            </div>
        </div>
    </div>
</form>
<script>
    $(function () {
        $("#character_name").autocomplete({
            serviceUrl: '/character.php?action=search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {
            },
            onSearchStart: function (query) {
                query.city = $('#city').val();
                query.only_sanctioned = 1
            },
            onSelect: function(item) {
                $("#character_id").val(item.data);
                $("#character_name").val(item.value);
                return false;
            }
        });

        $('form').submit(function() {
            if(!$("#character_id").val()) {
                alert('Please select a character.');
                return false;
            }
        })
    });
</script>
