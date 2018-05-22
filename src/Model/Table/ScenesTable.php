<?php

namespace App\Model\Table;

use App\Model\Entity\Scene;
use App\Model\Entity\SceneStatus;
use Cake\Cache\Cache;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Scenes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $RunBy
 * @property \Cake\ORM\Association\BelongsTo $CreatedBy
 * @property \Cake\ORM\Association\BelongsTo $UpdatedBiy
 * @property \Cake\ORM\Association\BelongsTo $SceneStatus
 * @property \Cake\ORM\Association\HasMany $SceneCharacters
 * @property \Cake\ORM\Association\HasMany $PlotScenes
 * @property \Cake\ORM\Association\HasMany $SceneRequests
 *
 * @method Scene get($primaryKey, $options = [])
 * @method Scene newEntity($data = null, array $options = [])
 * @method Scene[] newEntities(array $data, array $options = [])
 * @method Scene patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Scene[] patchEntities($entities, array $data, array $options = [])
 * @method Scene findOrCreate($search, callable $callback = null, $options = [])
 */
class ScenesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('scenes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('RunBy', [
            'foreignKey' => 'run_by_id',
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
        $this->belongsTo('SceneStatuses', [
            'foreignKey' => 'scene_status_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SceneCharacters', [
            'foreignKey' => 'scene_id'
        ]);
        $this->hasMany('SceneRequests', [
            'foreignKey' => 'scene_id'
        ]);
        $this->hasMany('PlotScenes', [
            'foreignKey' => 'scene_id'
        ]);

        $this->addBehavior('Tags.Tag', []);
    }

    public function save(EntityInterface $entity, $options = [])
    {
        Cache::delete('scenes_home_' . date('Y-m-d'));
        return parent::save($entity, $options);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('summary');

        $validator
            ->dateTime('run_on_date')
            ->allowEmpty('run_on_date');

        $validator
            ->allowEmpty('description');

        $validator
            ->dateTime('created_on')
            ->allowEmpty('created_on');

        $validator
            ->dateTime('updated_on')
            ->allowEmpty('updated_on');

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
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['run_by_id'], 'RunBy'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));
        $rules->add($rules->existsIn(['updated_by_id'], 'UpdatedBy'));
        $rules->add($rules->existsIn(['scene_status_id'], 'SceneStatuses'));

        return $rules;
    }

    /**
     * @param int $sceneCount
     * @return array
     */
    public function listForHome($sceneCount = 5): array
    {
        return $this
            ->find()
            ->select([
                'Scenes.id',
                'Scenes.name',
                'Scenes.run_on_date',
                'Scenes.slug'
            ])
            ->where([
                'Scenes.scene_status_id' => 1,
                'Scenes.run_on_date >=' => date('Y-m-d H:i:s')
            ])
            ->order([
                'Scenes.run_on_date' => 'asc'
            ])
            ->limit($sceneCount)
            ->cache('scenes_home_' . date('Y-m-d'))
            ->toList();
    }

    /**
     * @param $requestId
     * @param $userId
     * @return array|Query
     */
    public function listUnattachedScenes($requestId, $userId)
    {
        $linkedCharacter = TableRegistry::getTableLocator()->get('Characters')->find('list')
            ->leftJoin(
                ['RequestCharacters' => 'request_characters'],
                'RequestCharacters.character_id = Characters.id'
            )
            ->where([
                'RequestCharacters.request_id' => $requestId,
                'Characters.user_id' => $userId
            ])
            ->toArray();

        if (count($linkedCharacter)) {
            return $this->find('all')
                ->leftJoin(
                    ['SceneCharacters' => 'scene_characters'],
                    'SceneCharacters.scene_id = Scenes.id'
                )
                ->where([
                    'SceneCharacters.character_id IN' => array_keys($linkedCharacter),
                    'Scenes.scene_status_id !=' => SceneStatus::Cancelled
                ])
                ->order([
                    'Scenes.name'
                ]);
        }

        return [];
    }


    public function listScenesWithTag($tag)
    {
        return $this
            ->find('tagged', [
                'tag' => $tag
            ]);
    }
}
