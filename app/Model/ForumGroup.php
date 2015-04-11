<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 4/7/15
 * Time: 7:58 AM
 */

class ForumGroup extends AppModel
{
    public $useTable = 'phpbb_groups';
    public $primaryKey = 'group_id';
    public $displayField = 'group_name';

    public function listGroups()
    {
        return $this->find('list', array(
            'orderby' => array(
                'ForumGroup.group_name'
            )
        ));
    }
}