<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BeatStatuses Model
 *
 * @property \App\Model\Table\CharacterBeatsTable|\Cake\ORM\Association\HasMany $CharacterBeats
 *
 * @method \App\Model\Entity\BeatStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\BeatStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BeatStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BeatStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BeatStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BeatStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BeatStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class BeatStatusesTable extends Table
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

        $this->setTable('beat_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('CharacterBeats', [
            'foreignKey' => 'beat_status_id'
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

        return $validator;
    }
}
