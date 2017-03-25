<?php
use App\Model\Entity\PlayPreference;
use App\View\AppView;

/* @var AppView $this */
/* @var PlayPreference[] $playPreferences */

$this->set('title_for_layout', 'Manage Player Preference Options');
$menu['Actions']['submenu']['New Play Preference'] = [
    'link' => [
        'action' => 'add'
    ]
];
$this->set('menu', $menu);
?>
<div class="playPreferences index">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($playPreferences as $playPreference): ?>
            <tr>
                <td><?php echo h($playPreference->name); ?>&nbsp;</td>
                <td><?php echo h($playPreference->description); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'),
                        array('action' => 'view', $playPreference->id)); ?>
                    <?php echo $this->Html->link(__('Edit'),
                        array('action' => 'edit', $playPreference->id)); ?>
                    <?php echo $this->Form->postLink(__('Delete'),
                        array('action' => 'delete', $playPreference->id),
                        ['confirm' => __('Are you sure you want to delete # {0}?', $playPreference['PlayPreference']['id'])]
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paginator small callout">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('Previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
