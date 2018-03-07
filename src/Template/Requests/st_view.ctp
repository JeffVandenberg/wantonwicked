<?php
use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\View\AppView;
/**
 * @var AppView $this
 * @var Request $request
 * @var bool $isAdmin
 */

$this->set('title_for_layout', 'Request: ' . $request->title);
?>

<div class="row">
    <div class="small-12 columns button-group">
        <?= $this->Html->link('Back', ['action' => 'st-dashboard'], ['class' => 'button']); ?>
        <?= $this->Html->link('Add Note', ['action' => 'add-note', $request->id, '?' => ['st' => 1]], ['class' => 'button']); ?>
        <?php if(in_array($request->request_status_id, RequestStatus::$Storyteller)): ?>
            <?= $this->Html->link('Approve', ['action' => 'set-state', $request->id, '?' => ['state' => 'approve']], ['class' => 'button']); ?>
            <?= $this->Html->link('Forward', ['action' => 'forward', $request->id, '?' => ['st' => 1]], ['class' => 'button']); ?>
            <?= $this->Html->link('Deny', ['action' => 'set-state', $request->id, '?' => ['state' => 'deny']], ['class' => 'button']); ?>
            <?= $this->Html->link('Return', ['action' => 'set-state', $request->id, '?' => ['state' => 'return']], ['class' => 'button']); ?>
        <?php endif; ?>
        <?php if($isAdmin): ?>
            <?= $this->Html->link('Close', ['action' => 'set-state', $request->id, '?' => ['state' => 'close', 'st' => 1]], ['class' => 'button']); ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->cell('Request', [$request]); ?>
<?php $this->start('script'); ?>
<script>
    $(function () {
        $(document).on('click', '.ajax-link', (function (e) {
            var url = $(this).attr('href');
            $("#modal-subview-content")
                .load(
                    url,
                    null,
                    function () {
                        $("#modal-subview").foundation('open');
                    }
                );
            e.preventDefault();
        }));
    });
</script>
<?php $this->end(); ?>
