<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Your Player Preference'); ?>
<?php /* @var array $preferences */ ?>
<?php
$menu['Actions']['submenu']['Respond'] = [
    'link' => [
        'action' => 'respond'
    ]
];
if($isHead) {
    $menu['Action']['submenu']['Manage'] = [
        'link' => [
            'action' => 'manage'
        ]
    ];
}
$this->set('menu', $menu);
?>
<?php if (count($preferences)): ?>
    <div class="playPreferences index">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th>Type</th>
                <th>Response</th>
            </tr>
            <?php foreach ($preferences as $preference): ?>
                <tr>
                    <td>
                        <?php echo h($preference['PlayPreference']['name']); ?>
                        <?php if ($preference['PlayPreference']['description']): ?>
                            <button class="explanation">Show Explanation</button>
                            <div class="panel-content" style="display:none;">
                                <?php echo h($preference['PlayPreference']['description']); ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td style="vertical-align: top;">
                        <?php echo ($preference['PlayPreferenceResponse']['rating']) ? 'Yes' : 'No'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php else: ?>
    You haven't filled in your player preference Survey. Perhaps you should?
    <?php echo $this->Html->link('Fill in Player Preference Survey', [
        'action' => 'respond'
    ]); ?>
<?php endif; ?>
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
            })
        ;
    });
</script>