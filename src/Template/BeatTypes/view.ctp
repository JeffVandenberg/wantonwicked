<?php
use App\Model\Entity\BeatType;
use App\View\AppView;

/* @var AppView $this */
/* @var BeatType $beatType */

$this->set('title_for_layout', $beatType->name);
$menu['Actions']['submenu']['Edit'] = [
    'link' => [
        'controller' => 'beatTypes',
        'action' => 'edit',
        $beatType->id
    ]
];

$this->set('menu', $menu);
?>
<?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
<div class="row">
    <div class="small-2 medium-1 column">
        <label>Beat Type</label>
    </div>
    <div class="small-10 medium-3 column">
        <?php echo h($beatType->name); ?>
    </div>
    <div class="small-2 medium-3 column">
        <label>Number of Beats</label>
    </div>
    <div class="small-4 medium-1 column">
        <?php echo $beatType->number_of_beats; ?>
    </div>
    <div class="small-2 medium-3 column">
        <label>May Rollover</label>
    </div>
    <div class="small-4 medium-1 column">
        <?php echo $beatType->may_rollover ? 'Yes' : 'No'; ?>
    </div>
    <div class="small-2 medium-3 column">
        <label>Staff Only</label>
    </div>
    <div class="small-4 medium-1 column">
        <?php echo $beatType->admin_only ? 'Yes' : 'No'; ?>
    </div>
</div>
