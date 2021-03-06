<?php
use App\Model\Entity\CharacterPower;
use App\View\AppView;

/* @var CharacterPower[] $characters ; */
/* @var string $type ; */
/* @var AppView $this */

$this->set('title_for_layout', ucfirst($type) . ' Characters');
$this->Paginator->options(array(
    'update' => '#page-content',
    'evalScripts' => true,
)); ?>
<div id="page-content">
    <div class="row align-center">
        <div class="small-12 medium-4 column end">
            <label for="character_id" style="display: inline;">Character Type
                <?php echo $this->Form->select('character_type', $characterTypes, array('value' => ucfirst($type),
                        'empty' => false,
                    )
                ); ?>
            </label>
        </div>
    </div>
    <table id="content-table">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('character_name'); ?></th>
            <th><?php echo $this->Paginator->sort('Users.username', 'Player'); ?></th>
            <?php if (strtolower($type) == 'all'): ?>
                <th><?php echo $this->Paginator->sort('character_type'); ?></th>
            <?php endif; ?>
            <th><?php echo $this->Paginator->sort('splat1'); ?></th>
            <th><?php echo $this->Paginator->sort('splat2'); ?></th>
            <th><?php echo $this->Paginator->sort('is_npc'); ?></th>
            <th></th>
        </tr>
        </thead>
        <?php foreach ($characters as $character): ?>
            <tr>
                <td>
                    <?php echo $this->Html->link(
                        $character->character->character_name,
                        '/wiki/?n=Players.' . preg_replace('/[^\w]+/', '',
                            $character->character->character_name
                        )
                    ); ?>
                </td>
                <td>
                    <?php echo $character->character->user->username; ?>
                </td>
                <?php if (strtolower($type) == 'all'): ?>
                    <td>
                        <?php echo $this->Html->link(
                            $character->character->character_type,
                            array(
                                $character->character->character_type
                            )
                        ); ?>
                    </td>
                <?php endif; ?>
                <td>
                    <?php echo $character->character->splat1; ?>
                </td>
                <td>
                    <?php echo $character->character->splat2; ?>
                </td>
                <td>
                    <?php echo ($character->character->is_npc == 'Y') ? 'Yes' : 'No'; ?>
                </td>
                <td>
                    <?php echo nl2br($character->power_name); ?>
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
        $("#character_type").change(function () {
            document.location = '/characters/stGoals/' + $(this).val().toLowerCase();
        });

        $(document).on('click', '.pagination a, #content-table thead a', function () {
            var target = $(this).attr('href');

            $.get(target, function (data) {
                $('#page-content').html($(data).filter("#page-content"));
                var state = {html: 'doTo'};
                window.history.pushState(state, 'Beat Types', target);
            }, 'html');

            return false;
        });
    });
</script>
