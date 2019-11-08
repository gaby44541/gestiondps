<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\I18n\Number;
use Cake\Http\Session;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Log\Log;
use Cake\Http\Response;

//use Cake\Core\App;
//use TCPDF;

/**
 * Demandes Controller
 *
 * @property \App\Model\Table\DemandesTable $Demandes
 *
 * @method \App\Model\Entity\Demande[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DemandesController extends AppController {

    var $navigation = [
        ['label' => 'New demande', 'config' => ['controller' => 'Demandes', 'action' => 'add']],
        ['label' => 'List demande', 'config' => ['controller' => 'Demandes', 'action' => 'index']],
        ['label' => 'List ConfigEtats', 'config' => ['controller' => 'ConfigEtats', 'action' => 'index']],
        ['label' => 'Add ConfigEtats', 'config' => ['controller' => 'ConfigEtats', 'action' => 'add']],
        ['label' => 'List Organisateurs', 'config' => ['controller' => 'Organisateurs', 'action' => 'index']],
        ['label' => 'Add Organisateurs', 'config' => ['controller' => 'Organisateurs', 'action' => 'add']],
        ['label' => 'List Dimensionnements', 'config' => ['controller' => 'Dimensionnements', 'action' => 'index']],
        ['label' => 'Add Dimensionnements', 'config' => ['controller' => 'Dimensionnements', 'action' => 'add']],
    ];

	var $arraysum = ['paths'=>[
							'total_cout'=>'+dimensionnements.{n}.dispositif.equipes.{n}.cout_personnel/dimensionnements.{n}.dispositif.equipes.{n}.cout_kilometres/dimensionnements.{n}.dispositif.equipes.{n}.cout_repas',
							'total_personnel'=>'+dimensionnements.{n}.dispositif.personnels_public/dimensionnements.{n}.dispositif.personnels_acteurs',
							'total_duree'=>'dimensionnements.{n}.dispositif.equipes.{n}.duree',
							'total_kilometres'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_kilometres',
							'total_repas'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_repas',
							'total_remise'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_remise',
							'total_economie'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_economie',
							'total_antennes'=>'dimensionnements.{n}.dispositif.equipes.{n}.repartition_antenne',
							'total_adpc'=>'dimensionnements.{n}.dispositif.equipes.{n}.repartition_adpc',
							'total_repas_matin'=>'*dimensionnements.{n}.dispositif.equipes.{n}.repas_matin/dimensionnements.{n}.dispositif.equipes.{n}.repas_charge',
							'total_repas_midi'=>'*dimensionnements.{n}.dispositif.equipes.{n}.repas_midi/dimensionnements.{n}.dispositif.equipes.{n}.repas_charge',
							'total_repas_soir'=>'*dimensionnements.{n}.dispositif.equipes.{n}.repas_soir/dimensionnements.{n}.dispositif.equipes.{n}.repas_charge',
							//'total_vehicules'=>'dimensionnements.{n}.dispositif.equipes.{n}.vehicule_type',
							'total_lota'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_a',
							'total_lotb'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_b',
							'total_lotc'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_c',
							]
						];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($etat = -1) {

		$etat = (int) $etat;

		$this->ArraySum->setConfig($this->arraysum);

		if($etat>=0){
			$demandes = $this->Demandes->find('all',[
				'contain' => [	'ConfigEtats',
								'Organisateurs',
								'Dimensionnements.Dispositifs.Equipes',
								'Antennes'],
			])
			->where(['config_etat_id'=>$etat])
			->mapReduce( $this->ArraySum->getMapper() , $this->ArraySum->getReduce());
		} else{
			$demandes = $this->Demandes->find('all',[
				'contain' => [	'ConfigEtats',
								'Organisateurs',
								'Dimensionnements.Dispositifs.Equipes',
								'Antennes'],
			])
			->where(['ConfigEtats.ordre >='=>0,'ConfigEtats.ordre <='=>10])
			->order(['config_etat_id'=>'asc'])
			->mapReduce( $this->ArraySum->getMapper() , $this->ArraySum->getReduce());
		}

		$compteur = $this->Demandes->find('list', [
			'keyField' => 'id',
			'valueField' => 'config_etat_id'
		])->toArray();

		$compteur = array_count_values( $compteur );

        $navigation = $this->navigation;

		$etats = $this->Demandes->ConfigEtats->find('list', ['contain'=>[],'order' => ['ordre' => 'asc']])->toArray();

		foreach($etats as $key => &$val){
			if(isset($compteur[$key])){
				$val .= ' - <b>('.$compteur[$key].')</b>';
			}
		}

        $this->set(compact('demandes','etats','etat','navigation'));
    }

    public function ajax($function = false) {

        $this->autoRender = false;

        // Force le controller à rendre une réponse JSON.
        $this->RequestHandler->renderAs($this, 'json');

        // Définit le type de réponse de la requete AJAX
        $this->response->withType('application/json');

        // Chargement du layout AJAX
        $this->viewBuilder()->setLayout('ajax');

		$json_data = $this->Demandes->listeMiniMaxiJson(0,12);

        // Chargement des données
        //$json_data[] = ['id' => 2500, 'title' => 'test', 'start' => '2018-07-16 12:30:00', 'end' => '2018-07-18 05:45:00', 'url' => 'http://localhost/crud/antennes/view/1'];
        //$json_data[] = ['id' => 2501, 'title' => 'test', 'start' => '2018-07-18 05:45:00', 'end' => '2018-07-27']; //,'rendering'=>'background'

        $response = $this->response->withType('json')->withStringBody(json_encode($json_data));

        // Retour des données encodées en JSON
        return $response;
    }

    /**
     * View method
     *
     * @param string|null $id Demande id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {

		$errors = $this->Demandes->dossierComplet($id);

		if(in_array('demande',$errors)){
			$this->Flash->error(__('Cette demande n\'existe pas.'));
			return $this->redirect(['controller'=>'pages','action'=>'display','accueil']);
		}

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		if(! $demande->id ){
			return $this->redirect(['action'=>'index']);
		}
		if($demande->config_etat->ordre > 4 && $demande->config_etat->ordre != 10){
			$this->set('readonly', true);
		} else {
			$this->set('readonly', false);
		}

        $this->set('demande', $demande);
        $this->set('navigation', $this->navigation);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {

		$demande = $this->Demandes->newEntity();
		$demande->set('gestionnaire_nom',$this->Auth->user('nom'));
		$demande->set('gestionnaire_mail',$this->Auth->user('username'));
		$demande->set('gestionnaire_telephone',$this->Auth->user('telephone'));
		$demande->set('antenne_id',10);
		$configEtats = $this->Demandes->ConfigEtats->alone();

		if($this->request->is('post')) {

            $demande = $this->Demandes->patchEntity($demande, $this->request->getData());
            $demande->set('config_etat_id',$this->Demandes->ConfigEtats->firstid());

			if($this->Demandes->save($demande)) {
                $this->Flash->success(__('La demande a été sauvegardée.'));

                return $this->redirect(['action' => 'index']);
            }
            $msgErreur = "<p>La demande n'a pas pu être sauvegardée.</p><p> Merci de réessayer.</p> \r\n\r";
            foreach($demande->errors() as $cleDemandeErreur => $valeurDemandeErreur){
                $msgErreur .= $cleDemandeErreur . '=>';
                foreach($valeurDemandeErreur as $cleChamp => $valeurChamp){
                    $msgErreur .= '('.$cleChamp.'=>'.$valeurChamp.')';
                }
            }
            $this->Flash->error(__($msgErreur),['escape' => false]);
        }

        $configEtats = $this->Demandes->ConfigEtats->find('list', ['order' => ['ordre' => 'asc']]);
        $organisateurs = $this->Demandes->Organisateurs->listing(['select'=>['MAX(id) AS id','*'],
																  'where'=>['publish'=>1],
																  'order'=>['nom'=>'asc'],
																  'group'=>['uuid']]);
        $antennes = $this->Demandes->Antennes->find('list');

        $navigation = $this->navigation;

        $this->set(compact('demande', 'configEtats', 'antennes', 'organisateurs', 'navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Demande id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function dispatch($id = null) {

		$demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats']
        ]);

		$ordre = (int) $demande->get('config_etat')->get('ordre');

		if($ordre <4){
			$url = ['controller'=>'demandes','action'=>'wizard',3,'demandes__'.$id];
		} else {
			$url = ['controller'=>'demandes','action'=>'view',$id];
		}

		return $this->redirect($url);

	}

    /**
     * Edit method
     *
     * @param string|null $id Demande id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {

		$demande = $this->Demandes->get($id, [
            'contain' => ['Organisateurs']
        ]);

		if($this->request->is(['patch', 'post', 'put'])) {

            $demande = $this->Demandes->patchEntity($demande, $this->request->getData());

			if($this->Demandes->save($demande)) {

                $this->Flash->success(__('La demande a été sauvegardée.'));
                return $this->redirect(['action' => 'view',$id]);
            }

            $this->Flash->error(__('The demande could not be saved. Please, try again.'));

        }

        $configEtats = $this->Demandes->ConfigEtats->find('list', ['limit' => 200, 'order' => ['ordre' => 'asc']]);
        $organisateurs = $this->Demandes->Organisateurs->find('list', ['limit' => 200,'order'=>['id'>'desc']])->where(['uuid'=>$demande->organisateur->uuid]);
        $antennes = $this->Demandes->Antennes->find('list', ['limit' => 200]);

        $navigation = $this->navigation;

        $this->set(compact('demande', 'configEtats', 'organisateurs', 'antennes', 'navigation'));
    }

    /**
     * Cette méthode est appelée lorsqu'on appelle l'action "demander-coa"
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function demanderCoa($id = null) {
        $messages = [
            'step' => __('Votre dossier doit être en phase d\'étude avec toutes les données valides pour prétendre à l\'envoi.'),
            'success' => __('La demande de COA a bien été faite.'),
            'error' => __('Impossible de passer à l\'étape d\'après pour des raisons techniques.')
        ];
        $this->_traitementSteps($id,4,3,$messages);

        Log::write('debug', 'Demander COA ');
   		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);
    }

    /**
     * Cette méthode est appelée lorsqu'on appelle l'action "reception-coa"
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function receptionCoa($id = null) {
        Log::write('debug', 'Réception COA ');
        $messages = [
            'step' => __('Votre dossier doit être en phase de demande de COA pour pouvoir recevoir le COA.'),
            'success' => __('La réception du COA a bien été validée.'),
            'error' => __('Impossible de passer à l\'étape d\'après pour des raisons techniques.')
        ];
        $this->_traitementSteps($id,5,4,$messages);
  		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);
    }


	/**
     * Cette méthode est appelée lorsqu'on appelle l'action "etude-envoyee"
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function etudeEnvoyee($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier doit être en phase d\'étude avec toutes les données valides pour prétendre à l\'envoi.'),
			'success' => __('Vous allez envoyer l\'étude vous même, le dossier est donc en attente de signature.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,4,3,$messages);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$file = $this->Xtcpdf->getStart();
		$file = $this->Xtcpdf->getEtude($demande,$file,false);
		$file = $this->Xtcpdf->getAttachement($file);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom,
					$demande->antenne->technique_mail => $demande->antenne->technique_nom
			],
			'attachements' => [
								'etude_'.$demande->id.'.pdf' => ['data' => $file, 'mimetype' => 'application/pdf'],
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-etude');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function etudeRelance($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier doit être en phase d\'étude avec toutes les données valides pour prétendre à l\'envoi.'),
			'success' => __('Vous allez envoyer l\'étude vous même, le dossier est donc en attente de signature.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,4,3,$messages);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$file = $this->Xtcpdf->getStart();
		$file = $this->Xtcpdf->getEtude($demande,$file,false);
		$file = $this->Xtcpdf->getAttachement($file);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom,
					$demande->antenne->technique_mail => $demande->antenne->technique_nom
			],
			'attachements' => [
								'etude_'.$demande->id.'.pdf' => ['data' => $file, 'mimetype' => 'application/pdf'],
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-etude');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Cette méthode est appelée lorsqu'on appelle l'action "etude-signee"
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function etudeSignee($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier n\'est pas en attente de signature.'),
			'success' => __('L\'étude a été signée, vous pouvez rechercher le personnel nécessaire.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,5,4,$messages);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$file = $this->Xtcpdf->getStart();
		$file = $this->Xtcpdf->getEtude($demande,$file,false);
		$file = $this->Xtcpdf->getAttachement($file);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom
			],
			'attachements' => [
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-recrutement');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function conventionEnvoyee($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier doit être en phase de recherche des effectifs avec toutes les données valides pour prétendre à l\'envoi.'),
			'success' => __('Vous allez envoyer la convention vous même, le dossier est donc en attente de signature.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,6,5,$messages);

		$this->loadModel('ConfigConventions');

		$convention = $this->ConfigConventions->find('all',['order'=>['ordre'=>'asc']]);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$array = $this->Demandes->get($id,[
            'contain' => ['Organisateurs']
        ])->toArray();

		$array = $this->ArraySum->mergeLast( $array );

		//Number::config('fr_FR', \NumberFormatter::SPELLOUT);
		$array['total_cout_total'] = Number::format( $demande->total_cout );

		$flatten = Hash::flatten($array);
		$flatten['count::dimensionnements'] = count( $demande->dimensionnements );

		$flat_keys = array_keys($flatten);
		$flat_vals = array_values($flatten);
		$flat_keys = Hash::format($flat_keys, ['{n}'], '{{%1$s}}');
		$flatten = array_combine($flat_keys,$flat_vals);

		foreach( $convention as $item ):
			$item->description = str_replace(array_keys($flatten),$flatten,$item->description );
		endforeach;


		$file = $this->Xtcpdf->getStart();
		$file = $this->Xtcpdf->getConvention($demande,$convention,$flatten,$file,false);
		$file = $this->Xtcpdf->getAttachement($file);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom,
					$demande->antenne->technique_mail => 'Protection Civile - '.$demande->antenne->technique_nom
			],
			'attachements' => [
								'convention_'.$demande->id.'.pdf' => ['data' => $file, 'mimetype' => 'application/pdf'],
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-convention');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function conventionSignee($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier doit être en phase de recherche des effectifs avec toutes les données valides pour prétendre à l\'envoi.'),
			'success' => __('Vous allez envoyer la convention vous même, le dossier est donc en attente de signature.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,7,6,$messages);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom
			],
			'attachements' => [
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-poste');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function posteRealise($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier doit être en phase "convention signée" pour prétendre à la réalisation du poste.'),
			'success' => __('Vous allez envoyer la convention vous même, le dossier est donc en attente de signature.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,8,7,$messages);

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function posteBilan($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier doit être en phase de poste réalisé pour prétendre à la saisie du bilan.'),
			'success' => __('Vous allez saisir le bilan.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,9,8,$messages);

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function posteFacture($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Votre dossier doit être en phase de bilan avec toutes les données valides pour prétendre à l\'envoi.'),
			'success' => __('Vous allez envoyer la facture vous même.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,10,9,$messages);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);


		$file = $this->Xtcpdf->getStart();
		$file = $this->Xtcpdf->getFacture($demande,$file,false);
		$file = $this->Xtcpdf->getAttachement($file);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					//$demande->organisateur->tresorier_mail => $demande->organisateur->tresorier,
					$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom,
					$demande->antenne->tresorier_mail => 'Protection Civile - '.$demande->antenne->tresorier_nom
			],
			'attachements' => [
								'facture_'.$demande->id.'.pdf' => ['data' => $file, 'mimetype' => 'application/pdf'],
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-facture');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function postePaye($id = null) {

		$this->autoRender = false;

		$messages = [
			'step' => __('Ce dossier n\'est pas en attente de règlement, vérifiez l\'avancée du dossier avant de vouloir valider le règlement.'),
			'success' => __('Le dispositif a été clotûré suite au règlement.'),
			'error' => __('Impossible de clotûrer le dossier suite au règlement pour des raisons techniques.')
		];

		$this->_traitementSteps($id,11,10,$messages,false);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$file = $this->Xtcpdf->getStart();
		$file = $this->Xtcpdf->getEtude($demande,$file,false);
		$file = $this->Xtcpdf->getAttachement($file);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					//$demande->organisateur->tresorier_mail => $demande->organisateur->tresorier,
					$demande->gestionnaire_mail => $demande->gestionnaire_nom
			],
			'attachements' => [
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-cloture');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function posteAnnule($id = null) {

		$this->autoRender = false;

		$messages = [
			'success' => __('Le dispositif a été annulé.'),
			'error' => __('Impossible d\'annuler ce dispositif pour des raisons techniques.')
		];

		$this->_traitementSteps($id,11,0,$messages);

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}


	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function relanceFacture($id = null) {

		$this->autoRender = false;
		/*
		$messages = [
			'step' => __('Votre dossier doit être en phase de bilan avec toutes les données valides pour prétendre à l\'envoi.'),
			'success' => __('Vous allez envoyer la facture vous même.'),
			'error' => __('Impossible passer à l\'étape d\'après pour des raisons techniques.')
		];

		$this->_traitementSteps($id,10,9,$messages);
		*/

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$file = $this->Xtcpdf->getStart();
		$file = $this->Xtcpdf->getFacture($demande,$file,false);
		$file = $this->Xtcpdf->getAttachement($file);

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		$mail = [
			'replyTo' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom],
			'to' => [
					$demande->organisateur->mail => $demande->organisateur->representant,
					//$demande->organisateur->tresorier_mail => $demande->organisateur->tresorier,
					$demande->gestionnaire_mail => 'Protection Civile - '.$demande->gestionnaire_nom,
					$demande->antenne->tresorier_mail => 'Protection Civile - '.$demande->antenne->tresorier_nom
			],
			'attachements' => [
								'facture_'.$demande->id.'.pdf' => ['data' => $file, 'mimetype' => 'application/pdf'],
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];

		$this->_traitementMails($mail,$demande,'mail-facture');

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    protected function _traitementSteps($id = null , $souhaite = 0 , $necessite = 0 , $messages = [] , $security = true) {

		$security = (boolean) $security;

		$messages = array_merge([
			'referer' => __('Accès non autorisé, vous essayez de modifier une donnée protégée.'),
			'step' => __('Accès non autorisé, vous essayez de modifier une donnée protégée.'),
			'success' => __('Le dispositif a été clotûré suite au règlement.'),
			'error' => __('Impossible de clotûrer le dossier suite au règlement pour des raisons techniques.')
		],$messages);

		$this->autoRender = false;

		if($security){

			$referer = $this->referer();
			$created = Router::url(['controller' => 'demandes', 'action' => 'view' , $id],true);

			if($referer != $created || ! $this->request->is(['patch', 'post', 'put'])){
				$this->Flash->error($messages['referer']);
				return $this->redirect(['controller' => 'users', 'action' => 'logout']);
			}
		}

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats','Organisateurs', 'Dimensionnements.Dispositifs.Equipes']
        ]);

		if(!empty($necessite)){
			if($demande->config_etat->ordre != $necessite){
				$this->Flash->error($messages['step']);
				return $this->redirect(['controller' => 'demandes', 'action' => 'view',$demande->id]);
			}
		}

		$etat_id = $this->Demandes->ConfigEtats->alone( $souhaite );
		$etat_id = (int) key($etat_id);

		$demande->set('config_etat_id',$etat_id);

		if($this->Demandes->save($demande)){

			$this->Flash->success($messages['success']);
			return true;

		}

		$this->Flash->error($messages['error']);
		return false;

	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    protected function _traitementMails($parametres=[],$demande=[],$type='mail-etude') {

		$this->autoRender = false;

		$ordre = $demande->ordre;

		$necessites = [
			2=>'mail-saisie',
			3=>'mail-etude',
			4=>'mail-etude',
			5=>'mail-recrutement',
			6=>'mail-convention',
			7=>'mail-poste',
			10=>'mail-bilan',
			11=>'mail-facture',
			12=>'mail-cloture',
			13=>'mail-annule'
		];

		//$type = isset($necessites[$ordre]) ? $necessites[$ordre] : false;

		$parametres = array_merge([
			'subject' => __('Traitement de votre demande'),
			'message' => __('Vous avez un nouveau document'),
			'replyTo' => ['gabriel.boursier@loire-atlantique.protection-civile.org' => 'Protection Civile de Loire-Atlantique'],
			'from' => ['gabriel.boursier@loire-atlantique.protection-civile.org' => 'Protection Civile de Loire-Atlantique'],
			'to' => ['gabriel.boursier@loire-atlantique.protection-civile.org' => 'Protection Civile des Loire-Atlantique'],
			'cc' => [],
			'bcc' => ['gabriel.boursier@loire-atlantique.protection-civile.org' => 'Protection Civile des Loire-Atlantique'],
			'format' => 'both',
			'attachements' => []
		],$parametres);

		foreach($parametres as $key => $val){
			if($key == 'subject' || $key == 'message'){
				$parametres[$key] = (string) $val;
			} else {
				$parametres[$key] = (array) $val;
			}
		}

		extract($parametres);

		if($type){
			$this->loadModel('Mails');

			$model = $this->Mails->find('all')->where(['type'=>$type])->first();

			$subject = $model->subject;
			$message = $model->message;

			if($demande){
				if(is_object($demande)){
					$demande = $demande->toArray();
				}
				if(isset($demande['dimensionnements'])){
					unset($demande['dimensionnements']);
				}

				$demande_id = $demande['id'];

				$demande = Hash::flatten($demande);

				foreach($demande as $key => $val){
					$message = str_replace('{'.$key.'}',$val,$message);
				}

			}

			$tmp = explode(',',str_replace(' ','',$model->attachments));

			$attachements = array_merge($attachements,$tmp);
			$format = $model->format;

		}

		$bcc[] = 'gabriel.boursier@loire-atlantique.protection-civile.org';

		$email = new Email('default');

		$email->setTransport('smtpGmail')
			->setFrom($from)
			->setTemplate('default','default')
			->setEmailFormat($format)
			->setTo($to)
			->setCc($cc)
			->setBcc($bcc)
			->setAttachments($attachements)
			->setSubject($subject)
			->setReadReceipt($from)
			->setReturnPath($from)
			->setReplyTo($replyTo)
			->send($message);

	}
	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function remise($id = null) {

		$this->autoRender = false;

		$pourcentage = (int) $this->request->getData('pourcentage');
		$justification = trim($this->request->getData('remise_justification'));

		if($pourcentage > 100){
			$pourcentage = 100;
		}

		$referer = $this->referer();
		$created = Router::url(['controller' => 'demandes', 'action' => 'view' , $id],true);

		if($referer != $created || ! $this->request->is(['patch', 'post', 'put'])){
			$this->Flash->error(__('Accès non autorisé, vous essayez de modifier une donnée protégée.'));
			return $this->redirect(['controller' => 'users', 'action' => 'logout']);
		}

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats','Organisateurs', 'Dimensionnements.Dispositifs.Equipes']
        ]);

		if(empty($demande)){
			$this->Flash->error(__('Accès non autorisé, vous essayez de modifier une donnée protégée.'));
			return $this->redirect(['controller' => 'users', 'action' => 'logout']);
		}

		if($demande->config_etat->ordre>4&&$demande->config_etat->ordre!=10){
			$this->Flash->error(__('Impossible de modifier la tarification car l\'étude a été signée.'));
			return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);
		}

		if(! empty($pourcentage) && empty($justification)){
			$this->Flash->error(__('Une adaptation du tarif doit être systématiquement justifiée.'));
			return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);
		}

		$check = Hash::extract($demande , 'dimensionnements.{n}.dispositif.equipes.{n}');

		if(count($check)<=0 ){
			$this->Flash->error(__('Vous devez créer les équipes correspondantes avant de vouloir proposer une remise.'));
			return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);
		}

		$this->loadModel('ConfigParametres');

		$parametres = $this->ConfigParametres->find('all')->last();

		$taux['horaire'] = $parametres->cout_personnel;
		$taux['km'] = $parametres->cout_kilometres;
		$taux['repas'] = $parametres->cout_repas;
		$taux['repartition'] = $parametres->pourcentage;

		$check = Hash::insert($check , '{n}.remise', $pourcentage);

 		foreach($check as &$equipe){
			$equipe['horaires_convocation'] = $equipe['horaires_convocation']->format('Y-m-d H:i:s');
			$equipe['horaires_place'] = $equipe['horaires_place']->format('Y-m-d H:i:s');
			$equipe['horaires_fin'] = $equipe['horaires_fin']->format('Y-m-d H:i:s');
			$equipe['horaires_retour'] = $equipe['horaires_retour']->format('Y-m-d H:i:s');
			$equipe = $this->Demandes->Dimensionnements->Dispositifs->Equipes->calculs($equipe, $taux );
		}

		$check = $this->Demandes->Dimensionnements->Dispositifs->Equipes->patchEntities([],$check);

		if($this->Demandes->Dimensionnements->Dispositifs->Equipes->saveMany($check)){

			$demande->set('remise_justification',$justification);
			$this->Demandes->save($demande);

			$this->Flash->success(__('Le nouveau tarif a été calculé.'));
		} else {
			$this->Flash->error(__('Impossible de calculer le nouveau tarif, essayez d\'éditer le dossier.'));
		}

		return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

    }

    /**
     * Delete method
     *
     * @param string|null $id Demande id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $demande = $this->Demandes->get($id);
        if($this->Demandes->delete($demande)) {
            $this->Flash->success(__('The demande has been deleted.'));
        } else {
            $this->Flash->error(__('The demande could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function wizard()
	{
		$organisateur_id = (int) $this->Wizard->getDatas('Organisateurs.id');
		$demande_id = (int) $this->Wizard->getDatas('Demandes.id');

		if(empty($demande_id)){
			$organisateur = $this->Demandes->Organisateurs->get($organisateur_id);

			$configEtats = $this->Demandes->ConfigEtats->alone();

			$demande = $this->Demandes->newEntity();
			$demande->set('organisateur_id',$organisateur_id);
			$demande->set('representant',$organisateur->representant);
			$demande->set('representant_fonction',$organisateur->fonction);
			$demande->set('gestionnaire_nom',$this->Auth->user('nom'));
			$demande->set('gestionnaire_mail',$this->Auth->user('username'));
			$demande->set('gestionnaire_telephone',$this->Auth->user('telephone'));
			$demande->set('antenne_id',10);
			$demande->set('config_etat_id',$this->Demandes->ConfigEtats->firstid());
			$demande->set('remise_justification','');

		} else {
			$demande = $this->Demandes->get($demande_id);
			$configEtats = $this->Demandes->ConfigEtats->searchid($demande->config_etat_id);
		}

        if($this->request->is(['patch', 'post', 'put'])) {

			$demande = $this->Demandes->patchEntity($demande, $this->request->getData());

			if(empty($demande_id )) {
				$demande->set('remise_justification','');
				$demande->set('organisateur_id',$organisateur_id);
				$demande->set('config_etat_id',$this->Demandes->ConfigEtats->firstid());

				$result = $this->Demandes->save($demande);

				if($result) {
					$this->Flash->success(__('La demande a été sauvegardée.'));
					return $this->redirect(['action'=>'wizard','next',$result->id]);
				}

			} else {
				if($demande->isDirty()) {

					//$demande = $this->Demandes->patchEntity($demande, $this->request->getData());

					$result = $this->Demandes->save($demande);

					if( $result ) {
						$this->Flash->success(__('La demande a été sauvegardée.'));

						//return $this->redirect(['action' => 'index']);
						return $this->redirect(['action'=>'wizard','next',$result->id]);
					}

				} else {
					$this->Flash->error(__('No changes detected. Please, try again or quit by click on list button.'.json_encode($demande->isDirty())));

					return $this->redirect(['action'=>'wizard','next',$result->id]);
				}
			}

			$this->Flash->error(__('The demande could not be saved. Please, try again.'));

		}

        $organisateurs = $this->Demandes->Organisateurs->alone($organisateur_id);
        $antennes = $this->Demandes->Antennes->find('list');

        $navigation = $this->navigation;

        $this->set(compact('demande', 'configEtats', 'antennes', 'organisateurs', 'navigation'));
    }

    public function mailEtude($id = null) {

		$this->autoRender = false;

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		////////////////////////////////////////////////////////////////////

		$etapes = $this->Xtcpdf->getStart();
		$etapes = $this->Xtcpdf->getEtapes($etapes,false);
		$etapes = $this->Xtcpdf->getAttachement($etapes);

		/*
		$mail = [
			'replyTo' => ['operationnel@protectioncivile-vosges.org' => 'Operationnel Protection Civile des Vosges'],
			'from' => [$demande->gestionnaire_mail => 'Protection Civile - Vosges - '.$demande->gestionnaire_nom],
			'to' => ['president@protectioncivile-vosges.org' => 'Pres Protection Civile des Vosges'],
			'cc' => ['applications@protectioncivile-vosges.org' => 'App Protection Civile des Vosges'],
			'bcc' => ['jcroussel88@orange.fr' => 'JC Protection Civile des Vosges'],
			'attachements' => [
								'etude.pdf' => ['data' => $file, 'mimetype' => 'application/pdf'],
								'explication_processus.pdf' => ['data' => $etapes, 'mimetype' => 'application/pdf']
							]
		];
		*/

		//$this->_traitementMails($mail,$demande,'mail-etude');

		/*
		$this->loadModel('Mails');

		$model = $this->Mails->find('all',['where'=>['type'=>'aftersave']])->first();

		$model->attachments = explode(',',$model->attachments);

		$email = new Email('default');
		$email->setFrom(['president@protectioncivile-vosges.org' => 'Protection Civile des Vosges'])
			->setTo(['jcroussel88@orange.fr','president@protectioncivile-vosges.org'])
			->setAttachments(['etude.pdf' => ['data' => $file, 'mimetype' => 'application/pdf']])
			->setSubject($model->subject)
			->send($model->message);
		*/

		//$this->Flash->success(__('Processus d\'envoi du mail d\'étude.'));
		//return $this->redirect(['controller' => 'demandes', 'action' => 'view',$id]);

    }

    public function etude($id = null) {

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

        $this->set('demande', $demande);

        $this->viewBuilder()->setLayout('ajax');
		$this->set('title', 'My Great Title');
        $this->set('file_name', '2016-06' . '_June_CLM.pdf');
		$this->response->type('pdf');

    }

	public function convention($id = null) {

		$this->loadModel('ConfigConventions');

		$convention = $this->ConfigConventions->find('all',['order'=>['ordre'=>'asc']]);

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$array = $this->Demandes->get($id,[
            'contain' => ['Organisateurs']
        ])->toArray();

		$array = $this->ArraySum->mergeLast( $array );

		//Number::config('fr_FR', \NumberFormatter::SPELLOUT);
		$array['total_cout_total'] = Number::format( $demande->total_cout );

		$flatten = Hash::flatten($array);
		$flatten['count::dimensionnements'] = count( $demande->dimensionnements );

		$flat_keys = array_keys($flatten);
		$flat_vals = array_values($flatten);
		$flat_keys = Hash::format($flat_keys, ['{n}'], '{{%1$s}}');
		$flatten = array_combine($flat_keys,$flat_vals);

		foreach( $convention as $item ):
			$item->description = str_replace(array_keys($flatten),$flatten,$item->description );
		endforeach;

        $this->set('demande', $demande);
		$this->set('convention', $convention);
		$this->set('flatten', $flatten);

        $this->viewBuilder()->setLayout('ajax');
		$this->set('title', 'My Great Title');
        $this->set('file_name', '2016-06' . '_June_CLM.pdf');
        $this->response->type('pdf');
    }

	public function facture($id = null) {

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$demande['total_cout_total'] = Number::format( $demande->total_cout );

        $this->set('demande', $demande);

        //$this->set('demande', $demande);
		//$this->set('convention', $convention);
		//$this->set('flatten', $flatten);

        $this->viewBuilder()->setLayout('ajax');
		$this->set('title', 'My Great Title');
        $this->set('file_name', '2016-06' . '_June_CLM.pdf');
        $this->response->type('pdf');
    }

	public function mission($id = null) {

		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$demande['total_cout_total'] = Number::format( $demande->total_cout );

        $this->set('demande', $demande);

		$this->viewBuilder()->setLayout('default1');

        $this->viewBuilder()->setLayout('ajax');
		$this->set('title', 'My Great Title');
        $this->set('file_name', '2016-06' . '_June_CLM.pdf');
        $this->response->type('pdf');

    }

	public function recrutement() {

		$demandes = $this->Demandes->find('all',[
			'contain' => [	'ConfigEtats',
							'Organisateurs',
							'Dimensionnements.Dispositifs.Equipes',
							'Antennes'],
		])
		->where(['ConfigEtats.ordre >='=>0,'ConfigEtats.ordre <='=>5])
		->order(['config_etat_id'=>'asc'])
		->mapReduce( $this->ArraySum->getMapper() , $this->ArraySum->getReduce());

		//var_dump($demandes->toArray());

		$demandes = $demandes->toArray();

		$demandes = Hash::sort($demandes,'{n}.dates_limits.round_min','asc');

		$this->set('demandes', $demandes);

		/*
		$this->ArraySum->setConfig($this->arraysum);

        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$demande['total_cout_total'] = Number::format( $demande->total_cout );

        $this->set('demande', $demande);
*/
		$this->viewBuilder()->setLayout('default1');

        $this->viewBuilder()->setLayout('ajax');
		$this->set('title', 'My Great Title');
        $this->set('file_name', 'recrutement_' . '_June_CLM.pdf');
		$this->response->type('pdf');

    }

	public function planning($id = null) {

		$this->autoRender = false;

		$this->ArraySum->setConfig($this->arraysum);

		$demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);
		$demande['total_cout_total'] = Number::format( $demande->total_cout );

		$file = $this->Xtcpdf->getStart('L','MM','A3');
		$file = $this->Xtcpdf->getPlanning($demande,$file);
		$file = $this->Xtcpdf->getAttachement($file);

		$this->response->body($file);

        $this->viewBuilder()->setLayout('ajax');
		$this->set('title', 'My Great Title');
        $this->set('file_name', '2016-06' . '_June_CLM.pdf');

        $this->response->type('pdf');

    }

    /**
     * Delete method
     *
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function duplicate($id = null)
    {
		$this->autoRender = false;
        //$this->request->allowMethod(['post', 'delete']);

		if($this->Equipes->duplicate($id)) {
			$this->Flash->success(__('The equipe has been duplicate.'));
        } else {
            $this->Flash->error(__('The equipe could not be duplicate. Please, try again.'));
        }

       return $this->redirect(['action' => 'index']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function cleaning() {

		$this->autoRender = false;

		$demandes = $this->Demandes->find('all',['contain'=>['Dimensionnements.Dispositifs.Equipes','ConfigEtats']]);

		foreach($demandes as $demande){
			if(empty($demande->dimensionnements)){
				$this->Demandes->delete($demande);
			}
			if(empty($demande->config_etat)){
				$this->Demandes->delete($demande);
			}
		}

		$dimensionnements = $this->Demandes->Dimensionnements->find('all',['contain'=>['Dispositifs.Equipes','Demandes']]);

		foreach($dimensionnements as $dimensionnement){
			if(empty($dimensionnement->demande)){
				$this->Demandes->Dimensionnements->delete($dimensionnement);
			}
		}

		$dispositifs = $this->Demandes->Dimensionnements->Dispositifs->find('all',['contain'=>['Equipes','Dimensionnements.Demandes']]);

		foreach($dispositifs as $dispositif){
			if(empty($dispositif->dimensionnement)){
				var_dump($dispositif->dimensionnement);
			}
		}

		$equipes = $this->Demandes->Dimensionnements->Dispositifs->Equipes->find('all',['contain'=>['Dispositifs.Dimensionnements']]);

		foreach($equipes as $equipe){
			if(empty($equipe->dispositif)){
				var_dump($equipe->dispositif);
			}
		}

		return $this->redirect(['action' => 'index']);
    }

	public function grille($id = null) {

		$this->autoRender = false;

		$demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats', 'Organisateurs', 'Dimensionnements.Dispositifs.Equipes', 'Antennes']
        ]);

		$demande = $this->ArraySum->somme($demande);

		$file = $this->Xtcpdf->getStart('L','MM','A3');
		$file = $this->Xtcpdf->getPlanning($demande,$file);
		$file = $this->Xtcpdf->getAttachement($file);

		$this->response->body($file);

        $this->viewBuilder()->setLayout('ajax');
		$this->set('title', 'My Great Title');
        $this->set('file_name', '2016-06' . '_June_CLM.pdf');

        $this->response->type('pdf');
	}

}
