<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\OrganisateursTable|\Cake\ORM\Association\BelongsToMany $Organisateurs
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Organisateurs', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'organisateur_id',
            'joinTable' => 'users_organisateurs'
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
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->scalar('nom')
            ->maxLength('nom', 255)
            ->requirePresence('nom', 'create')
            ->notEmpty('nom');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 255)
            ->requirePresence('telephone', 'create')
            ->notEmpty('telephone');

        $validator
            ->boolean('externe')
            ->requirePresence('externe', 'create')
            ->notEmpty('externe');

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
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }

	/**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
	public function validationPasswords(Validator $validator)
	{
		
		$validator->allowEmpty('nouveau');
		$validator->allowEmpty('confirmer');
		
		$validator
			->lengthBetween('nouveau', [8, 30],'Le nouveau mot de passe doit être compris entre 8 et 30 caractères')
			->add('confirmer', 'no-misspelling', [
				'rule' => ['notBlank'],
				'message' => 'Il ne peut y avoir que des espaces dans votre mot de passe.',
				'on' => function ($context) {
					return !empty($context['data']['confirmer'])&&!empty($context['data']['nouveau']);
				}
			])
			->add('confirmer', 'no-misspelling', [
				'rule' => ['compareWith', 'nouveau'],
				'last' => true,
				'message' => 'Les mots de passe ne sont pas identique',
				'on' => function ($context) {
					return !empty($context['data']['confirmer'])&&!empty($context['data']['nouveau']);
				}
			]);

		return $validator;
	}
}
