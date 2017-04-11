<?php
use App\Model\Entity\Configuration;
use App\View\AppView;

/* @var AppView $this */
/* @var Configuration[] $configs */

$this->set('title_for_layout', 'Game Configuration');

echo $this->Html->link('Edit Configuration', ['action' => 'edit'], ['class' => 'button']); ?>
<table>
    <thead>
    <tr>
        <th>
            Key
        </th>
        <th>
            Setting
        </th>
        <th>
            Value
        </th>
    </tr>
    </thead>
    <?php foreach($configs as $config): ?>
        <tr>
            <td>
                <?php echo $config->key; ?>
            </td>
            <td>
                <?php echo $config->description; ?>
            </td>
            <td>
                <?php echo $config->value; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
