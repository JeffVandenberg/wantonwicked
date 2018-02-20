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
    <div class="small-12 columns">
        <?= $this->Html->link('Back', ['action' => 'st-dashboard'], ['class' => 'button']); ?>
        <?= $this->Html->link('Add Note', ['action' => 'st-add-note', $request->id], ['class' => 'button']); ?>
        <?php if(in_array($request->request_status_id, RequestStatus::$Storyteller)): ?>
            <?= $this->Html->link('Approve', ['action' => 'st-approve', $request->id], ['class' => 'button']); ?>
            <?= $this->Html->link('Forward', ['action' => 'st-forward', $request->id], ['class' => 'button']); ?>
            <?= $this->Html->link('Deny', ['action' => 'st-deny', $request->id], ['class' => 'button']); ?>
            <?= $this->Html->link('Return', ['action' => 'st-return', $request->id], ['class' => 'button']); ?>
        <?php endif; ?>
        <?php if($isAdmin): ?>
            <?= $this->Html->link('Close', ['action' => 'st-close', $request->id], ['class' => 'button']); ?>
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
