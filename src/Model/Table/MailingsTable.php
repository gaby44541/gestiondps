<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mailings Model
 *
 * @property \App\Model\Table\MailsTable|\Cake\ORM\Association\BelongsTo $Mails
 *
 * @method \App\Model\Entity\Mailing get($primaryKey, $options = [])
 * @method \App\Model\Entity\Mailing newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Mailing[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Mailing|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mailing|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mailing patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Mailing[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Mailing findOrCreate($search, callable $callback = null, $options = [])
 */
class MailingsTable extends Table
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

        $this->setTable('mailings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Mails', [
            'foreignKey' => 'mail_id',
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
            ->scalar('uuid')
            ->maxLength('uuid', 255)
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid');

        $validator
            ->scalar('destinataire')
            ->maxLength('destinataire', 255)
            ->requirePresence('destinataire', 'create')
            ->notEmpty('destinataire');

        $validator
            ->dateTime('send')
            ->requirePresence('send', 'update')
            ->notEmpty('send');

        $validator
            ->dateTime('read')
            ->requirePresence('read', 'update');

        $validator
            ->scalar('message')
            ->maxLength('message', 4294967295)
            ->requirePresence('message', 'create')
            ->notEmpty('message');

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
        $rules->add($rules->existsIn(['mail_id'], 'Mails'));

        return $rules;
    }
}
