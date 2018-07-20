<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use App\View\Helper\CharacterHelper;
use App\View\Helper\MainMenuHelper;
use App\View\Helper\UserPanelHelper;
use Cake\Core\Configure;
use Cake\View\View;
use Shrink\View\Helper\ShrinkHelper;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @property ShrinkHelper Shrink
 * @property CharacterHelper Character
 * @property UserPanelHelper UserPanel
 * @property MainMenuHelper MainMenu
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
    }

    public function addScript($scriptName)
    {
        if(Configure::read('debug')) {
            echo $this->Html->script($scriptName, ['block' => true]);
        } else {
            $this->Shrink->js($scriptName . '.js');
        }
    }
}
