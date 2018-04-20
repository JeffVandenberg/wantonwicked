<?php
namespace App\Model\Table;

use App\Model\Entity\Plot;
use App\Model\Entity\PlotStatus;
use App\Model\Entity\PlotVisibility;
use function array_merge;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use function is_null;
use function is_numeric;

/**
 * Plots Model
 *
 * @property \App\Model\Table\PlotStatusesTable|\Cake\ORM\Association\BelongsTo $PlotStatuses
 * @property \App\Model\Table\PlotStatusesTable|\Cake\ORM\Association\BelongsTo $PlotVisibilities
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $RunBy
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $CreatedBy
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $UpdatedBy
 * @property \App\Model\Table\PlotCharactersTable|\Cake\ORM\Association\HasMany $PlotCharacters
 * @property \App\Model\Table\PlotScenesTable|\Cake\ORM\Association\HasMany $PlotScenes
 *
 * @method Plot get($primaryKey, $options = [])
 * @method Plot newEntity($data = null, array $options = [])
 * @method Plot[] newEntities(array $data, array $options = [])
 * @method Plot|bool save(EntityInterface $entity, $options = [])
 * @method Plot patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Plot[] patchEntities($entities, array $data, array $options = [])
 * @method Plot findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PlotsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('plots');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always',
                ]
            ]
        ]);

        $this->addBehavior('Tags.Tag', []);

        $this->belongsTo('PlotStatuses', [
            'foreignKey' => 'plot_status_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PlotVisibilities', [
            'foreignKey' => 'plot_visibility_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RunBy', [
            'foreignKey' => 'run_by_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);
        $this->belongsTo('CreatedBy', [
            'foreignKey' => 'created_by_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);
        $this->belongsTo('UpdatedBy', [
            'foreignKey' => 'updated_by_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);
        $this->hasMany('PlotCharacters', [
            'foreignKey' => 'plot_id'
        ]);
        $this->hasMany('PlotScenes', [
            'foreignKey' => 'plot_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('slug', 'create')
            ->notEmpty('slug');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['plot_status_id'], 'PlotStatuses'));
        $rules->add($rules->existsIn(['plot_visibility_id'], 'PlotVisibilities'));
        $rules->add($rules->existsIn(['run_by_id'], 'RunBy'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));
        $rules->add($rules->existsIn(['updated_by_id'], 'UpdatedBy'));

        return $rules;
    }

    public function getByIdOrSlug($identifier, $contain = null)
    {
        if(is_null($contain)) {
            $contain = ['PlotStatuses', 'PlotVisibilities', 'RunBy',
                'CreatedBy', 'UpdatedBy', 'PlotCharacters', 'PlotScenes'];
        }
        if(is_numeric($identifier)) {
            $where = [
                'Plots.id' => $identifier
            ];
        } else {
            $where = [
                'Plots.slug' => $identifier
            ];
        }
        return $this
            ->find()
            ->where($where)
            ->contain($contain)
            ->firstOrFail();
    }

    public function listForHome()
    {
        return $this
            ->find()
            ->select([
                'Plots.id',
                'Plots.name',
                'Plots.slug',
                'RunBy.username'
            ])
            ->contain([
                'PlotVisibilities',
                'PlotStatuses',
                'RunBy'
            ])
            ->where([
                'PlotVisibilities.id IN' => [
                    PlotVisibility::Promoted,
                    PlotVisibility::Public,
                ],
                'PlotStatuses.id IN' => [
                    PlotStatus::InProgress
                ]
            ])
            ->order([
                'Plots.name'
            ])
            ->cache('plots_frontpage');
    }
}
