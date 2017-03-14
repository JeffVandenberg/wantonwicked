<?php
namespace app\Controller;

use App\Controller\AppController;

/**
 * RequestTypes Controller
 *
 * @property RequestType $RequestType
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 */
class RequestTypesController extends AppController
{
    /**
     * Components
     *
     * @var array
     */
    public $components = array(
    );

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->RequestType->recursive = 0;
        $this->set('requestTypes', $this->Paginator->paginate());
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
        if (!$this->RequestType->exists($id)) {
            throw new NotFoundException(__('Invalid request type'));
        }
        $options = array('conditions' => array('RequestType.' . $this->RequestType->primaryKey => $id));
        $this->set('requestType', $this->RequestType->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->RequestType->create();
            if ($this->RequestType->save($this->request->data)) {
                $this->Session->setFlash(__('The request type has been saved.'));

                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request type could not be saved. Please, try again.'));
            }
        }
        $groups = $this->RequestType->Group->find('list');
        $this->set(compact('groups'));
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
        if (!$this->RequestType->exists($id)) {
            throw new NotFoundException(__('Invalid request type'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->RequestType->save($this->request->data)) {
                $this->Session->setFlash(__('The request type has been saved.'));

                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request type could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('RequestType.' . $this->RequestType->primaryKey => $id));
            $this->request->data = $this->RequestType->find('first', $options);
        }
        $groups = $this->RequestType->Group->find('list');
        $this->set(compact('groups'));
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
        $this->RequestType->id = $id;
        if (!$this->RequestType->exists()) {
            throw new NotFoundException(__('Invalid request type'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->RequestType->delete()) {
            $this->Session->setFlash(__('The request type has been deleted.'));
        } else {
            $this->Session->setFlash(__('The request type could not be deleted. Please, try again.'));
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
        $this->RequestType->recursive = 0;
        $this->set('requestTypes', $this->Paginator->paginate());
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'New Request Type' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                )
            )
        );
        $this->set('submenu', $storytellerMenu);
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
        if (!$this->RequestType->exists($id)) {
            throw new NotFoundException(__('Invalid request type'));
        }
        $options = array(
            'conditions' => array(
                'RequestType.' . $this->RequestType->primaryKey => $id
            )
        );
        $this->set('requestType', $this->RequestType->find('first', $options));
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
                'New Request Type' => array(
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
            $this->RequestType->create();
            if ($this->RequestType->save($this->request->data)) {
                $this->Session->setFlash(__('The request type has been saved.'));

                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request type could not be saved. Please, try again.'));
            }
        }
        $groups = $this->RequestType->Group->find('list');
        $this->set(compact('groups'));
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
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
        if (!$this->RequestType->exists($id)) {
            throw new NotFoundException(__('Invalid request type'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->RequestType->save($this->request->data)) {
                $this->Session->setFlash(__('The request type has been saved.'));

                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The request type could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('RequestType.' . $this->RequestType->primaryKey => $id));
            $this->request->data = $this->RequestType->find('first', $options);
        }
        $groups = $this->RequestType->Group->find('list');
        $this->set(compact('groups'));
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
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
        $this->RequestType->id = $id;
        if (!$this->RequestType->exists()) {
            throw new NotFoundException(__('Invalid request type'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->RequestType->delete()) {
            $this->Session->setFlash(__('The request type has been deleted.'));
        } else {
            $this->Session->setFlash(__('The request type could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->IsAdmin();
    }
}
