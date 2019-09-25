<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Http\Session;
use Cake\Mailer\Email;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;
use Cake\I18n\Time;
use Cake\I18n\FrozenTime;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

	public $smtp_mails = ['smtpGmail'];

	public $helpers = [
		'Form' => [
			'className' => 'Bootstrap.Form',
			'widgets' => [
				'datetimepicking' => ['DateTimePicking']
			]
		],
		'Html' => [
			'className' => 'Bootstrap.Html'
		],
		'Modal' => [
			'className' => 'Bootstrap.Modal'
		],
		'Navbar' => [
			'className' => 'Bootstrap.Navbar'
		],
		'Paginator' => [
			'className' => 'Bootstrap.Paginator'
		],
		'Panel' => [
			'className' => 'Bootstrap.Panel'
		],
		'ProgressGantt',
		'TcpdfGantt',
		'PdfGantt',
		'GoogleMap',
		'OpenLayers',
		'Breadcrumbs'
	];

	private $cron_key = '';

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

		$this->loadComponent('Auth');
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        $this->loadComponent('Flash');
		$this->loadComponent('ArraySum');
		$this->loadComponent('Wizard');
		$this->loadComponent('Xtcpdf');
		$this->loadComponent('Pourcentages');

		$session = new Session();

		$action = $this->request->getParam('action');
		$controller = $this->request->getParam('controller');

		$this->Auth->allow(['backup','relances']);

		if($this->Auth->user()){

			$externe = (int) $this->Auth->user('externe');

			if(!empty($externe)){
				$this->Flash->error(__('Nous êtes une personne étrangère à la Protection Civile et donc ne pouvez accéder à cette interface.'));
				$this->redirect(['controller'=>'users','action'=>'logout']);
			}

			$this->Auth->allow(['*']);

		}

		if( $action != 'ajax' && $action != 'login' && $action != 'logout' ){

			$this->loadModel('ConfigLogs');

			$config = $this->ConfigLogs->newEntity();

			$config = $this->ConfigLogs->patchEntity($config,[
				'controller' => $this->request->getParam('controller'),
				'action' => $this->request->getParam('action'),
				'params' => json_encode($this->request->getParam('pass')),
				'request' => json_encode($this->request->getData()),
				'ip' => $this->request->clientIp(),
				'user' => $this->Auth->user('nom').' - '.$this->Auth->user('username'),
				'date' => date('Y-m-d H:i:s')
			]);

			$this->ConfigLogs->save( $config );
		}

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
		set_time_limit(0);

    }

	public function backup(){

		$this->autoRender = false;

		$uuid = 'gestiondps_'.date('Ymdhis').'_'.uniqid();

		$folder = new Folder();
		$folder->create(TMP.'backup');

		$path = TMP.'backup'.DS.$uuid;

		$source = ConnectionManager::get('default');
		$source = $source->config();

		exec('mysqldump --user='.$source['username'].' --password='.$source['password'].' --host='.$source['host'].' '.$source['database'].' | gzip > '.$path.'.sql.gz');

		unset($source);

		$email = new Email('default');

		$email->setTransport($this->smtp_mails[0])
			->setFrom(['gabriel.boursier@loire-atlantique.protection-civile.org'=>'Gabriel Boursier'])
			->setTemplate('default','default')
			->setEmailFormat('both')
			->setTo('applications@loire-atlantique.protection-civile.org')
			->setAttachments($path.'.sql.gz')
			->setSubject('Sauvegarde BDD Gestion opérationnel'.date('Y-m-d H:i:s'))
			->setReadReceipt('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setReturnPath('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setReplyTo('gabriel.boursier@loire-atlantique.protection-civile.org')
			->send('Sauvegarde de la BDD opérationnelle au '.date('Y-m-d H:i:s'));

		$file = new File($path.'.sql.gz');
		$file->delete();
		$file->close();

		$this->_traitementAutomatises([
			'postes_realises'
		]);

		echo '';

	}

	public function relances(){

		$this->autoRender = false;

		$this->_traitementAutomatises([
			'postes_realises',
			'relances_etudes'
		]);
	}

	private function _relancesEquipeOperationnelle(){

		$this->autoRender = false;

		$this->loadModel('Demandes');

		$demandes = $this->Demandes->listeMiniMaxi(0,3);

		$gestionnaires = Hash::extract($demandes,'{n}.gestionnaire_mail');
		$gestionnaires = array_unique($gestionnaires);
		$gestionnaires[] = 'applications@loire-atlantique.protection-civile.org';

		$count = count($demandes);

		$email = new Email('default');

		$email->setTransport('smtpGmail')
			->setFrom(['gabriel.boursier@loire-atlantique.protection-civile.org'=>'Gabriel Boursier'])
			->setTemplate('tableaux','default')
			->setEmailFormat('html')
			->helpers($this->helpers)
			->viewVars(['demandes'=>$demandes])
			->setTo($gestionnaires)
			->setSubject('[Relance] '.$count.' organisateurs attendent une étude !')
			->setReadReceipt('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setReturnPath('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setReplyTo('gabriel.boursier@loire-atlantique.protection-civile.org')
			->send('Sauvegarde de la BDD opérationnelle au '.date('Y-m-d H:i:s'));

	}

	public function test(){

		$this->autoRender = false;

		ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
		$client = new \SoapClient("https://franceprotectioncivile.org/soap.php?wsdl"); // I'm using proxy to connect Internet from my LAN
		try {
			//var_dump($client->getSections('e2d34650cfc1115c9d2adb9c58050362'));
			$events = $client->getEvents('e2d34650cfc1115c9d2adb9c58050362');
			$events = Hash::sort($events,'{n}.date_debut','asc');
			var_dump($events);
			//var_dump($client->getUserInfo('e2d34650cfc1115c9d2adb9c58050362','JCROUSSEL','celine67'));
		} catch (SoapFault $exception) {
			echo $exception;
		}
		var_dump($exception);

		/*
		$this->autoRender = false;

		//

		$this->loadModel('Demandes');

		$demandes = $this->Demandes->listeMiniMaxi(5,5);

		foreach($demandes as $demande){
			var_dump($demande);
		}
		*/
	}

	private function _traitementAutomatises($types = []){

		$this->autoRender = false;

		$this->loadModel('Demandes');

		$this->loadComponent('GestionDemandes');

		$types = (array) $types;

		if( in_array('postes_realises',$types)){

			$demandes = $this->Demandes->listeMiniMaxiBeforeToday(7,7);

			$demandes_ids = (array) Hash::extract($demandes,'{n}.id');

			if(isset($demande_ids)){
				if(!count($demande_ids)){
					$this->Demandes->modifyEtatByDemandesId($demandes_ids,8);
				}
			}

		}

		if( in_array('relances_etudes',$types)){
			//if( date('w') == 3 ){

				$this->loadModel('Demandes');

				$demandes = $this->Demandes->listeMiniMaxiAll(4,4);

				foreach($demandes as $demande){

					$strtotime = strtotime(date('Y-m-d H:i:00',$demande->dates_limits['min']).' -2 week');

					if($strtotime <= strtotime(date('Y-m-d'))){

						$this->GestionDemandes->_traitementRelanceEtude($demande,'mail-etude-relance');
					}
				}
			//}
		}

	}

}
