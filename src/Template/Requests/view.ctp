<?php

use App\Model\Entity\Character;
use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var string $backLink
 * @var Character $characater
 */

$this->set('title_for_layout', 'Request: ' . $request->title);
?>

<div class="row">
    <div class="small-12 columns button-group">
        <?php
        echo $this->Html->link('Back', $backLink, ['class' => 'button']);
        if ((int)$request->request_status_id === RequestStatus::NEW_REQUEST) {
            echo $this->Html->link('Edit', ['action' => 'edit', $request->id], ['class' => 'button']);
        }
        if ((int)$request->request_status_id !== RequestStatus::CLOSED) {
            echo $this->Html->link('Forward', ['action' => 'forward', $request->id], ['class' => 'button']);
            echo $this->Html->link('Close', ['action' => 'close', $request->id], ['class' => 'button', 'confirm' => __('Are you sure you want to close this request?')]);
        }

        if (in_array((int)$request->request_status_id, RequestStatus::$PlayerSubmit, true)) {
            echo $this->Html->link('Submit', ['action' => 'submit', $request->id], ['class' => 'button single-click']);
        }
        if (!in_array((int)$request->request_status_id, RequestStatus::$Terminal, true)) {
        echo $this->Html->link('Add Note', ['action' => 'add-note', $request->id], ['class' => 'button', 'style' => 'display: inline-block']);
        if (in_array((int)$request->request_status_id, RequestStatus::$PlayerEdit, true)) {
            echo $this->Html->link('Attach', ['action' => '#'], ['class' => 'button dead-link', 'style' => 'display: inline-block']);
            ?>
            <button class="dropdown button arrow-only" type="button" style="display: inline-block;"
                    data-toggle="<?php echo $request->id; ?>-dropdown">
                <span class="show-for-sr">Show menu</span>
            </button>
            <div class="dropdown-pane bottom right" id="<?php echo $request->id; ?>-dropdown"
                 data-dropdown data-position="bottom" data-alignment="center" data-auto-focus="true">
                <ul class="vertical menu">
                    <li><?= $this->Html->link('Character', ['action' => 'add-character', $request->id]) ?></li>
                    <li><?= $this->Html->link('Request', ['action' => 'attach-request', $request->id]) ?></li>
                    <li><?= $this->Html->link('Bluebook', ['action' => 'attach-bluebook', $request->id]) ?></li>
                    <?php if ($character): ?>
                        <li><?= $this->Html->link('Die Roll', '/dieroller.php?action=character&character_id=' . $character->id . '&request_id=' . $request->id, ['target' => 'blank']) ?></li>
                    <?php endif; ?>
                    <li><?= $this->Html->link('Scene', ['action' => 'attach-scene', $request->id]) ?></li>
                </ul>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<?php
echo $this->cell('Request', [$request, false]);
?>
<?php $this->start('script'); ?>
<script>
    $(function () {
        $(document).on('click', '.ajax-link', (function (e) {
            const url = $(this).attr('href');
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
