<?php
/* @var array $characters; */
/* @var string $type; */
/* @var View $this */
?>
<?php $this->set('title_for_layout', ucfirst($type) . ' Characters'); ?>

<table>
    <thead>
    <tr>
        <th>
            Name
        </th>
        <th>
            Splat 1
        </th>
        <th>
            Splat 2
        </th>
        <th>
            Sanctioned
        </th>
        <th>

        </th>
    </tr>
    </thead>
    <?php foreach($characters as $character): ?>
        <tr>
            <td>
                <?php echo $character['Character']['character_name']; ?>
            </td>
            <td>
                <?php echo $character['Character']['splat1']; ?>
            </td>
            <td>
                <?php echo $character['Character']['splat2']; ?>
            </td>
            <td>
                <?php echo $character['Character']['is_sanctioned']; ?>
            </td>
            <td>
                <?php echo $this->Html->link('Wiki', '/wiki/?n=Players.'.str_replace(' ', '', $character['Character']['character_name'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>