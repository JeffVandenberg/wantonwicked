<?php
include 'cgi-bin/start_of_page.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'common.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

//
// Start session management
//
$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;
//
// End session management
//

// check page actions
$page_title = "";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$page_template = "main_ww4.tpl";
$contentHeader = "";

// build links
<?php /* @var View $this */ ?>
<?php /* @var Character[] $characters */ ?>
<?php $this->set('title_for_layout', 'Cast of Characters'); ?>
<?php $this->Paginator->options(array(
    'update' => '#page-content',
    'evalScripts' => true
)); ?>
    <div class="characters index" id="page-content">
        <h2><?php echo __('Characters'); ?></h2>
        <table>
            <tr>
                <th><?php echo $this->Paginator->sort('character_name', 'Name'); ?></th>
                <th><?php echo $this->Paginator->sort('Template.template_name', 'Template'); ?></th>
                <th><?php echo $this->Paginator->sort('CreatedBy.username', 'Player'); ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
            <?php foreach ($characters as $character): ?>
                <tr>
                    <td><?php echo h($character['Character']['character_name']); ?>&nbsp;</td>
                    <td>
                        <?php echo h($character['Template']['template_name']); ?>
                    </td>
                    <td>
                        <?php echo h($character['CreatedBy']['username']); ?>
                    </td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'), array('action' => 'publicView', $character['Character']['id'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="paging">
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
            ));
            ?>
            <p>
                <?php
                echo $this->Paginator->prev('< ' . __('Previous'), array(), null, array('class' => 'prev disabled'));
                echo $this->Paginator->numbers(array('separator' => ''));
                echo $this->Paginator->next(__('Next') . ' >', array(), null, array('class' => 'next disabled'));
                ?>
            </p>
        </div>
    </div>
<?php $this->start('context-navigation'); ?>
    <div class="context-group">
        <h3><?php echo __('Actions'); ?></h3>
        <ul>
            <li><?php echo $this->Html->link(__('New Character'), array('action' => 'add')); ?></li>
        </ul>
    </div>
<?php $this->end(); ?>