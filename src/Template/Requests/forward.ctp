<?php

use App\Model\Entity\Request;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var array $groups
 */

$this->set('title_for_layout', 'Forward Request');
?>
<?= $this->Form->create($request); ?>
    <div class="row">
        <div class="small-12 column">
            Select the group that you want to forward &quot;<?php echo htmlspecialchars($request->title); ?>&quot; to
        </div>
        <div class="small-12 column">
            <?= $this->Form->select('group_id', $groups, [
                'value' => $request->group_id
            ]); ?>
        </div>
        <div class="small-12 column text-center">
            <button class="button" name="action" value="Forward" type="submit">Forward</button>
            <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
            <?= $this->Form->hidden('request_id', ['value' => $request->id]); ?>
        </div>
    </div>
<?= $this->Form->end(); ?>
