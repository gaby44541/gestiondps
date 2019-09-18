<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mails Model
 *
 * @property \App\Model\Table\MailingsTable|\Cake\ORM\Association\HasMany $Mailings
 *
 * @method \App\Model\Entity\Mail get($primaryKey, $options = [])
 * @method \App\Model\Entity\Mail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Mail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Mail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Mail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Mail findOrCreate($search, callable $callback = null, $options = [])
 */
class MailsTable extends Table
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

        $this->setTable('mails');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
		
		$this->addBehavior('Duplicatable');

        $this->hasMany('Mailings', [
            'foreignKey' => 'mail_id'
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
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->scalar('controller')
            ->maxLength('controller', 255)
            ->requirePresence('controller', 'create')
            ->notEmpty('controller');

        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->requirePresence('action', 'create')
            ->notEmpty('action');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->notEmpty('subject');

        $validator
            ->scalar('message')
            ->maxLength('message', 4294967295)
            ->requirePresence('message', 'create')
            ->notEmpty('message');

        $validator
            ->scalar('format')
            ->maxLength('format', 255)
            ->requirePresence('format', 'create')
            ->notEmpty('format');

        $validator
            ->scalar('attachments')
            ->requirePresence('attachments', 'create')
            ->notEmpty('attachments');

        $validator
            ->boolean('publish')
            ->requirePresence('publish', 'create')
            ->notEmpty('publish');

        return $validator;
    }
}
