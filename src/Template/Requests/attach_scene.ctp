<?php

use App\Model\Entity\Bluebook;
use App\Model\Entity\Request;
use App\Model\Entity\RequestBluebook;
use App\Model\Entity\SceneRequest;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var SceneRequest $sceneRequest
 * @var array $unattachedScenes
 */

$this->set('title_for_layout', 'Attach Scene to: ' . $request->title);
?>
<?php if (count($unattachedScenes)): ?>
    <?= $this->Form->create($sceneRequest); ?>
    <div class="row">
        <div class="small-12 column">
            <?= $this->Form->control('scene_id', [
                'options' => $unattachedScenes,
                'type' => 'select',
                'label' => 'Scene to Attach'
            ]); ?>
        </div>
        <div class="small-12 column">
            <?= $this->Form->control('note', ['class' => 'tinymce-textarea']); ?>
        </div>
        <div class="small-12 column text-center">
            <button class="button" name="action" value="Attach Scene" type="submit">Attach Scene</button>
            <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
            <?= $this->Form->hidden('request_id', ['value' => $request->id]); ?>
        </div>
    </div>
    <?= $this->Form->end(); ?>
<?php else: ?>
    <div class="row">
        <div class="small-12 columns">
            No Scenes to attach. <br/>
            <?= $this->Html->link('Back', ['action' => 'view', $request->id], [
                'class' => 'button'
            ]); ?>
        </div>
    </div>
<?php endif; ?>
