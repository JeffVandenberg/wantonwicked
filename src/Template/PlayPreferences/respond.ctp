<?php
use App\Model\Entity\PlayPreference;
use App\View\AppView;

/* @var AppView $this */
/* @var PlayPreference[] $preferences */
/* @var array $userPrefs */

$this->set('title_for_layout', 'Update your Preferences');


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
                <?php echo h($preference->name); ?>
                <?php if ($preference->description): ?>
                    <button class="small button" data-toggle="description-<?php echo $preference->id; ?>">Show Explanation</button>
                    <div class="hide small callout" id="description-<?php echo $preference->id; ?>" data-toggler=".hide">
                        <?php echo h($preference->description); ?>
                    </div>
                <?php endif; ?>
            </td>
            <td style="vertical-align: top;width:100px;">
                <?php echo $this->Form->radio(
                    $preference->name,
                    [
                        1 => 'Yes',
                        0 => 'No'
                    ],
                    [
                        'name' => 'user_preference[' . $preference->id . ']',
                        'value' => $userPrefs[$preference->id],
                        'legend' => false,
                        'required' => true
                    ]
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="2" style="text-align: center;">
            <button class="button" type="submit" value="Update">Update</button>
        </td>
    </tr>
</table>

<?php echo $this->Form->end(); ?>
