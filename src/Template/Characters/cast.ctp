<?php

use App\Model\Entity\Character;
use App\View\AppView;

/* @var Character[] $characters ; */
/* @var string $type ; */
/* @var bool $mayManageCharacters ; */
/* @var AppView $this */

$this->set('title_for_layout', ucfirst($type) . ' Characters');
$this->Paginator->options([
    'update' => '#page-content',
    'evalScripts' => true,
]); ?>
<div id="page-content">
    <div class="row align-center">
        <div class="small-12 medium-4 column end">
            <label for="character_id" style="display: inline;">Character Type
                <?php echo $this->Form->select('character_type', $characterTypes, array('value' => ucfirst($type),
                        'empty' => false,
                        'id' => 'character_type'
                    )
                ); ?>
            </label>
        </div>
    </div>
    <table id="content-table">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('character_name'); ?></th>
            <th><?php echo $this->Paginator->sort('Users.username', 'Player', ['model' => 'Users']); ?></th>
            <?php if (strtolower($type) == 'all'): ?>
                <th><?php echo $this->Paginator->sort('character_type'); ?></th>
            <?php endif; ?>
            <th><?php echo $this->Paginator->sort('splat1'); ?></th>
            <th><?php echo $this->Paginator->sort('splat2'); ?></th>
            <th>Status</th>
            <th><?php echo $this->Paginator->sort('is_npc'); ?></th>
            <?php if ($mayManageCharacters): ?>
                <th>
                    Admin
                </th>
            <?php endif; ?>
        </tr>
        </thead>
        <?php foreach ($characters

                       as $character): ?>
            <tr>
                <td>
                    <?php echo $this->Html->link(
                        $character->character_name,
                        '/wiki/?n=Players.' . preg_replace('/[^\w]+/', '',
                            $character->character_name
                        )
                    ); ?>
                </td>
                <td>
                    <?php echo $character->user->username; ?>
                </td>
                <?php if (strtolower($type) == 'all'): ?>
                    <td>
                        <?php echo $this->Html->link(
                            ucfirst($character->character_type),
                            [
                                $character->character_type
                            ]
                        ); ?>
                    </td>
                <?php endif; ?>
                <td>
                    <?php echo $character->splat1; ?>
                </td>
                <td>
                    <?php echo $character->splat2; ?>
                </td>
                <td>
                    <?php echo $character->character_status->name; ?>
                </td>
                <td>
                    <?php echo ($character->is_npc == 'Y') ? 'Yes' : 'No'; ?>
                </td>
                <td>
                    <?php echo $this->Html->link(
                        'Edit',
                        [
                            'action' => 'st-view',
                            $character->slug
                        ],
                        [
                            'class' => 'button'
                        ]
                    ); ?>
                </td>
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
</div>
<script>
    $(function () {
        $(document).on('change', "#character_type", function () {
            document.location = '/characters/cast/' + $(this).val().toLowerCase();
        });

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
