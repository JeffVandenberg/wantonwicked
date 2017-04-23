<?php
use App\Model\Entity\Scene;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $scene */
/* @var bool $mayAdd */
/* @var array $report */

if ($mayAdd) {
    $menu['Actions'] = array(
        'link'    => '#',
        'submenu' => array(
            'New Scene' => array(
                'link' => array(
                    'action' => 'add'
                )
            )
        )
    );
}

$menu['Actions']['submenu']['Return to Scene'] = array(
    'link' => array(
        'action' => 'view',
        $scene->slug
    )
);

$this->set('menu', $menu);
$this->set('title_for_layout', 'Player Preferences in Scene');
?>
<h3>
    Scene: <?Php echo $scene->name; ?>
</h3>
<table>
    <thead>
    <tr>
        <th>
            Name
        </th>
        <th>
            Percent Interested
        </th>
    </tr>
    </thead>
    <?php foreach($report as $row): ?>
        <tr>
            <td>
                <?php echo $row['name']; ?>
            </td>
            <td>
                <?php echo $row['percentage']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
