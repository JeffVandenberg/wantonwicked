<?php
/* @var array $characters */
?>

<table>
    <?php foreach($characters as $character): ?>
        <tr>
            <td>
                <?php echo $character['Character']['character_name']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>