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
use Cake\Controller\Component\Response;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Inflector;
use Cake\Utility\Hash;
use Cake\I18n\Number;
use Cake\Http\Session;
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
class WizardComponent extends Component
{

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
		1=>['label'=>'Organisateur',
			'controller'=>'Organisateurs',
			'url'=>['controller'=>'organisateurs','action'=>'wizard'],
			'key'=>'Organisateurs.id',
			'need'=>false],
		2=>['label'=>'Demande',
			'controller'=>'Demandes',
			'url'=>['controller'=>'demandes','action'=>'wizard'],
			'key'=>'Demandes.id',
			'need'=>'Organisateurs.id'],
		3=>['label'=>'Saisir déclarations',
			'controller'=>'Dimensionnements',
			'url'=>['controller'=>'dimensionnements','action'=>'wizard'],
			'key'=>'Dimensionnements.id',
			'need'=>'Demandes.id'],
		4=>['label'=>'Dispositifs',
			'controller'=>'Dispositifs',
			'url'=>['controller'=>'dispositifs','action'=>'wizard'],
			'key'=>'Dispositifs.id',
			'need'=>'Demandes.id'],
		5=>['label'=>'Equipes',
			'controller'=>'Equipes',
			'url'=>['controller'=>'equipes','action'=>'wizard'],
			'key'=>'Equipes.id',
			'need'=>'Demande.id'],
		6=>['label'=>'Terminer',
			'controller'=>'Demandes',
			'url'=>['controller'=>'demandes','action'=>'view'],
			'key'=>'Demandes.id',
			'need'=>false,
			'end'=>true]
    ];
	
    /**
     * Datasource paginator instance.
     *
     * @var \Cake\Datasource\Paginator
     */
    protected $_base = 'Wizards';
	
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
	 * Permet de charger l'instance Session
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
		$this->Session = $this->_getSession();
		
        parent::__construct($registry, $config);
		
		$this->ActiveController = $this->_getController();

    }
	
    /**
     * Controller redirect.
     *
     * Before
     */
	function beforeFilter(Event $event)
	{
		$config = $this->getConfig();
		$action = $this->request->getParam('action');
		$config = array_keys($config);

		if( $action == 'wizard'){
			
			$step = ['next','previous','quit','active'];
			$pass = $this->request->getParam('pass.0');
			$data = $this->request->getParam('pass.1');
			$data = explode('__',$data);
			
			if(!in_array($pass,$step)){
				$pass = (int) $pass;
				if(in_array($pass,$config)){
					$this->request->params = Hash::insert($this->request->params,'pass.0',$pass);
					if( count($data) == 2 ){
						$model = $data[0];
						$value = $data[1];
						
						$model = TableRegistry::get($model);
						$output = $model->wizard($value);
						$this->request->params = Hash::insert($this->request->params,'pass.1',$output);
					}					
				}
			}
		}

		if( $this->_getExist() ){
			//$this->redirect();
			//var_dump('tagada');	
		}
		//$event->stopPropagation();
	}
	
    /**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	function beforeRender(Event $event)
	{

		$this->ActiveController->set('wizard',false);
		
		if( $this->_getExist() ){
			$this->ActiveController->set('wizard',$this->getConfig());
			$this->ActiveController->set('wizard_step',$this->Session->read('Wizard.step'));	
		}
	}

    /**
     * Process.
     *
     * Est l'instance appelée depuis votre controller
	 * @var process permet de faire avancer, reculer, créer si n'existe pas, quitter le process lorsque le procédé est terminé
     */	
	public function process( $process = false , $id = false )
	{
		$this->_processStart();
		
		$this->_getExistController();
		
		$this->ActiveController->layout = 'default';
		
		//var_dump($this->Session->read('Wizard'));
		
		if( is_int( $process ) ){
			$step = (int) $process;
			$process = 'force';
		}

		switch( $process ){
			case 'next':
				$this->_processNext( $id );
				break;
			case 'previous':
				$this->_processPrevious();
				break;
			case 'quit':
				$this->_processQuit(true);
				break;
			case 'active':
				$this->_processStart();
				break;
			case 'force':
				$this->_processForce($step,$id);
				break;
			default:
				break;
		}

		$end = (boolean) $this->Session->read('Wizard.configuration.end');
		
		if( $end ){
			$this->_processQuit();
		}
		
		// $need[0] = $this->_getExistNeeded();
		// $need[1] = $this->_getExistCreated();
			
		$this->ActiveController->layout = 'default';

		//return $need;
	}
	
    /**
     * Activation du process.
     *
     */	
	public function _processStart()
	{
		$this->ActiveController->layout = 'default';

		if( ! $this->_getExist() ){
			$this->_write();
		}

	}
	
    /**
     * Quitter le process.
     *
     */
	public function _processQuit( $force = false )
	{
		if( $force ){
			$array = $this->getConfig();
			
			end($array);
			$key = key($array);
			
			$this->_write( $key );			
		}

		$end = (boolean) $this->Session->read('Wizard.configuration.end');
		$url = $this->Session->read('Wizard.configuration.url');
		$key = $this->Session->read('Wizard.configuration.key');
		$val = $this->Session->read('Wizard.datas.'.$key);
		
		if( ! $url ){
			$url = $this->Session->read('Wizard.configuration.redirect');
		}

		if( $end ){
			if( ! empty($val)){
				if(is_array($url)){
					$url[] = $val;
				} else {
					$url .= '/'.$val;
				}
			} else {
				$url = $this->Session->read('Wizard.configuration.redirect');
			}
			
		}
		
		$this->Session->delete('Wizard');
		$this->redirect( $url , true );
					
		$this->ActiveController->layout = 'default';
	}

    /**
     * Reculer dans le process.
     *
     */
	protected function _processForce($step=0,$datas=[])
	{
		$this->ActiveController->layout = 'default';
		
		$datas = (array) $datas;

		// Forcer l'étape
		$this->_write($step);
		
		// Précharger data
		$configs = $this->getConfig();
		$extract_key = Hash::extract( $configs , '{n}.key' );
		$extract_need = Hash::extract( $configs , '{n}.need' );
		
		$extract = array_merge($extract_key,$extract_need);
		$extract = array_unique($extract);

		foreach($datas as $data_key => $data_val){
			if( in_array($data_key,$extract)){
				$this->Session->write('Wizard.datas.'.$data_key,$data_val);
			}
		}

	}
	
    /**
     * Reculer dans le process.
     *
     */
	protected function _processPrevious()
	{
		$step = (int) $this->Session->read('Wizard.step');
		
		if( ! empty($step)){
			$step--;
			$this->_write($step);
			
			$this->redirect();
		}		
	}
	
    /**
     * Avancer dans le process.
     *
	 * Détermine la valeur de l'étape du wizard
     */
	protected function _processNext( $value = false )
	{
		$step = (int) $this->Session->read('Wizard.step');

		if( ! empty($step) ){
			$this->_setKeyWizard( $value );
			
			$step++;
			$this->_write($step);
			
			$this->redirect();
		}

	}

    /**
     * Crée la valeur wizard à stocker.
     *
     */
	protected function _setKeyWizard($value = false)
	{
		if( $value ){
			$key = $this->Session->read('Wizard.configuration.key');
			$this->Session->write( 'Wizard.datas.'.$key , $value );
		}
	}

    /**
     * Reculer dans le process.
     *
     */
	public function _presetKeyWizard($step = 1, $value = false)
	{
		if( ! $this->_getExist() ){
			
			$step = (int) $step;
			
			$configs = $this->getConfig();
			$configs = array_keys($configs);

			if( $step < 1 ){
				$step = 1;
			}
			
			if( ! in_array($step,$configs) ){
				$step = 1;
			}

			$config = $this->getConfig( $step );
			$config = $this->_merge( $config );
			
			$key = Hash::extract('url');
			
			$this->Session->write( 'Wizard.datas.'.$key , $value );

		}

	}
	
    /**
     * Controller redirect.
     *
     * Permet de remplacer les redirect classique pour garantir le rechargement sur la page wizard déterminée
     */
	public function redirect($url = [], $force = false )
	{
		if( ! $force ){
			$url = $this->Session->read('Wizard.configuration.url');
		}
		
		if( empty( $url ) ){
			$url = ['/'];
		}
		var_dump( $url );
		$this->ActiveController->layout = 'default';
	
		return $this->ActiveController->redirect( $url );

		// $controller = $this->_getController();
		// return $controller->redirect( $url );
		
	}
	
    /**
     * Retourne la valeur stockée dans l'étape précédente.
     *
     */
	protected function loadNeeded()
	{
		$need = $this->Session->read('Wizard.configuration.need');
		$val = $this->Session->read('Wizard.datas.'.$need);
	
		return $val;
	}
	
    /**
     * Retourne la valeur stockée dans l'étape précédente.
     *
     */
	public function loadExist()
	{
		$need = $this->Session->read('Wizard.configuration.key');
		$val = $this->Session->read('Wizard.datas.'.$need);

		return $val;
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
	
    /**
     * Controller redirect.
     *
     * Créer une nouvelle instance Session
     */
	protected function _getSession()
	{
		return new Session();
	}
	
    /**
     * Test pour voir si le process est déjà commencé.
     *
     */
	protected function _getExist()
	{
		
		$test1 = $this->Session->read('Wizard.step');
		$test2 = $this->Session->read('Wizard.configuration');
		
		if( !$test1 || !$test2 ){
			return false;
		}
		
		return true;
	}
	
    /**
     * Test pour voir si le process est déjà commencé.
     *
     */
	protected function _getExistController()
	{
		if($this->_getExist()){
			$controller_session = $this->Session->read('Wizard.configuration.controller');
			$controller_actif = $this->_getController()->getName();
			$controller_session = Inflector::camelize($controller_session);

			if( $controller_session != $controller_actif ){
				//var_dump('redirect');
				return $this->redirect();
			}
		}
	}

	/**
     * Ecrire l'instance wizard en cours.
     *
     * Cherche l'occurence  et la stocke dans la session active
     */
	public function _getExistNeeded()
	{
		$need = 0;
		
		if( $this->_getExist()){
			
			$step = $this->Session->read('Wizard.step');
			
			if( $step > 1 ){

				$need = $this->loadNeeded();

				if( empty( $need ) ){
					$this->_processPrevious();
					return false;
				}
				
				$path = $this->Session->read('Wizard.configuration.need');

				$keys = explode( '.', $path );
				
				if(isset($keys[0]) && isset($keys[1])){
					$model 	= $keys[0];
					$key 	= $keys[1];
					
					$model = TableRegistry::get($model);

					$query = $model->find('all')
								   ->select([$key])
								   ->where([$key=>$need])
								   ->first();

					if( empty( $query ) ){
						$this->_processPrevious();
						return false;
					}
				}	
			}
		}
		
		return $need;
	}
	
	public function _getExistCreatedBis($key='')
	{
		return $this->Session->read('Wizard.datas.'.$key);
	}
	/**
     * Ecrire l'instance wizard en cours.
     *
     * Cherche l'occurence  et la stocke dans la session active
     */
	public function _getExistCreated()
	{
		$controller_session = $this->Session->read('Wizard.configuration.controller');
		$controller_actif = $this->_getController()->getName();
		$controller_session = Inflector::camelize($controller_session);

		if( $controller_session != $controller_actif ){
			return $this->redirect();
		}
		
		$need = 0;
		
		if( $this->_getExist()){

			$step = $this->Session->read('Wizard.step');
			
			if( $step > 1 ){

				$need = $this->loadExist();

				if( empty( $need ) ){
					return 0;
				}

				$path = $this->Session->read('Wizard.configuration.key');

				$keys = explode( '.', $path );
				
				if(isset($keys[0]) && isset($keys[1])){
					$model 	= $keys[0];
					$key 	= $keys[1];
					
					$model = TableRegistry::get($model);
					
					$query = $model->find('all')
								   ->select([$key])
								   ->where([$key.' IN'=>(array)$need])
								   ->toArray();

					if( empty( $query ) ){
						return 0;
					}
				}	
			}
		}
		
		return $need;
	}	
    /**
     * Merge en array.
     *
     * Sécurité pour avoir les bonnes clés dans le array de config
     */
	protected function _merge( $config )
	{
		$config = (array) $config;
		
		$array = [
				'label' => 'First step',
				'url' => '/',
				'controller'=>'Pages',
				'key' => false,
				'need' => false,
				'end'=> false,
				'redirect' => ['action'=>'index']
			];
		
		return array_merge( $array , $config );
		
	}

	/**
     * Ecrire l'instance wizard en cours.
     *
     * Cherche l'occurence  et la stocke dans la session active
     */
	protected function _write( $step = 0 )
	{
		$step = (int) $step;
		
		$configs = $this->getConfig();
		$configs = array_keys($configs);

		if( $step < 1 ){
			$step = 1;
		}
		
		if( ! in_array($step,$configs) ){
			$step = 1;
		}

		$config = $this->getConfig( $step );
		$config = $this->_merge( $config );
		
		$this->Session->write('Wizard.step', $step );
		$this->Session->write('Wizard.configuration',$config);
	}
}
