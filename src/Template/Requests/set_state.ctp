<?php

use App\Model\Entity\Request;
use App\Model\Entity\RequestNote;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var RequestNote[] $notes
 * @var string $state
 */

$ucState = ucfirst($state);
$this->set('title_for_layout', $ucState . ' Request: ' . $request->title);
?>
<?= $this->Form->create(false); ?>
<div class="row">
    <div class="small-12 columns">
        <label>
            Note
        </label>
        <?= $this->Form->textarea('note', [
            'label' => 'Note',
            'class' => 'tinymce-textarea',
        ]); ?>
    </div>
    <div class="small-12 columns text-center">
        <button class="button" name="action" value="<?= $ucState; ?>" type="submit"><?= $ucState; ?></button>
        <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
    </div>
</div>
<?= $this->Form->end(); ?>
<h3>Request</h3>
<div class="tinymce-content">
    <?= $request->body; ?>
</div>
<h3>Past Notes</h3>
<?php if (count($notes) > 0): ?>
    <?php foreach ($notes as $note): ?>
        <div class="paragraph'  ">
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
    <div class="paragraph">
        No Notes for this Request
    </div>
<?php endif; ?>

