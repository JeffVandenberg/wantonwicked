<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\Plot;
use App\Model\Entity\PlotStatus;
use App\Model\Entity\PlotVisibility;
use Cake\Event\Event;
use function compact;

/**
 * Plots Controller
 *
 * @property \App\Model\Table\PlotsTable $Plots
 * @property PermissionsComponent Permissions
 *
 * @method Plot[] paginate($object = null, array $settings = [])
 */
class PlotsController extends AppController
{

    /**
     * @param Event $event
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'index',
            'view',
            'tagged'
        ]);

        // set common permissions
        $isPlotManager = $this->Permissions->isPlotManager($this->Auth->user('user_id'));
        $isPlotViewer = $this->Permissions->isPlotViewer($this->Auth->user('user_id'));
        $this->set(compact('isPlotManager', 'isPlotViewer'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $isPlotManager = $this->Permissions->isPlotManager($this->Auth->user('user_id'));
        $isPlotViewer = $this->Permissions->isPlotViewer($this->Auth->user('user_id'));
        $viewAll = $this->request->getQuery('view_all', false);

        if ($isPlotManager || $isPlotViewer) {
            $where = [
                'PlotStatuses.id IN' => [
                    PlotStatus::Pending,
                    PlotStatus::InProgress
                ]
            ];
        } else {
            $where = [
                'PlotVisibilities.id IN' => [
                    PlotVisibility::Promoted,
                    PlotVisibility::Public,
                ],
                'PlotStatuses.id IN' => [
                    PlotStatus::InProgress
                ]
            ];
        }
        if ($viewAll) {
            $where['PlotStatuses.id IN'][] = PlotStatus::Completed;
            $where['PlotStatuses.id IN'][] = PlotStatus::Cancelled;
        }
        $this->paginate = [
            'contain' => [
                'PlotStatuses',
                'PlotVisibilities',
                'CreatedBy' => [
                    'fields' => [
                        'username',
                        'user_id'
                    ]
                ],
                'UpdatedBy' => [
                    'fields' => [
                        'username',
                        'user_id'
                    ]
                ],
                'RunBy' => [
                    'fields' => [
                        'username',
                        'user_id'
                    ]
                ]
            ],
            'where' => $where,
        ];

        $plots = $this->paginate($this->Plots, [
            'sortWhitelist' => [
                'name',
                'Plots.name',
                'RunBy.username',
                'PlotStatuses.name',
                'PlotVisibilities.name',
                'CreatedBy.username',
                'UpdatedBy.username',
                'created',
                'updated'
            ],
            'order' => [
                'Plots.name' => 'asc'
            ],
            'conditions' => $where
        ]);

        $this->set(compact('plots', 'viewAll'));
        $this->set('_serialize', ['plots']);
    }

    public function tagged($tag)
    {
        $isPlotManager = $this->Permissions->isPlotManager($this->Auth->user('user_id'));
        $isPlotViewer = $this->Permissions->isPlotViewer($this->Auth->user('user_id'));

        if ($isPlotManager || $isPlotViewer) {
            $where = [
                'PlotStatuses.id IN' => [
                    PlotStatus::Pending,
                    PlotStatus::InProgress
                ]
            ];
        } else {
            $where = [
                'PlotVisibilities.id IN' => [
                    PlotVisibility::Promoted,
                    PlotVisibility::Public,
                ],
                'PlotStatuses.id IN' => [
                    PlotStatus::InProgress
                ]
            ];
        }

        $plots = $this->paginate(
            $this->Plots
                ->find('tagged', ['tag' => $tag])
                ->contain([
                    'PlotStatuses',
                    'PlotVisibilities',
                    'RunBy' => ['fields' => ['username']],
                    'CreatedBy' => ['fields' => ['username']],
                    'UpdatedBy' => ['fields' => ['username']],
                ])
                ->where($where),
            [
                'limit' => 25,
                'order' => [
                    'Plots.name' => ''
                ]
            ]);

        $this->set(compact('plots', 'tag', 'isPlotManager', 'isPlotViewer'));
    }

    /**
     * View method
     *
     * @param string|null $id Plot id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $isPlotManager = $this->Permissions->isPlotManager($this->Auth->user('user_id'));
        $plot = $this->Plots->getByIdOrSlug($id, [
            'PlotStatuses',
            'Tags',
            'PlotVisibilities',
            'CreatedBy' => [
                'fields' => ['username']
            ],
            'UpdatedBy' => [
                'fields' => ['username']
            ],
            'RunBy' => [
                'fields' => ['username']
            ],
            'PlotCharacters' => [
                'Characters' => [
                    'fields' => ['character_name', 'slug']
                ]
            ],
            'PlotScenes' => [
                'Scenes' => [
                    'fields' => ['name', 'slug']
                ]
            ]
        ]);
        if (!$this->mayViewPlot($plot)) {
            $this->Flash->set('You do not have permission to view that plot.');
            $this->redirect(['action' => 'index']);
        }

        $this->set(compact('plot', 'isPlotManager'));
        $this->set('_serialize', ['plot']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $plot = $this->Plots->newEntity();
        if ($this->request->is('post')) {
            if ($this->request->getData('action') == 'cancel') {
                $this->redirect(['action' => 'index']);
                return null;
            }

            $plot = $this->Plots->patchEntity($plot, $this->request->getData());
            $plot->created_by_id = $plot->updated_by_id = $this->Auth->user('user_id');

            if ($this->Plots->save($plot)) {
                $this->Flash->success(__('The plot has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The plot could not be saved. Please, try again.'));
        }
        $plotStatuses = $this->Plots->PlotStatuses->find('list', ['limit' => 200]);
        $plotVisibilities = $this->Plots->PlotVisibilities->find('list', ['limit' => 200]);
        $this->set(compact('plot', 'plotStatuses', 'plotVisibilities'));
        $this->set('_serialize', ['plot']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Plot id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $plot = $this->Plots->getByIdOrSlug($id, [
            'RunBy' => [
                'fields' => [
                    'username', 'user_id'
                ]
            ],
            'Tags'
        ]);
        if (!$this->mayManagePlot($plot)) {
            $this->Flash->set('You may not edit that plot');
            $this->redirect(['action' => 'index']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->request->getData('action') == 'cancel') {
                $this->redirect(['action' => 'view', $id]);
                return;
            }

            $plot = $this->Plots->patchEntity($plot, $this->request->getData());
            $plot->updated_by_id = $this->Auth->user('user_id');

            if ($this->Plots->save($plot)) {
                $this->Flash->success(__('The plot has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The plot could not be saved. Please, try again.'));
        }
        $plotStatuses = $this->Plots->PlotStatuses->find('list', ['limit' => 200]);
        $plotVisibilities = $this->Plots->PlotVisibilities->find('list', ['limit' => 200]);
        $this->set(compact('plot', 'plotStatuses', 'plotVisibilities'));
        $this->set('_serialize', ['plot']);
    }

    /**
     * @param $user
     * @return bool
     */
    public function isAuthorized($user)
    {
        switch ($this->request->getParam('action')) {
            default:
                return true;
        }
    }

