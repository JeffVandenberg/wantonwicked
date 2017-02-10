<?php
/* @var array $beatType */
/* @var View $this */
$this->set('title_for_layout', $beatType['BeatType']['name']);
$menu['Actions']['submenu']['Edit'] = [
    'link' => [
        'controller' => 'beatTypes',
        'action' => 'edit',
        $beatType['BeatType']['id']
    ]
];

$this->set('menu', $menu);
?>
<?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
<div class="row">
    <div class="small-2 medium-1 column">
        <label>Name</label>
    </div>
    <div class="small-10 medium-4 column">
        <?php echo h($beatType['BeatType']['name']); ?>
    </div>
    <div class="small-2 medium-3 column">
        <label>Number of Beats</label>
    </div>
    <div class="small-4 medium-1 column">
        <?php echo $beatType['BeatType']['number_of_beats']; ?>
    </div>
    <div class="small-2 medium-2 column">
        <label>Admin Only</label>
    </div>
    <div class="small-4 medium-1 column">
        <?php echo $beatType['BeatType']['admin_only'] ? 'Yes' : 'No'; ?>
    </div>
</div>
