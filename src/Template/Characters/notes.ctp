<?php
use App\Model\Entity\CharacterNote;
use App\View\AppView;
use classes\character\data\Character;

/* @var AppView $this */
/* @var Character $character */
/* @var CharacterNote[] $rows */

$this->set('title_for_layout', 'Notes for ' . $character->CharacterName);
$this->Paginator->options([
    'update' => '#page-content',
    'evalScripts' => true,
]);
?>
<div id="page-content">
    <table id="content-table">
        <thead>
        <tr>
            <th>Username</th>
            <th>Create</th>
            <th>Note</th>
        </tr>
        </thead>
        <?php foreach ($rows as $data): ?>
            <tr>
                <td>
                    <?php echo $data->user->username; ?>
                </td>
                <td>
                    <?php echo $data->created ?>
                </td>
                <td>
                    <?php echo $data->note; ?>
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


