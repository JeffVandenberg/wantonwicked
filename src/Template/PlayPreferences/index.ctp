<?php
use App\Model\Entity\PlayPreferenceResponse;
use App\View\AppView;

/* @var AppView $this */
/* @var PlayPreferenceResponse[] $preferences */

$this->set('title_for_layout', 'Your Player Preference');
$action = (count($preferences) > 0) ? 'Update Responses' : 'Respond';

$menu['Actions']['submenu'][$action] = [
    'link' => [
        'action' => 'respond'
    ]
];
if ($isSt) {
    $menu['Actions']['submenu']['Venue Report'] = [
        'link' => [
            'action' => 'report_venue'
        ]
    ];
}
if ($isHead) {
    $menu['Actions']['submenu']['Manage'] = [
        'link' => [
            'action' => 'manage'
        ]
    ];
}
$this->set('menu', $menu);
?>
<?php if (count($preferences)): ?>
    <div>
        Below are your indicated preferences. Feel free to <?php echo $this->Html->link('Update your Play Preferences', [
            'action' => 'respond'
        ]); ?>

    </div>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>Type</th>
            <th>Response</th>
        </tr>
        <?php foreach ($preferences as $preference): ?>
            <tr>
                <td>
                    <?php echo h($preference->play_preference->name); ?>
                    <?php if ($preference->play_preference->description): ?>
                        <button class="small button" data-toggle="description-<?php echo $preference->play_preference_id; ?>">Show Explanation</button>
                        <div class="hide small callout" id="description-<?php echo $preference->play_preference_id; ?>" data-toggler=".hide">
                            <?php echo h($preference->play_preference->description); ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td style="vertical-align: top;width:100px;">
                    <?php echo ($preference->rating) ? 'Yes' : 'No'; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    You haven't filled in your player preference Survey. Perhaps you should?
    <?php echo $this->Html->link('Fill in Play Preferences.', [
        'action' => 'respond'
    ]); ?>
<?php endif; ?>
<script>
    $(function () {
        $('button.explanation')
            .click(function () {
                $(this).next('div').toggle();
            })
        ;
    });
</script>
