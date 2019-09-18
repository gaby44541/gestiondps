<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Wizards Model
 *
 * @method \App\Model\Entity\Wizard get($primaryKey, $options = [])
 * @method \App\Model\Entity\Wizard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Wizard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Wizard|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Wizard|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Wizard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Wizard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Wizard findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WizardsTable extends Table
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

        $this->setTable('wizards');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('uuid')
            ->maxLength('uuid', 255)
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid');

        $validator
            ->integer('step')
            ->requirePresence('step', 'create')
            ->notEmpty('step');

        $validator
            ->scalar('datas')
            ->maxLength('datas', 4294967295)
            ->requirePresence('datas', 'create')
            ->notEmpty('datas');

        return $validator;
    }
}
