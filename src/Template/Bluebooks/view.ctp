<?php

use App\Model\Entity\Bluebook;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Bluebook $bluebook
 * @var string $backLink
 */

$this->set('title_for_layout', 'Bluebook: ' . $bluebook->title);
?>

<div class="row">
    <div class="small-12 columns button-group">
        <?php
        echo $this->Html->link('Back', ['action' => 'character', $bluebook->character_id], ['class' => 'button']);
        echo $this->Html->link('Edit', ['action' => 'edit', $bluebook->id], ['class' => 'button']);
        ?>
    </div>
    <div class="small-12 columns tinymce-content">
        <?= $bluebook->body; ?>
    </div>
</div>
