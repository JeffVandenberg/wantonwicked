<?php
/* @var array $characters ; */
/* @var string $type ; */
/* @var View $this */

$this->set('title_for_layout', ucfirst($type) . ' Characters');
$this->Paginator->options(array(
                              'update'      => '#page-content',
                              'evalScripts' => true,
                          ));?>
<div id="page-content">
    <div style="text-align: center;">
        <label for="character_id" style="display: inline;">Character Type</label>
        <?php echo $this->Form->select('character_type', $characterTypes, array('value' => ucfirst($type),
                                                                                'empty' => false,
                                                                                )
        ); ?>
    </div>
    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
    <table>
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('character_name'); ?></th>
            <th><?php echo $this->Paginator->sort('username', 'Player'); ?></th>
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
                                          '/wiki/?n=Players.' . preg_replace('/[^\w]+/', '',
                                                                            $character['Character']['character_name']
                                          )
                    ); ?>
                </td>
                <td>
                    <?php echo $character['Player']['username']; ?>
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
    <script>
        $(function() {
            $("#character_type").change(function() {
                document.location = '/characters/cast/' + $(this).val().toLowerCase();
            });
        });
    </script>
</div>