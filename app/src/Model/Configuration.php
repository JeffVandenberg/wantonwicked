<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/20/14
 * Time: 8:07 PM
 */



use App\Model\AppModel;
class Configuration extends AppModel {
    public $primaryKey = 'key';
    public $displayField = 'description';

} 