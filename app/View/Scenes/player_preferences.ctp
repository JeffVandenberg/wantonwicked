<?php /* @var View $this */ ?>
<?php
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
        $scene['Scene']['slug']
    )
);

$this->set('menu', $menu);
$this->set('title_for_layout', 'Player Preferences in Scene');
?>
<h3>
    Scene: <?Php echo $scene['Scene']['name']; ?>
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
                <?php echo $row['PP']['name']; ?>
            </td>
            <td>
                <?php echo $row[0]['percentage']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
