<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\Log\Log;
use Cake\Routing\Router;

/**
 * Demandes Model
 *
 * @property \App\Model\Table\OrganisateursTable|\Cake\ORM\Association\BelongsTo $Organisateurs
 * @property \App\Model\Table\ConfigEtatsTable|\Cake\ORM\Association\BelongsTo $ConfigEtats
 * @property \App\Model\Table\AntennesTable|\Cake\ORM\Association\BelongsTo $Antennes
 * @property \App\Model\Table\DimensionnementsTable|\Cake\ORM\Association\HasMany $Dimensionnements
 *
 * @method \App\Model\Entity\Demande get($primaryKey, $options = [])
 * @method \App\Model\Entity\Demande newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Demande[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Demande|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Demande|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Demande patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Demande[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Demande findOrCreate($search, callable $callback = null, $options = [])
 */
class DemandesTable extends Table
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

        $this->setTable('demandes');
        $this->setDisplayField('manifestation');
        $this->setPrimaryKey('id');
		
		$this->addBehavior('Listing');
		$this->addBehavior('Duplicatable');
		$this->addBehavior('Chronologie',[
			'update' => 'chronologie',
			'listen' => 'config_etat_id',
			'compare' =>[
				13 => 'demande',
				5  => 'etude.envoi',
				6  => 'etude.signee',
				7  => 'convention.envoi',
				8  => 'convention.signee',
				10 => 'facture.envoi',
				11 => 'facture.reglee',
				14 => 'annulation'
			]
		]);

        $this->belongsTo('Organisateurs', [
            'foreignKey' => 'organisateur_id'
        ]);
        $this->belongsTo('ConfigEtats', [
            'foreignKey' => 'config_etat_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Antennes', [
            'foreignKey' => 'antenne_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('Dimensionnements', [
            'foreignKey' => 'demande_id',
			'dependent' => true,
			'sort' => ['Dimensionnements.horaires_debut'=>'asc']
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
            ->scalar('manifestation')
            ->maxLength('manifestation', 255)
            ->allowEmpty('manifestation');

        $validator
            ->scalar('representant')
            ->maxLength('representant', 255)
            ->allowEmpty('representant');

        $validator
            ->scalar('representant_fonction')
            ->maxLength('representant_fonction', 255)
            ->allowEmpty('representant_fonction');

		$validator
            ->scalar('chronologie')
            ->allowEmpty('chronologie');
			
        $validator
            ->scalar('gestionnaire_nom')
            ->maxLength('gestionnaire_nom', 255)
            ->requirePresence('gestionnaire_nom', 'create')
            ->notEmpty('gestionnaire_nom');

        $validator
            ->scalar('gestionnaire_mail')
            ->maxLength('gestionnaire_mail', 255)
            ->requirePresence('gestionnaire_mail', 'create')
            ->notEmpty('gestionnaire_mail');

        $validator
            ->scalar('gestionnaire_telephone')
            ->maxLength('gestionnaire_telephone', 255)
            ->requirePresence('gestionnaire_telephone', 'create')
            ->notEmpty('gestionnaire_telephone');

        $validator
            ->scalar('remise_justification')
            ->requirePresence('remise_justification', 'create')
            ->allowEmpty('remise_justification');

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
        $rules->add($rules->existsIn(['organisateur_id'], 'Organisateurs'));
        $rules->add($rules->existsIn(['config_etat_id'], 'ConfigEtats'));
        $rules->add($rules->existsIn(['antenne_id'], 'Antennes'));

        return $rules;
    }
    
	/**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function wizard( $id )
    {
		$return = [];
		$wizard = $this->find()
					->contain(['Dimensionnements.Dispositifs.Equipes'])
					->where(['id' => $id])
					->first();
		if (empty($wizard)) {
			return array();
		}
		
		$wizard = $wizard->toArray();

		$return['Demandes.id'] = $wizard['id'];
		$return['Organisateurs.id'] = $wizard['organisateur_id'];
		$return['Dimensionnements.id'] = (array) Hash::extract( $wizard , 'dimensionnements.{n}.id');
		$return['Dispositifs.id'] = (array) Hash::extract( $wizard , 'dimensionnements.{n}.dispositif.id');
		$return['Equipes.id'] = (array) Hash::extract( $wizard , 'dimensionnements.{n}.dispositif.equipes.{n}.id');
		
		return $return;
    }
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function duplicatedd( $id , array $options , array $paths_reset )
    {
		$id  = (int) $id;
		
		$options = Hash::merge( $options , ['contain' => ['Dimensionnements.Dispositifs.Equipes']] );
		$paths_reset = Hash::merge( $paths_reset , ['id','dimensionnements.{n}.dispositif.equipes.{n}.id','dimensionnements.{n}.dispositif.id','antennes.id'] );
		
        $demande = $this->findById($id,$options);

		foreach( $paths_reset as $path_reset ){
			$demande = Hash::remove($demande, $path_reset);
		}
		
		$associated = $options['contain'];
		
		$this->save( $demande,['associated' => $associated]);
    }
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function listeMiniMaxi( $mini , $maxi , $order = 'asc' )
    {
		$demandes = $this->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
				'sort' => ['Dimensionnements.horaires_debut' => 'ASC']
			],'Antennes']])
		->where(['ConfigEtats.ordre >='=>$mini,'ConfigEtats.ordre <='=>$maxi])
		->order(['ConfigEtats.ordre'=>'asc','Demandes.id'=>'asc']);
	
		return Hash::sort($demandes->toArray(),'{n}.dimensionnements.0.horaires_debut', $order);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function listeMiniMaxiAll( $mini , $maxi , $order = 'asc' )
    {
		$demandes = $this->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
				'sort' => ['Dimensionnements.horaires_debut' => 'ASC']
			],'Antennes','Dimensionnements.Dispositifs.Equipes']])
		->where(['ConfigEtats.ordre >='=>$mini,'ConfigEtats.ordre <='=>$maxi])
		->order(['ConfigEtats.ordre'=>'asc','Demandes.id'=>'asc']);
	
		return Hash::sort($demandes->toArray(),'{n}.dimensionnements.0.horaires_debut', $order);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function listeMiniMaxiBeforeToday( $mini , $maxi , $order = 'asc' )
    {

		$demandes = $this->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
				'sort' => ['Dimensionnements.horaires_debut' => 'ASC']
			],'Antennes']])
		->where(['ConfigEtats.ordre'=>$mini])
		->order(['ConfigEtats.ordre'=>'asc','Demandes.id'=>'asc'])
		->matching('Dimensionnements', function ($q) {
			$time = (string) date('Y-m-d 00:00:00');
			return $q->where(['Dimensionnements.horaires_fin <' => $time]);
		});
	
		return Hash::sort($demandes->toArray(),'{n}.dimensionnements.0.horaires_debut', $order);
    }
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function listeMiniMaxiJson( $mini , $maxi , $order = 'asc' )
    {
		
		$json_data = $this->Dimensionnements->find('all',['contain'=>['Demandes.ConfigEtats']])
										//->select(['id','title'=>'intitule','start'=>'horaires_debut','end'=>'horaires_fin','demande_id','color'=>'ConfigEtats.ordre'])
										->where(['ConfigEtats.ordre >='=>$mini,'ConfigEtats.ordre <='=>$maxi])
										->order(['horaires_debut'=>'ASC'])
										->toArray();
		
		$json_data = Hash::extract($json_data,'{n}.calendar');
		
		//foreach($json_data as $json){
			//Log::write('debug', $json->calendar);
			//$json->start = date('Y-m-d H:i:s',$json->start->toUnixString());
			//$json->end = date('Y-m-d H:i:s',$json->end->toUnixString());
			//$json->url = Router::url(['controller'=>'demandes','action'=>'wizard',3,'demandes__'.$json->demande_id]);
		//}
		
		return $json_data;
    }
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function dossierComplet($id = 0)
    {
		$errors = [];
		
		$demande = $this->get($id,['contain'=>['Dimensionnements.Dispositifs.Equipes','Antennes','ConfigEtats']]);
		
		if( $demande ){
			
			if( $demande->config_etat->ordre < 6 ){
				
				$dimensionnements = Hash::extract( $demande , 'dimensionnements.{n}');
				
				if( ! empty($dimensionnements)){

					$dispositifs = Hash::extract( $dimensionnements , '{n}.dispositif.id');

					if( ! empty($dispositifs)){
						
						$dispositifs = Hash::extract( $dimensionnements , '{n}.dispositif');
						
						$this->modifyEtat($id,3,false);
						
						if(count($dimensionnements) != count($dispositifs)){
							$this->modifyEtat($id,3);
							$errors[] = 'dispositifs.incomplets';
						}
						
						foreach($dispositifs as $dispositif){
							$exists = Hash::extract($dispositif,'equipes.{n}.effectif');
							$count = count($exists);
							$exists = array_sum($exists);
							
							if(empty($count)){
								if(!in_array('equipes.vides',$errors)){
									$this->modifyEtat($id,3);
									$errors[] = 'equipes.vides';
								}
							}

							if($exists < $dispositif['personnels_total']){
								if(!in_array('equipes.incompletes',$errors)){
									$this->modifyEtat($id,3);
									$errors[] = 'equipes.incompletes';
								}
							}
						}
					
					} else {
						$this->modifyEtat($id,1);
						$errors[] = 'dispositifs.vides';
					}
					
				} else {
					
					$this->modifyEtat($id,1);
					$errors[] = 'dimensionnements';
					
				}
			
			}

		} else {
			$errors[] = 'demande';
		}
						
		if(empty($errors)){
			$this->modifyEtat($id,3,false);
		}
		//Log::write('debug',$errors);			
				
		return $errors;

	}

	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function modifyEtat( $id = 0 , $etat = 0 , $superieur = true )
    {
		$id  = (int) $id;
		$etat = (int) $etat;
		
		$etats = $this->ConfigEtats->alone($etat);
		$etats = key($etats);
		
		$demande = $this->get($id,['contain'=>['ConfigEtats']]);
		
		$actuel = (int) $demande->config_etat->ordre;
		
		if( $superieur){
			if($actuel > $etat){
				$demande->set('config_etat_id',$etats);
				$this->save( $demande );
			}
		} else {
			if($actuel < $etat){
				$demande->set('config_etat_id',$etats);
				$this->save( $demande );
			}			
		}

    }
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function modifyEtatByDemandesId( $demandes_ids = [] , $etat = 0 )
    {
		$demandes_ids = (array) $demandes_ids;
		$demandes_ids = array_unique($demandes_ids);
		
		$etat = (int) $etat;

		if(!empty($demandes_ids)){
			foreach($demandes_ids as $demandes_id){
				$this->modifyEtat($demandes_id,$etat,false);
			}
		}
		
		return false;

    }
	
}
