<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PersonnelsAntennes Model
 *
 * @property \App\Model\Table\AntennesTable|\Cake\ORM\Association\BelongsTo $Antennes
 * @property \App\Model\Table\PersonnelsTable|\Cake\ORM\Association\BelongsTo $Personnels
 *
 * @method \App\Model\Entity\PersonnelsAntenne get($primaryKey, $options = [])
 * @method \App\Model\Entity\PersonnelsAntenne newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PersonnelsAntenne[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PersonnelsAntenne|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PersonnelsAntenne|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PersonnelsAntenne patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PersonnelsAntenne[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PersonnelsAntenne findOrCreate($search, callable $callback = null, $options = [])
 */
class PersonnelsAntennesTable extends Table
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

        $this->setTable('personnels_antennes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Antennes', [
            'foreignKey' => 'antenne_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Personnels', [
            'foreignKey' => 'personnel_id',
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
        $rules->add($rules->existsIn(['antenne_id'], 'Antennes'));
        $rules->add($rules->existsIn(['personnel_id'], 'Personnels'));

        return $rules;
    }
}
