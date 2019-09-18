<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Personnels Model
 *
 * @property \App\Model\Table\AntennesTable|\Cake\ORM\Association\BelongsToMany $Antennes
 * @property \App\Model\Table\EquipesTable|\Cake\ORM\Association\BelongsToMany $Equipes
 *
 * @method \App\Model\Entity\Personnel get($primaryKey, $options = [])
 * @method \App\Model\Entity\Personnel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Personnel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Personnel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Personnel|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Personnel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Personnel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Personnel findOrCreate($search, callable $callback = null, $options = [])
 */
class PersonnelsTable extends Table
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

        $this->setTable('personnels');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Antennes', [
            'foreignKey' => 'personnel_id',
            'targetForeignKey' => 'antenne_id',
            'joinTable' => 'personnels_antennes'
        ]);
        $this->belongsToMany('Equipes', [
            'foreignKey' => 'personnel_id',
            'targetForeignKey' => 'equipe_id',
            'joinTable' => 'personnels_equipes'
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
            ->scalar('prenom')
            ->maxLength('prenom', 15)
            ->allowEmpty('prenom');

        $validator
            ->scalar('nom')
            ->maxLength('nom', 17)
            ->allowEmpty('nom');

        $validator
            ->scalar('nom_naissance')
            ->maxLength('nom_naissance', 16)
            ->allowEmpty('nom_naissance');

        $validator
            ->scalar('statut')
            ->maxLength('statut', 3)
            ->allowEmpty('statut');

        $validator
            ->scalar('rue')
            ->maxLength('rue', 93)
            ->allowEmpty('rue');

        $validator
            ->scalar('code_postal')
            ->maxLength('code_postal', 6)
            ->allowEmpty('code_postal');

        $validator
            ->scalar('ville')
            ->maxLength('ville', 25)
            ->allowEmpty('ville');

        $validator
            ->scalar('identifiant')
            ->maxLength('identifiant', 18)
            ->allowEmpty('identifiant');

        $validator
            ->scalar('portable')
            ->maxLength('portable', 14)
            ->allowEmpty('portable');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 14)
            ->allowEmpty('telephone');

        $validator
            ->scalar('mail')
            ->maxLength('mail', 255)
            ->allowEmpty('mail');

        $validator
            ->scalar('antenne')
            ->maxLength('antenne', 60)
            ->allowEmpty('antenne');

        $validator
            ->scalar('entreprise')
            ->maxLength('entreprise', 10)
            ->allowEmpty('entreprise');

        $validator
            ->date('naissance_date')
            ->allowEmpty('naissance_date');

        $validator
            ->scalar('naissance_lieu')
            ->maxLength('naissance_lieu', 25)
            ->allowEmpty('naissance_lieu');

        $validator
            ->scalar('prevenir')
            ->maxLength('prevenir', 38)
            ->allowEmpty('prevenir');

        $validator
            ->scalar('prevenir_telephone')
            ->maxLength('prevenir_telephone', 14)
            ->allowEmpty('prevenir_telephone');

        return $validator;
    }
}
