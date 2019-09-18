<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigConventions Model
 *
 * @method \App\Model\Entity\ConfigConvention get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigConvention newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigConvention[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigConvention|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigConvention|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigConvention patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigConvention[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigConvention findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigConventionsTable extends Table
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

        $this->setTable('config_conventions');
        $this->setDisplayField('designation');
        $this->setPrimaryKey('id');
		
		$this->addBehavior('Ordre');
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
            ->scalar('designation')
            ->maxLength('designation', 255)
            ->requirePresence('designation', 'create')
            ->notEmpty('designation');

        $validator
            ->scalar('description')
            ->maxLength('description', 4294967295)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->integer('ordre')
            ->requirePresence('ordre', 'create')
            ->notEmpty('ordre');

        return $validator;
    }
}
