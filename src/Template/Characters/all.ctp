<?php

use App\Model\Entity\Character;
use App\View\AppView;

/* @var Character[] $characters ; */
/* @var AppView $this */

$this->set('title_for_layout', 'All of your Characters');
?>
<div id="page-content">
    <div class="row">
        <div class="small-12 column">
            <?php if ($isLoggedIn): ?>
                <?= $this->Html->link('Active Game',
                    ['action' => 'index'],
                    ['class' => 'button']
                ); ?>
                <?= $this->Html->link('New Character',
                    ['action' => 'add'],
                    ['class' => 'button']
                ); ?>
            <?php endif; ?>
            <?= $this->Html->link('View Other Character',
                ['action' => 'view-other'],
                ['class' => 'button']
            ); ?>
        </div>
        <div class="small-12 column">
            <?php if ($characters && count($characters)): ?>
                <table id="content-table">
                    <thead>
                    <tr>
                        <th><?php echo $this->Paginator->sort('character_name'); ?></th>
                        <th><?php echo $this->Paginator->sort('character_type'); ?></th>
                        <th><?php echo $this->Paginator->sort('splat1'); ?></th>
                        <th><?php echo $this->Paginator->sort('splat2'); ?></th>
                        <th>Status</th>
                        <th>City</th>
                    </tr>
                    </thead>
                    <?php foreach ($characters as $character): ?>
                        <tr>
                            <td>
                                <?= $this->Html->link(
                                    $character->character_name,
                                    [
                                        'action' => 'view-own',
                                        $character->slug
                                    ]
                                ); ?>
                            </td>
                            <td>
                                <?= ucfirst($character->character_type) ?>
                            </td>
                            <td>
                                <?= $character->splat1; ?>
                            </td>
                            <td>
                                <?= $character->splat2; ?>
                            </td>
                            <td><?= $character->character_status->name; ?></td>
                            <td><?= $character->city; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="paginator small callout">
                    <ul class="pagination">
                        <?php if ($this->Paginator->hasPrev()): ?>
                            <?= $this->Paginator->first('<< ' . __('First')) ?>
                            <?= $this->Paginator->prev('< ' . __('Previous')) ?>
                        <?php endif; ?>
                        <?= $this->Paginator->numbers() ?>
                        <?php if ($this->Paginator->hasNext()): ?>
                            <?= $this->Paginator->next(__('Next') . ' >') ?>
                            <?= $this->Paginator->last(__('Last') . ' >>') ?>
                        <?php endif; ?>
                    </ul>
                    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                </div>
            <?php else: ?>
                You have no characters.
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    $(function () {
        $(document).on('click', '.pagination a, #content-table thead a', function () {
            var target = $(this).attr('href');

            $.get(target, function (data) {
                $('#page-content').html($(data).filter("#page-content"));
                var state = {html: 'doTo'};
                window.history.pushState(state, 'Cast', target);

            }, 'html');

            return false;
        });
    });
</script>
