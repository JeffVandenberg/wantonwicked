<?php

use App\Model\Entity\Request;
use App\Model\Entity\RequestNote;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var RequestNote[] $notes
 * @var RequestNote $requestNotes
 */

$this->set('title_for_layout', 'Add Note to: ' . $request->title);
?>
<?= $this->Form->create($requestNote); ?>
<div class="">
    <?= $this->Form->control('note', ['class' => 'tinymce-textarea']); ?>
</div>
<div class="text-center">
    <button class="button" name="action" value="Add Note" type="submit">Add Note</button>
    <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
    <?= $this->Form->hidden('request_id', ['value' => $request->id]); ?>
</div>
<?= $this->Form->end(); ?>
<h3>Request</h3>
<div class="tinymce-content">
    <?= $request->body; ?>
</div>
<?php if (count($notes)): ?>
    <?php foreach ($notes as $note): ?>
        <div>
            <strong>
                <?= $note->created_by->username; ?>
                wrote on
                <?= $this->Time->format($note->created_on); ?>
            </strong>
            <div class="tinymce-content">
                <?= $note->note; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div>
        No notes for this request.
    </div>
<?php endif; ?>
