<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigRecommandations Model
 *
 * @method \App\Model\Entity\ConfigRecommandation get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigRecommandation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigRecommandation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigRecommandation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigRecommandation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigRecommandation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigRecommandation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigRecommandation findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigRecommandationsTable extends Table
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

        $this->setTable('config_recommandations');
        $this->setDisplayField('recommandations');
        $this->setPrimaryKey('id');
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
            ->integer('indice')
            ->allowEmpty('indice');

        $validator
            ->scalar('recommandations')
            ->maxLength('recommandations', 4294967295)
            ->allowEmpty('recommandations');

        return $validator;
    }
}
