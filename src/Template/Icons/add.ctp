<?php
use App\Model\Entity\Icon;

/**
 * @var \App\View\AppView $this
 * @var Icon $icon
 */

$this->set('title_for_layout', 'Add Icon');
?>
<div class="icons form content">
    <?= $this->Form->create($icon) ?>
    <table class="vertical-table">
        <tr>
            <td>
                <?= $this->Form->control('id'); ?>
                <?= $this->Form->control('icon_name'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>File Name</label>
                <?= $this->Form->text('icon_id'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Player Viewable</label>
                <?= $this->Form->checkbox('player_viewable', [
                    'checked' => $icon->player_viewable === 'Y',
                    'required' => false
                ]); ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Staff Viewable</label>
                <?= $this->Form->checkbox('staff_viewable', [
                    'checked' => $icon->staff_viewable === 'Y',
                    'required' => false
                ]); ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Admin Viewable</label>
                <?= $this->Form->checkbox('admin_viewable', [
                    'checked' => $icon->admin_viewable === 'Y',
                    'required' => false
                ]); ?>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <button type="submit" value="save" name="action" class="button">Save</button>
                <button type="submit" value="cancel" name="action" class="button">Cancel</button>
            </td>
        </tr>
    </table>
    <?= $this->Form->end() ?>
</div>
