<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PlotVisibilities Model
 *
 * @property \App\Model\Table\PlotsTable|\Cake\ORM\Association\HasMany $Plots
 *
 * @method \App\Model\Entity\PlotVisibility get($primaryKey, $options = [])
 * @method \App\Model\Entity\PlotVisibility newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PlotVisibility[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PlotVisibility|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PlotVisibility patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PlotVisibility[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PlotVisibility findOrCreate($search, callable $callback = null, $options = [])
 */
class PlotVisibilitiesTable extends Table
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

        $this->setTable('plot_visibilities');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Plots', [
            'foreignKey' => 'plot_visibility_id'
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
