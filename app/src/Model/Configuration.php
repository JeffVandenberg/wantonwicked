<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/20/14
 * Time: 8:07 PM
 */

App::uses('AppModel', 'Model');
class Configuration extends AppModel {
    public $primaryKey = 'key';
    public $displayField = 'description';

} 