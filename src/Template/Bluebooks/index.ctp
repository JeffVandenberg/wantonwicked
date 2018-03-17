<?php

use App\Model\Entity\Bluebook;
use App\Model\Entity\Request;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Bluebook[] $bluebooks
 * @var array $characters
 */

$this->set('title_for_layout', 'Your Bluebooks');
?>
<div class="clearfix">
    <div class="">
        <form method="get" action="/bluebooks/add">
            <?= $this->Form->select('character_id', $characters, ['style' => 'display:inline-block;width:300px;',]); ?>
            <button type="submit" value="New Request" class="button">New Bluebook Entry</button>
        </form>
    </div>
</div>
<div id="page-content">
    <table id="content-table" class="stack">
        <thead>
        <tr>
            <th>
                <?= $this->Paginator->sort('title'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('created_on'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('updated_on'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('Characters.character_name', 'Character'); ?>
            </th>
        </tr>
        </thead>
        <?php foreach ($bluebooks as $bluebook): ?>
            <tr>
                <td>
                    <?= $this->Html->link($bluebook->title, ['action' => 'view', $bluebook->id]); ?>
                </td>
                <td>
                    <?= $this->Time->format($bluebook->created_on); ?>
                </td>
                <td>
                    <?= $this->Time->format($bluebook->updated_on); ?>
                </td>
                <td>
                    <?= $bluebook->character->character_name; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
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
