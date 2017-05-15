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

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('requestTemplates', $this->paginate($this->RequestTemplates));
    }

    public function getList()
    {
        $templates = $this->RequestTemplates->find()->toArray();
        /* @var RequestTemplate[] $templates */
        $list = array();
        foreach ($templates as $template) {
            $list[] = array(
                'title' => $template->name,
                'description' => $template->description,
                'content' => $template->content,
            );
        }
        echo json_encode($list);
        die();
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
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->RequestTemplate->create();
            if ($this->RequestTemplate->save($this->request->data)) {
                $this->Session->setFlash(__('The request template has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request template could not be saved. Please, try again.'));
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
        if (!$this->RequestTemplate->exists($id)) {
            throw new NotFoundException(__('Invalid request template'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->RequestTemplate->save($this->request->data)) {
                $this->Session->setFlash(__('The request template has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request template could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('RequestTemplate.' . $this->RequestTemplate->primaryKey => $id));
            $this->request->data = $this->RequestTemplate->find('first', $options);
        }
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
        $this->RequestTemplate->id = $id;
        if (!$this->RequestTemplate->exists()) {
            throw new NotFoundException(__('Invalid request template'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->RequestTemplate->delete()) {
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
    public function admin_index()
    {
        $this->RequestTemplate->recursive = 0;
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
        $this->set('requestTemplates', $this->Paginator->paginate());
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null)
    {
        if (!$this->RequestTemplate->exists($id)) {
            throw new NotFoundException(__('Invalid request template'));
        }
        $options = array('conditions' => array('RequestTemplate.' . $this->RequestTemplate->primaryKey => $id));
        $this->set('requestTemplate', $this->RequestTemplate->find('first', $options));
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
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->RequestTemplate->create();
            if ($this->RequestTemplate->save($this->request->data)) {
                $this->Session->setFlash(__('The request template has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request template could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        if (!$this->RequestTemplate->exists($id)) {
            throw new NotFoundException(__('Invalid request template'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->RequestTemplate->save($this->request->data)) {
                $this->Session->setFlash(__('The request template has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request template could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('RequestTemplate.' . $this->RequestTemplate->primaryKey => $id));
            $this->request->data = $this->RequestTemplate->find('first', $options);
        }
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null)
    {
        $this->RequestTemplate->id = $id;
        if (!$this->RequestTemplate->exists()) {
            throw new NotFoundException(__('Invalid request template'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->RequestTemplate->delete()) {
            $this->Session->setFlash(__('The request template has been deleted.'));
        } else {
            $this->Session->setFlash(__('The request template could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->IsAdmin();
    }
}
