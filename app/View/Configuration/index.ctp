<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Game Configuration'); ?>
<?php echo $this->Html->link('Edit Configuration', array('action' => 'edit')); ?>
<table>
    <thead>
    <tr>
        <th>
            Setting
        </th>
        <th>
            Value
        </th>
        <th>
            Key
        </th>
    </tr>
    </thead>
    <?php foreach($configs as $config): ?>
        <tr>
            <td>
                <?php echo $config['Configuration']['description']; ?>
            </td>
            <td>
                <?php echo $config['Configuration']['value']; ?>
            </td>
            <td>
                <?php echo $config['Configuration']['key']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php $this->start('context-navigation'); ?>
    <div class="context-group">
        <h3><?php echo __('Actions'); ?></h3>
        <ul>
            <li><?php echo $this->Html->link(__('Edit Configuration'), array('action' => 'edit')); ?></li>
        </ul>
    </div>
<?php $this->end(); ?>