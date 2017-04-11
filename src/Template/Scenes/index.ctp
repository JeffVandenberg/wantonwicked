<?php
use App\Model\Entity\Scene;
use Cake\View\View;

/* @var View $this */
/* @var bool $mayAdd */

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

if ($this->request->session()->read('Auth.User.user_id') > 1) {
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

<?php if ($mayAdd): ?>
    <div class="callout-navigation">
        <?php echo $this->Html->link('New Scene', array('action' => 'add'), array('class' => 'button add')); ?>
    </div>
<?php endif; ?>
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
        /* @var Scene $scene */
        $dayOfMonth = date('d', strtotime($scene->run_on_date));
        $sceneList[$dayOfMonth][] = [
            'link' => [
                'controller' => 'scenes',
                'action' => 'view',
                $scene->slug
            ],
            'linkTitle' => $scene->summary,
            'title' => $scene->name . ' - ' . date('H:i', strtotime($scene->run_on_date)),
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
