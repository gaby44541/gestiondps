<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsersOrganisateurs Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\OrganisateursTable|\Cake\ORM\Association\BelongsTo $Organisateurs
 *
 * @method \App\Model\Entity\UsersOrganisateur get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersOrganisateur newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsersOrganisateur[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersOrganisateur|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersOrganisateur|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersOrganisateur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersOrganisateur[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersOrganisateur findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersOrganisateursTable extends Table
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

        $this->setTable('users_organisateurs');
        $this->setDisplayField('organisateur_id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Organisateurs', [
            'foreignKey' => 'organisateur_id',
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['organisateur_id'], 'Organisateurs'));

        return $rules;
    }
}
