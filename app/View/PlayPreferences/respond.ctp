<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Update your Preferences'); ?>
<?php /* @var array $preferences */ ?>
<?php /* @var array $userPrefs */ ?>
<?php
$menu['Actions']['submenu']['Back'] = [
    'link' => [
        'action' => 'index'
    ]
];
$this->set('menu', $menu);
?>
<style>
    table label {
        display: inline;
        padding: 2px 10px 2px 5px;
    }
</style>
<div>
    Player preferences are a tool for the storytelling team to gauge the interests of the venue or scene. Please mark
    whether you enjoy scenes with the following content, level of risk, and play style. Marking that you are willing to
    participate in scenes that risk character loss does not, for example, mean that you will be risking your character
    in every scene. You can also enjoy no-risk 'Kiddy Pool' scenes sometimes and mark yes on that option as well so mark
    all options that apply.
</div>
<?php echo $this->Form->create(); ?>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th>Preference</th>
        <th>Response</th>
    </tr>
    <?php foreach ($preferences as $preference): ?>
        <tr>
            <td>
                <?php echo h($preference['PlayPreference']['name']); ?>
                <?php if ($preference['PlayPreference']['description']): ?>
                    <button class="explanation">Show Explanation</button>
                    <div class="hidden-panel-content">
                        <?php echo h($preference['PlayPreference']['description']); ?>
                    </div>
                <?php endif; ?>
            </td>
            <td style="vertical-align: top;width:100px;">
                <?php echo $this->Form->radio(
                    $preference['PlayPreference']['name'],
                    [
                        1 => 'Yes',
                        0 => 'No'
                    ],
                    [
                        'name' => 'user_preference[' . $preference['PlayPreference']['id'] . ']',
                        'value' => $userPrefs[$preference['PlayPreference']['id']],
                        'legend' => false,
                        'required' => true
                    ]
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tfoot>
    <tr>
        <td colspan="2" style="text-align: center;">
            <?php echo $this->Form->submit('Update'); ?>
        </td>
    </tr>
    </tfoot>
</table>

<?php echo $this->Form->end(); ?>
<script>
    $(function () {
        $('button.explanation')
            .button({
                icons: {
                    primary: 'ui-icon-help'
                },
                text: false
            })
            .click(function () {
                $(this).next('div').toggle();
                return false;
            })
        ;
    });
</script>