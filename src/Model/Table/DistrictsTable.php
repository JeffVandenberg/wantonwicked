<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Districts Model
 *
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\CreatedBiesTable|\Cake\ORM\Association\BelongsTo $CreatedBies
 * @property \App\Model\Table\UpdatedBiesTable|\Cake\ORM\Association\BelongsTo $UpdatedBies
 * @property \App\Model\Table\RealitiesTable|\Cake\ORM\Association\BelongsTo $Realities
 * @property \App\Model\Table\DistrictTypesTable|\Cake\ORM\Association\BelongsTo $DistrictTypes
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\HasMany $Locations
 *
 * @method \App\Model\Entity\District get($primaryKey, $options = [])
 * @method \App\Model\Entity\District newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\District[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\District|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\District patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\District[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\District findOrCreate($search, callable $callback = null, $options = [])
 */
class DistrictsTable extends Table
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

        $this->setTable('districts');
        $this->setDisplayField('district_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_on' => 'new',
                    'updated_on' => 'always',
                ]
            ]
        ]);

        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'LEFT'
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
        $this->belongsTo('Realities', [
            'foreignKey' => 'reality_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('DistrictTypes', [
            'foreignKey' => 'district_type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Locations', [
            'foreignKey' => 'district_id'
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
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('district_name')
            ->maxLength('district_name', 100)
            ->requirePresence('district_name', 'create')
            ->notEmpty('district_name');

        $validator
            ->scalar('district_description')
            ->allowEmpty('district_description');

        $validator
            ->scalar('district_image')
            ->maxLength('district_image', 100)
            ->allowEmpty('district_image');

        $validator
            ->boolean('is_active')
            ->requirePresence('is_active', 'create')
            ->notEmpty('is_active');

        $validator
            ->dateTime('created_on')
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

        $validator
            ->dateTime('updated_on')
            ->requirePresence('updated_on', 'create')
            ->notEmpty('updated_on');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 100)
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
        // to revisit
//        $rules->add($rules->existsIn(['city_id'], 'Cities'));
//        $rules->add($rules->existsIn(['reality_id'], 'Realities'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));
        $rules->add($rules->existsIn(['updated_by_id'], 'UpdatedBy'));
        $rules->add($rules->existsIn(['district_type_id'], 'DistrictTypes'));

        return $rules;
    }

    public function save(EntityInterface $entity, $options = [])
    {
        $entity->slug = Text::slug($entity->district_name);
        return parent::save($entity, $options);
    }
}
