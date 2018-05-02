<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Location $location
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Location'), ['action' => 'edit', $location->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Location'), ['action' => 'delete', $location->id], ['confirm' => __('Are you sure you want to delete # {0}?', $location->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Districts'), ['controller' => 'Districts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New District'), ['controller' => 'Districts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Location Types'), ['controller' => 'LocationTypes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location Type'), ['controller' => 'LocationTypes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Location Traits'), ['controller' => 'LocationTraits', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location Trait'), ['controller' => 'LocationTraits', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="locations view large-9 medium-8 columns content">
    <h3><?= h($location->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('District') ?></th>
            <td><?= $location->has('district') ? $this->Html->link($location->district->id, ['controller' => 'Districts', 'action' => 'view', $location->district->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location Name') ?></th>
            <td><?= h($location->location_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location Image') ?></th>
            <td><?= h($location->location_image) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Owning Character Name') ?></th>
            <td><?= h($location->owning_character_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location Type') ?></th>
            <td><?= $location->has('location_type') ? $this->Html->link($location->location_type->name, ['controller' => 'LocationTypes', 'action' => 'view', $location->location_type->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($location->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By Id') ?></th>
            <td><?= $this->Number->format($location->created_by_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated By Id') ?></th>
            <td><?= $this->Number->format($location->updated_by_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Character Id') ?></th>
            <td><?= $this->Number->format($location->character_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created On') ?></th>
            <td><?= h($location->created_on) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated On') ?></th>
            <td><?= h($location->updated_on) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $location->is_active ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Private') ?></th>
            <td><?= $location->is_private ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Location Description') ?></h4>
        <?= $this->Text->autoParagraph(h($location->location_description)); ?>
    </div>
    <div class="row">
        <h4><?= __('Location Rules') ?></h4>
        <?= $this->Text->autoParagraph(h($location->location_rules)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Characters') ?></h4>
        <?php if (!empty($location->characters)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Character Name') ?></th>
                <th scope="col"><?= __('Show Sheet') ?></th>
                <th scope="col"><?= __('View Password') ?></th>
                <th scope="col"><?= __('Character Type') ?></th>
                <th scope="col"><?= __('City') ?></th>
                <th scope="col"><?= __('Age') ?></th>
                <th scope="col"><?= __('Sex') ?></th>
                <th scope="col"><?= __('Apparent Age') ?></th>
                <th scope="col"><?= __('Concept') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Url') ?></th>
                <th scope="col"><?= __('Safe Place') ?></th>
                <th scope="col"><?= __('Friends') ?></th>
                <th scope="col"><?= __('Exit Line') ?></th>
                <th scope="col"><?= __('Icon') ?></th>
                <th scope="col"><?= __('Is Npc') ?></th>
                <th scope="col"><?= __('Virtue') ?></th>
                <th scope="col"><?= __('Vice') ?></th>
                <th scope="col"><?= __('Splat1') ?></th>
                <th scope="col"><?= __('Splat2') ?></th>
                <th scope="col"><?= __('Subsplat') ?></th>
                <th scope="col"><?= __('Size') ?></th>
                <th scope="col"><?= __('Speed') ?></th>
                <th scope="col"><?= __('Initiative Mod') ?></th>
                <th scope="col"><?= __('Defense') ?></th>
                <th scope="col"><?= __('Armor') ?></th>
                <th scope="col"><?= __('Health') ?></th>
                <th scope="col"><?= __('Wounds Agg') ?></th>
                <th scope="col"><?= __('Wounds Lethal') ?></th>
                <th scope="col"><?= __('Wounds Bashing') ?></th>
                <th scope="col"><?= __('Willpower Perm') ?></th>
                <th scope="col"><?= __('Willpower Temp') ?></th>
                <th scope="col"><?= __('Power Stat') ?></th>
                <th scope="col"><?= __('Power Points') ?></th>
                <th scope="col"><?= __('Morality') ?></th>
                <th scope="col"><?= __('Merits') ?></th>
                <th scope="col"><?= __('Flaws') ?></th>
                <th scope="col"><?= __('Equipment Public') ?></th>
                <th scope="col"><?= __('Equipment Hidden') ?></th>
                <th scope="col"><?= __('Public Effects') ?></th>
                <th scope="col"><?= __('History') ?></th>
                <th scope="col"><?= __('Character Notes') ?></th>
                <th scope="col"><?= __('Goals') ?></th>
                <th scope="col"><?= __('Current Experience') ?></th>
                <th scope="col"><?= __('Total Experience') ?></th>
                <th scope="col"><?= __('Bonus Received') ?></th>
                <th scope="col"><?= __('Updated By Id') ?></th>
                <th scope="col"><?= __('Updated On') ?></th>
                <th scope="col"><?= __('Gm Notes') ?></th>
                <th scope="col"><?= __('Sheet Update') ?></th>
                <th scope="col"><?= __('Hide Icon') ?></th>
                <th scope="col"><?= __('Helper') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Bonus Attribute') ?></th>
                <th scope="col"><?= __('Misc Powers') ?></th>
                <th scope="col"><?= __('Average Power Points') ?></th>
                <th scope="col"><?= __('Power Points Modifier') ?></th>
                <th scope="col"><?= __('Temporary Health Levels') ?></th>
                <th scope="col"><?= __('Is Suspended') ?></th>
                <th scope="col"><?= __('Location Id') ?></th>
                <th scope="col"><?= __('Gameline') ?></th>
                <th scope="col"><?= __('Slug') ?></th>
                <th scope="col"><?= __('Character Status Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($location->characters as $characters): ?>
            <tr>
                <td><?= h($characters->id) ?></td>
                <td><?= h($characters->user_id) ?></td>
                <td><?= h($characters->character_name) ?></td>
                <td><?= h($characters->show_sheet) ?></td>
                <td><?= h($characters->view_password) ?></td>
                <td><?= h($characters->character_type) ?></td>
                <td><?= h($characters->city) ?></td>
                <td><?= h($characters->age) ?></td>
                <td><?= h($characters->sex) ?></td>
                <td><?= h($characters->apparent_age) ?></td>
                <td><?= h($characters->concept) ?></td>
                <td><?= h($characters->description) ?></td>
                <td><?= h($characters->url) ?></td>
                <td><?= h($characters->safe_place) ?></td>
                <td><?= h($characters->friends) ?></td>
                <td><?= h($characters->exit_line) ?></td>
                <td><?= h($characters->icon) ?></td>
                <td><?= h($characters->is_npc) ?></td>
                <td><?= h($characters->virtue) ?></td>
                <td><?= h($characters->vice) ?></td>
                <td><?= h($characters->splat1) ?></td>
                <td><?= h($characters->splat2) ?></td>
                <td><?= h($characters->subsplat) ?></td>
                <td><?= h($characters->size) ?></td>
                <td><?= h($characters->speed) ?></td>
                <td><?= h($characters->initiative_mod) ?></td>
                <td><?= h($characters->defense) ?></td>
                <td><?= h($characters->armor) ?></td>
                <td><?= h($characters->health) ?></td>
                <td><?= h($characters->wounds_agg) ?></td>
                <td><?= h($characters->wounds_lethal) ?></td>
                <td><?= h($characters->wounds_bashing) ?></td>
                <td><?= h($characters->willpower_perm) ?></td>
                <td><?= h($characters->willpower_temp) ?></td>
                <td><?= h($characters->power_stat) ?></td>
                <td><?= h($characters->power_points) ?></td>
                <td><?= h($characters->morality) ?></td>
                <td><?= h($characters->merits) ?></td>
                <td><?= h($characters->flaws) ?></td>
                <td><?= h($characters->equipment_public) ?></td>
                <td><?= h($characters->equipment_hidden) ?></td>
                <td><?= h($characters->public_effects) ?></td>
                <td><?= h($characters->history) ?></td>
                <td><?= h($characters->character_notes) ?></td>
                <td><?= h($characters->goals) ?></td>
                <td><?= h($characters->current_experience) ?></td>
                <td><?= h($characters->total_experience) ?></td>
                <td><?= h($characters->bonus_received) ?></td>
                <td><?= h($characters->updated_by_id) ?></td>
                <td><?= h($characters->updated_on) ?></td>
                <td><?= h($characters->gm_notes) ?></td>
                <td><?= h($characters->sheet_update) ?></td>
                <td><?= h($characters->hide_icon) ?></td>
                <td><?= h($characters->helper) ?></td>
                <td><?= h($characters->status) ?></td>
                <td><?= h($characters->bonus_attribute) ?></td>
                <td><?= h($characters->misc_powers) ?></td>
                <td><?= h($characters->average_power_points) ?></td>
                <td><?= h($characters->power_points_modifier) ?></td>
                <td><?= h($characters->temporary_health_levels) ?></td>
                <td><?= h($characters->is_suspended) ?></td>
                <td><?= h($characters->location_id) ?></td>
                <td><?= h($characters->gameline) ?></td>
                <td><?= h($characters->slug) ?></td>
                <td><?= h($characters->character_status_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Characters', 'action' => 'view', $characters->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Characters', 'action' => 'edit', $characters->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Characters', 'action' => 'delete', $characters->id], ['confirm' => __('Are you sure you want to delete # {0}?', $characters->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Location Traits') ?></h4>
        <?php if (!empty($location->location_traits)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Location Id') ?></th>
                <th scope="col"><?= __('Trait Name') ?></th>
                <th scope="col"><?= __('Trait Value') ?></th>
                <th scope="col"><?= __('Note') ?></th>
                <th scope="col"><?= __('Is Private') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($location->location_traits as $locationTraits): ?>
            <tr>
                <td><?= h($locationTraits->id) ?></td>
                <td><?= h($locationTraits->location_id) ?></td>
                <td><?= h($locationTraits->trait_name) ?></td>
                <td><?= h($locationTraits->trait_value) ?></td>
                <td><?= h($locationTraits->note) ?></td>
                <td><?= h($locationTraits->is_private) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'LocationTraits', 'action' => 'view', $locationTraits->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'LocationTraits', 'action' => 'edit', $locationTraits->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'LocationTraits', 'action' => 'delete', $locationTraits->id], ['confirm' => __('Are you sure you want to delete # {0}?', $locationTraits->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
