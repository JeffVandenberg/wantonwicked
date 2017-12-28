<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PlotCharacters Model
 *
 * @property \App\Model\Table\PlotsTable|\Cake\ORM\Association\BelongsTo $Plots
 * @property \App\Model\Table\CharactersTable|\Cake\ORM\Association\BelongsTo $Characters
 *
 * @method \App\Model\Entity\PlotCharacter get($primaryKey, $options = [])
 * @method \App\Model\Entity\PlotCharacter newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PlotCharacter[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PlotCharacter|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PlotCharacter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PlotCharacter[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PlotCharacter findOrCreate($search, callable $callback = null, $options = [])
 */
class PlotCharactersTable extends Table
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

        $this->setTable('plot_characters');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Plots', [
            'foreignKey' => 'plot_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id',
            'joinType' => 'INNER'
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
            ->requirePresence('note', 'create')
            ->notEmpty('note');

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
        $rules->add($rules->existsIn(['plot_id'], 'Plots'));
        $rules->add($rules->existsIn(['character_id'], 'Characters'));

        return $rules;
    }
}
