<?php
use App\Model\Entity\Configuration;

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/20/14
 * Time: 8:21 PM
 * @property Configuration Configuration
 * @property PermissionsComponent Permissions
 */

namespace App\Controller;


use App\Controller\Component\PermissionsComponent;
use App\Model\Table\ConfigurationsTable;
use Cake\Event\Event;

/**
 * @property ConfigurationsTable Configurations
 * @property PermissionsComponent Permissions
 */
class ConfigurationsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny();
    }

    public function index()
    {
        $this->set('configs', $this->Configurations->find()->toArray());
    }

    public function read($configName)
    {
        $configValue = $this->Configurations->get($configName);
        $this->set(compact('configValue'));
        $this->set('_serialize', array('configValue'));
    }

    public function edit()
    {
        if ($this->request->is('post')) {
            // try to save
            $data = $this->request->getData();
            $configurations = [];
            foreach($data as $row) {
                $item = $this->Configurations->get($row['key']);
                /* @var Configuration $item */
                $item->value = $row['value'];
                $configurations[] = $item;
            }
            $this->Configurations->getConnection()->getDriver()->enableAutoQuoting();
            if ($this->Configurations->saveMany($configurations)) {
                $this->Flash->set('Updated Configuration');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->set('Error Saving');
            }
        }
        $this->set('configs', $this->Configurations->find()->toArray());
    }

    public function isAuthorized($user = null)
    {
        switch ($this->request->getParam('action')) {
            case 'index':
            case 'edit':
                return $this->Permissions->IsAdmin();
                break;
            case 'read':
                return true;
                break;
        }
        return false;
    }
}
