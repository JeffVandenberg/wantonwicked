<?php
namespace App\Model\Table;

use App\Model\Entity\Location;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Locations Model
 *
 * @property \App\Model\Table\DistrictsTable|\Cake\ORM\Association\BelongsTo $Districts
 * @property \App\Model\Table\CreatedBiesTable|\Cake\ORM\Association\BelongsTo $CreatedBy
 * @property \App\Model\Table\UpdatedBiesTable|\Cake\ORM\Association\BelongsTo $UpdatedBy
 * @property \App\Model\Table\CharactersTable|\Cake\ORM\Association\BelongsTo $Characters
 * @property \App\Model\Table\LocationTypesTable|\Cake\ORM\Association\BelongsTo $LocationTypes
 * @property \App\Model\Table\LocationTraitsTable|\Cake\ORM\Association\HasMany $LocationTraits
 *
 * @method \App\Model\Entity\Location get($primaryKey, $options = [])
 * @method \App\Model\Entity\Location newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Location[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Location patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Location[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Location findOrCreate($search, callable $callback = null, $options = [])
 */
class LocationsTable extends Table
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

        $this->setTable('locations');
        $this->setDisplayField('location_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_on' => 'new',
                    'updated_on' => 'always',
                ]
            ]
        ]);

        $this->belongsTo('Districts', [
            'foreignKey' => 'district_id'
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
        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id'
        ]);
        $this->belongsTo('LocationTypes', [
            'foreignKey' => 'location_type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Characters', [
            'foreignKey' => 'location_id'
        ]);
        $this->hasMany('LocationTraits', [
            'foreignKey' => 'location_id'
        ]);
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
            ->requirePresence('location_name', 'create')
            ->notEmpty('location_name');

        $validator
            ->allowEmpty('location_description');

        $validator
            ->allowEmpty('location_image');

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
            ->boolean('is_private')
            ->requirePresence('is_private', 'create')
            ->notEmpty('is_private');

        $validator
            ->allowEmpty('owning_character_name');

        $validator
            ->requirePresence('location_rules', 'create')
            ->notEmpty('location_rules');

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
        $rules->add($rules->existsIn(['district_id'], 'Districts'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));
        $rules->add($rules->existsIn(['updated_by_id'], 'Updatedby'));
        $rules->add($rules->existsIn(['character_id'], 'Characters'));
        $rules->add($rules->existsIn(['location_type_id'], 'LocationTypes'));

        return $rules;
    }

    public function save(EntityInterface $entity, $options = [])
    {
        $entity->slug = Text::slug($entity->location_name);
        return parent::save($entity, $options);
    }
}
