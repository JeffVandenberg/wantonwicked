<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PlotScenes Model
 *
 * @property \App\Model\Table\PlotsTable|\Cake\ORM\Association\BelongsTo $Plots
 * @property \App\Model\Table\ScenesTable|\Cake\ORM\Association\BelongsTo $Scenes
 *
 * @method \App\Model\Entity\PlotScene get($primaryKey, $options = [])
 * @method \App\Model\Entity\PlotScene newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PlotScene[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PlotScene|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PlotScene patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PlotScene[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PlotScene findOrCreate($search, callable $callback = null, $options = [])
 */
class PlotScenesTable extends Table
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

        $this->setTable('plot_scenes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Plots', [
            'foreignKey' => 'plot_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Scenes', [
            'foreignKey' => 'scene_id',
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
        $rules->add($rules->existsIn(['scene_id'], 'Scenes'));

        return $rules;
    }
}