    /**
     * @param Plot $plot
     * @return bool
     */
    private function mayViewPlot(Plot $plot)
    {
        $isPlotManager = $this->Permissions->isPlotManager($this->Auth->user('user_id'));
        $isPlotViewer = $this->Permissions->isPlotViewer($this->Auth->user('user_id'));
        if (in_array($plot->plot_status_id, [PlotStatus::InProgress, PlotStatus::Completed])) {
            return true;
        } else if ($plot->plot_status_id == PlotStatus::Pending) {
            return ($isPlotManager || $isPlotViewer);
        } else if ($plot->plot_status_id == PlotStatus::Cancelled) {
            return ($isPlotManager);
        }
        return false;
    }

    private function mayManagePlot(Plot $plot)
    {
        $isPlotManager = $this->Permissions->isPlotManager($this->Auth->user('user_id'));
        return ($isPlotManager || $plot->run_by_id == $plot->run_by_id);
    }

    public function addCharacter($id)
    {
        $plot = $this->Plots->getByIdOrSlug($id);
        if (!$this->mayManagePlot($plot)) {
            $this->Flash->set('You may not manager that plot');
            $this->redirect(['action' => 'view', $id]);
        }

        $plotCharacter = $this->Plots->PlotCharacters->newEntity();
        if ($this->request->is(['post', 'patch', 'put'])) {
            if ($this->request->getData('action') == 'cancel') {
                $this->redirect(['action' => 'view', $id]);
                return;
            }

            $plotCharacter = $this->Plots->PlotCharacters->patchEntity($plotCharacter, $this->request->getData());
            if ($this->Plots->PlotCharacters->save($plotCharacter)) {
                $this->Flash->set('Added character to plot.');
                $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->set('Error saving character to plot.');
            }
        }
        $this->set(compact('plot', 'plotCharacter'));
    }

    public function addScene($id)
    {
        $plot = $this->Plots->getByIdOrSlug($id);
        if (!$this->mayManagePlot($plot)) {
            $this->Flash->set('You may not manager that plot');
            $this->redirect(['action' => 'view', $id]);
        }

        $plotScene = $this->Plots->PlotScenes->newEntity();
        if ($this->request->is(['post', 'patch', 'put'])) {
            if ($this->request->getData('action') == 'cancel') {
                $this->redirect(['action' => 'view', $id]);
                return;
            }

            $plotScene = $this->Plots->PlotCharacters->patchEntity($plotScene, $this->request->getData());
            if ($this->Plots->PlotScenes->save($plotScene)) {
                $this->Flash->set('Added scene to plot.');
                $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->set('Error saving scene to plot.');
            }
        }
        $this->set(compact('plot', 'plotScene'));
    }
}
