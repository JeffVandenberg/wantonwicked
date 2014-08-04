<?php
/* @var array $characters ; */
/* @var string $type ; */
/* @var View $this */

$this->set('title_for_layout', ucfirst($type) . ' Characters');
$this->Paginator->options(array(
                              'update'      => '#page-content',
                              'evalScripts' => true,
                              'before'      => $this->Js->get('#busy-indicator')->effect(
                                                        'fadeIn',
                                                        array('buffer' => false)
                                  ),
                              'complete'    => $this->Js->get('#busy-indicator')->effect(
                                                        'fadeOut',
                                                        array('buffer' => false)
                                  ),
                          ));?>
<div id="page-content">
    <table>
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('character_name'); ?></th>
            <?php if (strtolower($type) == 'all'): ?>
                <th><?php echo $this->Paginator->sort('character_type'); ?></th>
            <?php endif; ?>
            <th><?php echo $this->Paginator->sort('splat1'); ?></th>
            <th><?php echo $this->Paginator->sort('splat2'); ?></th>
            <th><?php echo $this->Paginator->sort('is_npc'); ?></th>
        </tr>
        </thead>
        <?php foreach ($characters as $character): ?>
            <tr>
                <td>
                    <?php echo $this->Html->link(
                                          $character['Character']['character_name'],
                                          '/wiki/?n=Players.' . str_replace(' ', '',
                                                                            $character['Character']['character_name']
                                          )
                    ); ?>
                </td>
                <?php if (strtolower($type) == 'all'): ?>
                    <td>
                        <?php echo $this->Html->link(
                                              $character['Character']['character_type'],
                                              array(
                                                  $character['Character']['character_type']
                                              )
                        ); ?>
                    </td>
                <?php endif; ?>
                <td>
                    <?php echo $character['Character']['splat1']; ?>
                </td>
                <td>
                    <?php echo $character['Character']['splat2']; ?>
                </td>
                <td>
                    <?php echo ($character['Character']['is_npc'] == 'Y') ? 'Yes' : 'No'; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
    <?php echo $this->Js->writeBuffer(); ?>
</div>