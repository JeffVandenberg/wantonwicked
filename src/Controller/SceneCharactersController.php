<?php
namespace App\Controller;

use App\Model\Table\SceneCharactersTable;

/**
 * SceneCharacters Controller
 *
 * @property \App\Model\Table\SceneCharactersTable `$SceneCharacters
 * @property SceneCharactersTable SceneCharacters
 */
class SceneCharactersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Scenes', 'Characters']
        ];
        $sceneCharacters = $this->paginate($this->SceneCharacters);

        $this->set(compact('sceneCharacters'));
        $this->set('_serialize', ['sceneCharacters']);
    }

    /**
     * View method
     *
     * @param string|null $id Scene Character id.
     * @return \Cake\Network\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sceneCharacter = $this->SceneCharacters->get($id, [
            'contain' => ['Scenes', 'Characters']
        ]);

        $this->set('sceneCharacter', $sceneCharacter);
        $this->set('_serialize', ['sceneCharacter']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sceneCharacter = $this->SceneCharacters->newEntity();
        if ($this->getRequest()->is('post')) {
            $sceneCharacter = $this->SceneCharacters->patchEntity($sceneCharacter, $this->getRequest()->getData());
            if ($this->SceneCharacters->save($sceneCharacter)) {
                $this->Flash->success(__('The scene character has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scene character could not be saved. Please, try again.'));
        }
        $scenes = $this->SceneCharacters->Scenes->find('list', ['limit' => 200]);
        $characters = $this->SceneCharacters->Characters->find('list', ['limit' => 200]);
        $this->set(compact('sceneCharacter', 'scenes', 'characters'));
        $this->set('_serialize', ['sceneCharacter']);
        return null;
    }

    /**
     * Edit method
     *
     * @param string|null $id Scene Character id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sceneCharacter = $this->SceneCharacters->get($id, [
            'contain' => []
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $sceneCharacter = $this->SceneCharacters->patchEntity($sceneCharacter, $this->getRequest()->getData());
            if ($this->SceneCharacters->save($sceneCharacter)) {
                $this->Flash->success(__('The scene character has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scene character could not be saved. Please, try again.'));
        }
        $scenes = $this->SceneCharacters->Scenes->find('list', ['limit' => 200]);
        $characters = $this->SceneCharacters->Characters->find('list', ['limit' => 200]);
        $this->set(compact('sceneCharacter', 'scenes', 'characters'));
        $this->set('_serialize', ['sceneCharacter']);
        return null;
    }

    /**
     * Delete method
     *
     * @param string|null $id Scene Character id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $sceneCharacter = $this->SceneCharacters->get($id);
        if ($this->SceneCharacters->delete($sceneCharacter)) {
            $this->Flash->success(__('The scene character has been deleted.'));
        } else {
            $this->Flash->error(__('The scene character could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
