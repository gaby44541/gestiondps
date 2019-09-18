<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Inflector;
use Cake\Utility\Hash;
use Cake\I18n\Number;
use Cake\Http\Session;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Log\Log;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * This component is used to handle automatic model data pagination. The primary way to use this
 * component is to call the paginate() method. There is a convenience wrapper on Controller as well.
 *
 * ### Configuring pagination
 *
 * You configure pagination when calling paginate(). See that method for more details.
 *
 * @link https://book.cakephp.org/3.0/en/controllers/components/pagination.html
 */
class GestionDemandesComponent extends Component
{
	public $components = ['ArraySum','Xtcpdf'];

    /**
     * Default pagination settings.
     *
     * When calling paginate() these settings will be merged with the configuration
     * you provide.
     *
     * - `maxLimit` - The maximum limit users can choose to view. Defaults to 100
     * - `limit` - The initial number of items per page. Defaults to 20.
     * - `page` - The starting page, defaults to 1.
     * - `whitelist` - A list of parameters users are allowed to set using request
     *   parameters. Modifying this list will allow users to have more influence
     *   over pagination, be careful with what you permit.
     *
     * @var array
     */
    protected $_defaultConfig = [
        	'arraysum' => [
				'paths'=>[
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
					'total_vehicules'=>'dimensionnements.{n}.dispositif.equipes.{n}.vehicule_type',
					'total_lota'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_a',
					'total_lotb'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_b',
					'total_lotc'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_c',
				]
			],
			'mails_cutom'=>[],
			'mails_default'=>[
				'referer' => 'Accès non autorisé, vous essayez de modifier une donnée protégée.',
				'step' => 'Accès non autorisé, vous essayez de modifier une donnée protégée.',
				'success' => 'Vous allez passer à l\'étape suivante.',
				'error' => 'Impossible passer à l\'étape d\'après pour des raisons techniques.'
			],
			'mails' => [
				'subject' => 'Traitement de votre demande',
				'message' => 'Vous avez un nouveau document',
				'replyTo' => ['gabriel.boursier@loire-atlantique.protection-civile.org' => 'Gabriel Boursier'],
				'from' => ['gabriel.boursier@loire-atlantique.protection-civile.org' => 'Gabriel Boursier'],
				'to' => [],
				'cc' => [],
				'bcc' => [
							'gabriel.boursier@loire-atlantique.protection-civile.org' => 'Gabriel Boursier',
							'gabriel.boursier@loire-atlantique.protection-civile.org' => 'Gabriel Boursier'
						],
				'format' => 'both',
				'attachements' => []
			]
    ];

    /**
     * Datasource paginator instance.
     *
     * @var \Cake\Datasource\Paginator
     */
    protected $_array = [];

	   /**
     * Constructor
     *
     * @param \Cake\Controller\ComponentRegistry $registry A ComponentRegistry for this component
     * @param array $config Array of config.
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);
		
		$this->ActiveController = $this->_getController();
		
		$this->Demandes = TableRegistry::get('Demandes');
		$this->Mails = TableRegistry::get('Mails');
		
		$this->arraysum = $this->getConfig();

    }

    /**
     * Controller redirect.
     *
     * Retourne la classe controller active
     */
	protected function _getController()
	{
		return $this->_registry->getController();
	}
	
	public function getMapper(){
		
		$this->config = $this->getConfig();
		$this->ActiveController->autoRender = false;
		
		var_dump($this->ActiveController->referer());
		
		//return $this->ActiveController->redirect(['controller' => 'users', 'action' => 'logout']);
		
	}
	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function _traitementSteps($id = null , $souhaite = 0 , $necessite = 0 , $messages = []) {
		
		$this->config = $this->getConfig();
		
		$messages = array_merge( $this->config['mails_default'] , $messages );
		
		$this->ActiveController->autoRender = false;
		
		$referer = $this->ActiveController->referer();
		$created = Router::url(['controller' => 'demandes', 'action' => 'view' , $id],true);
		$created = Router::url(['controller' => 'users', 'action' => 'test'],true);

		if($referer != $created || ! $this->request->is(['patch', 'post', 'put'])){
			$this->ActiveController->Flash->error($created);
			return $this->ActiveController->redirect(['controller' => 'users', 'action' => 'index']);	
		}
		
        $demande = $this->Demandes->get($id, [
            'contain' => ['ConfigEtats','Organisateurs', 'Dimensionnements.Dispositifs.Equipes']
        ]);		
		
		if(!empty($necessite)){
			if($demande->config_etat->ordre != $necessite){
				$this->ActiveController->Flash->error($messages['step']);
				return $this->ActiveController->redirect(['controller' => 'demandes', 'action' => 'view',$demande->id]);	
			}			
		}

		$etat_id = $this->Demandes->ConfigEtats->alone( $souhaite );
		$etat_id = (int) key($etat_id);
		
		$demande->set('config_etat_id',$etat_id);
		/*
		if($this->Demandes->save($demande)){
			
			$this->ActiveController->Flash->success($messages['success']);	
			return true;
			
		}*/
		
		$this->ActiveController->Flash->error($messages['error']);	
		return false;
		
	}

	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function _setConfig($key=false,$value=[]) {
		
		$this->setConfig($key,$value);

	}
	
	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function _traitementMails($parametres=[],$demande=[],$type='mail-etude') {
		
		// Procéder à une vérification ordre corresponds à mail avant ?
		
		$mails = (array) $this->getConfig('mails');
		
		$parametres = array_merge($mails,$parametres);
		
		foreach($parametres as $key => $val){
			if($key == 'subject' || $key == 'message'){
				$parametres[$key] = (string) $val;
			} else {
				$parametres[$key] = (array) $val;
			}
		}
		
		extract($parametres);

		if($type){

			$model = $this->Mails->find('all')->where(['type'=>$type])->first();
			
			if( $model ){
					
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
					
				$email->setTransport('smtpOrange')
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
		}

	}
	
	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function _traitementRelanceEtude($demande=[],$type='mail-etude-relance') {

		$this->ArraySum->setConfig($this->arraysum);

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
					
		$this->_traitementMails($mail,$demande,$type);
		
	}
}
