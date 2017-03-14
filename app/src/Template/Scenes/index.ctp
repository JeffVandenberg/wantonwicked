<?php /* @var View $this */ ?>
<?php
if ($mayAdd) {
    $menu['Actions'] = [
        'link' => '#',
        'submenu' => [
            'New Scene' => [
                'link' => [
                    'action' => 'add'
                ]
            ]
        ]
    ];
}

if (AuthComponent::user('user_id') != 1) {
    $menu['Actions']['submenu']['My Scenes'] = [
        'link' => [
            'action' => 'my_scenes'
        ]
    ];
}

$this->set('title_for_layout', 'Scene Calendar');
$this->set('menu', $menu);
$nextMonthTimestamp = strtotime('+1 Month', strtotime("$year-$month-01"));
$prevMonthTimestamp = strtotime('-1 Month', strtotime("$year-$month-01"));
?>

<div class="callout-navigation">
    <?php if ($mayAdd): ?>
        <?php echo $this->Html->link('New Scene', array('action' => 'add'), array('class' => 'button add')); ?>
    <?php endif; ?>
</div>
<div id="page-content" class="scenes index">
    <div style="text-align: center;">
        <?php echo $this->Html->link('<<', [
            date('Y', $prevMonthTimestamp),
            date('m', $prevMonthTimestamp)
        ]); ?>
        <strong><?php echo date('F Y', strtotime("$year-$month-01")); ?></strong>
        <?php echo $this->Html->link('>>', [
            date('Y', $nextMonthTimestamp),
            date('m', $nextMonthTimestamp)
        ]); ?>
    </div>
    <?php
    $sceneList = [];
    foreach ($scenes as $scene) {
        $dayOfMonth = date('d', strtotime($scene['Scene']['run_on_date']));
        $sceneList[$dayOfMonth][] = [
            'link' => [
                'controller' => 'scenes',
                'action' => 'view',
                $scene['Scene']['slug']
            ],
            'linkTitle' => $scene['Scene']['summary'],
            'title' => $scene['Scene']['name'] . ' - ' . date('H:i', strtotime($scene['Scene']['run_on_date'])),
            'class' => 'mortal'
        ];
    }
    echo $this->Calendar->drawCalendar($month, $year, $sceneList);
    ?>
</div>
<script>
    $(function () {
    });
</script>
