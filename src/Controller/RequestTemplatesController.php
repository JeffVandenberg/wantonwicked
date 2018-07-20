<?php

namespace App\Controller;

use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\RequestTemplate;
use App\Model\Table\RequestTemplatesTable;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

/**
 * RequestTemplates Controller
 *
 * @property RequestTemplatesTable $RequestTemplates
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 */
class RequestTemplatesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(array(
            'getList'
        ));
    }

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        'Paginator',
        'Menu'
    );

    public function getList()
    {
        $templates = $this->RequestTemplates->find('all', [
            'order' => [
                'RequestTemplates.name' => 'asc'
            ]
        ])->toArray();
        /* @var RequestTemplate[] $templates */
        $list = array();
        foreach ($templates as $template) {
            $list[] = array(
                'title' => $template->name,
                'description' => $template->description,
                'content' => $template->content,
            );
        }

        $this->set(compact('list'));
        $this->set('_serialize', 'list');
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        $this->set('requestTemplate', $this->RequestTemplates->get($id));
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
                'Edit' => array(
                    'link' => array(
                        'action' => 'edit',
                        $id
                    )
                ),
                'New Template' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if($this->getRequest()->is(['post', 'patch', 'put'])) {
            if ($this->getRequest()->getData('action') == 'cancel') {
                $this->redirect(['action' => 'index']);
                return;
            }

            $requestTemplate = $this->RequestTemplates->patchEntity($this->RequestTemplates->newEntity(), $this->getRequest()->getData());
            if($this->RequestTemplates->save($requestTemplate)) {
                $this->Flash->set($requestTemplate->name . ' has been saved.');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->set('Error saving Request Template');
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        $requestTemplate = $this->RequestTemplates->get($id, [
            'contain' => []
        ]);

        if($this->getRequest()->is(['post', 'patch', 'put'])) {
            if ($this->getRequest()->getData('action') == 'cancel') {
                $this->redirect(['action' => 'index']);
                return;
            }

            $requestTemplate = $this->RequestTemplates->patchEntity($requestTemplate, $this->getRequest()->getData());
            if($this->RequestTemplates->save($requestTemplate)) {
                $this->Flash->set($requestTemplate->name . ' has been updated');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->set('Error updating Request Template');
            }
        }
        $this->set('requestTemplate', $requestTemplate);
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->RequestTemplates->id = $id;
        if (!$this->RequestTemplates->exists()) {
            throw new NotFoundException(__('Invalid request template'));
        }
        $this->getRequest()->onlyAllow('post', 'delete');
        if ($this->requestTemplate->delete()) {
            $this->Session->setFlash(__('The request template has been deleted.'));
        } else {
            $this->Session->setFlash(__('The request template could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function index()
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'New Template' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                )
            )
        );
        $this->set('submenu', $storytellerMenu);
        $this->set('requestTemplates', $this->paginate($this->RequestTemplates, [
            'order' => [
                'RequestTemplates.name'
            ]
        ]));
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isAdmin();
    }
}
