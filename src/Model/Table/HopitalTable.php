<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Hopital Model
 *
 * @property |\Cake\ORM\Association\HasMany $Demandes
 * @property \App\Model\Table\PersonnelsTable|\Cake\ORM\Association\BelongsToMany $Personnels
 *
 * @method \App\Model\Entity\Caserne get($primaryKey, $options = [])
 * @method \App\Model\Entity\Caserne newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Caserne[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Caserne|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Caserne|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Caserne patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Caserne[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Caserne findOrCreate($search, callable $callback = null, $options = [])
 */
class HopitalTable extends Table
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

        $this->setTable('Hopital');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

        $this->hasMany('Dimensionnements', [
            'foreignKey' => 'id_hopital'
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
            ->scalar('nom')
            ->maxLength('nom', 255)
            ->notEmpty('nom')
			->requirePresence('nom');

        return $validator;
    }
}
