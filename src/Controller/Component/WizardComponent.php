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
	// use Cake\Log\Log;
	// Log::write('debug','variable');
	
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
			'onload'=>['Organisateurs.id'],
			'save'=>'Organisateurs.id',
			'navigate'=>false],
		2=>['label'=>'Demande',
			'controller'=>'Demandes',
			'url'=>['controller'=>'demandes','action'=>'wizard'],
			'onload'=>['Demandes.id','Organisateurs.id'],
			'redirect'=>['index','view'],
			'save'=>'Demandes.id',
			'navigate'=>true],
		3=>['label'=>'Saisir déclarations',
			'controller'=>'Dimensionnements',
			'url'=>['controller'=>'dimensionnements','action'=>'wizard'],
			'onload'=>['Demandes.id','Dimensionnements.id'],
			'redirect'=>['view','index'],
			'save'=>'Dimensionnements.id',
			'navigate'=>true],
		4=>['label'=>'Dispositifs',
			'controller'=>'Dispositifs',
			'url'=>['controller'=>'dispositifs','action'=>'wizard'],
			'onload'=>['Demandes.id','Dispositifs.id'],
			'save'=>'Dispositifs.id',
			'redirect'=>['index','view'],
			'navigate'=>true],
		5=>['label'=>'Equipes',
			'controller'=>'Equipes',
			'url'=>['controller'=>'equipes','action'=>'wizard'],
			'onload'=>['Demandes.id','Equipes.id'],
			'save'=>'Equipes.id',
			'redirect'=>['index','view'],
			'navigate'=>true],
		6=>['label'=>'Terminer',
			'controller'=>'Demandes',
			'url'=>['controller'=>'demandes','action'=>'view'],
			'alt'=>['controller'=>'pages','action'=>'display','accueil'],
			'onload'=>['Demandes.id'],
			'save'=>'Demandes.id',
			'navigate'=>true,
			'get'=>true,
			'end'=>true]
    ];
    
	/**
     * Datasource paginator instance.
     *
     * @var \Cake\Datasource\Paginator
     */
    protected $_dirty = false;
    
	/**
     * Datasource paginator instance.
     *
     * @var \Cake\Datasource\Paginator
     */
    protected $_active = false;
	
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
    function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);
		
		$this->Session = $this->_getSession();
		$this->ActiveController = $this->_getController();
    }
	
    /**
     * Controller redirect.
     *
     * Before
     */
	function beforeFilter(Event $event)
	{

		$action = $this->request->getParam('action');
		
		$active = $this->Session->read('Wizard.active');

		if( $action == 'wizard'){
				
			if( ! $active ){
				$this->active();
			}

			$step = ['next','previous','quit','active'];
			$pass = $this->request->getParam('pass.0');
			$data = $this->request->getParam('pass.1');

			if(!in_array($pass,$step)){ 
				$pass = (int) $pass;
				$data = explode('__',$data);
				$model = false;
				$value = false;
				if( count($data) == 2 ){
					$model = $data[0];
					$value = $data[1];
				}
				$this->push($pass,$model,$value);
			} else {
				if(!empty($data)){
					$this->setData($data);
				}
				$this->{$pass}();
			}
			
			$limit = (int) $this->verifyRequireds();
			$actif = (int) $this->Session->read('Wizard.step');
	
			if($limit<$actif){
				$this->push($limit);
			}

			$this->reload();
		}

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
		
		$active = $this->Session->read('Wizard.active');
		
		if( $active ){
			$this->ActiveController->set('wizard',$this->getConfig());
			$this->ActiveController->set('wizard_step',$this->Session->read('Wizard.step'));
			$this->ActiveController->set('wizard_limit',$this->verifyRequireds());
			
		}
	}
	
    /**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	function startup(Event $event)
	{
		$action = $this->request->getParam('action');
		
		$active = $this->Session->read('Wizard.active');
		
		if( $active ){
			$step = (int) $this->Session->read('Wizard.step');
			$config = $this->getConfig($step);
			if(isset($config['redirect'])){
				$redirect = (array) $config['redirect'];
				if(in_array($action,$redirect)){
					$this->redirect();
				}
			}

		}	
	}

		
    /**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function active()
	{
		$this->Session->delete('Wizard');
		
		$this->Session->write('Wizard.step',1);
		$this->Session->write('Wizard.active',true);	
	
		$this->_dirty = true;		
	}
	
    /**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function quit()
	{

		$config = $this->getConfig();
		$config = array_pop($config);
		
		if(isset($config['end']) && isset($config['get'])){
			if(isset($config['onload']) && $config['get']){
				$datas = (array) $this->Session->read('Wizard.datas');
				foreach($config['onload'] as $load){
					$datas = Hash::get($datas,$load);
					if(empty($datas)){
						$config['url'] = $config['alt'];
					} else {
						$config['url'][] = $datas;
					}
					
				}
			}
		}

		$this->Session->delete('Wizard');
		$this->_dirty = true;

		if(isset($config['end'])){
			if($config['end']){
				return $this->ActiveController->redirect( $config['url'] );
			}			
		}
		
	}

    /**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function next()
	{
		$return = $this->verifyRequired();
		
		if( $return ){		
			$step = (int) $this->Session->read('Wizard.step');
			$step++;
			
			$configs = $this->getConfig();
			$configk = array_keys($configs);
			
			if( ! in_array($step,$configk) ){
				$step = end($configk);
			}
			
			$active = $this->getConfig($step);
			$end = (boolean) Hash::extract( $active , 'end' );
			
			$this->Session->write('Wizard.step',$step);
			$this->_dirty = true;
			
			if( $end ){
				$this->quit();
			}
		}		
	}

    /**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function previous()
	{
		$step = (int) $this->Session->read('Wizard.step');
		$step--;
		
		$configs = $this->getConfig();
		$configk = array_keys($configs);
		
		if( ! in_array($step,$configk) ){
			$step = 1;
		}
		
		$this->Session->write('Wizard.step',$step);
		$this->_dirty = true;
	}
	
    /**
     * Instance parentéess.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function push( $new = 0 , $model = false , $data = false )
	{
		$test = false;
		
		$this->verifyRequireds();
		
		if( ! empty($model) && ! empty($data) ){
			$test = $this->testId($model,$data);
		}
						
		if( ! $test ){
			$model = false;
			$data = false;
			//$new = 1;
		}

		$new = (int) $new;
		$step = (int) $this->Session->read('Wizard.step');
		
		$configs = $this->getConfig();
		$configk = array_keys($configs);
		
		$end = end($configk);

		if( $new <= $end && $new >= 1 && $new != $step ){

			$active = $this->getConfig($new);
			$end = (boolean) Hash::extract( $active , 'end' );
			
			if( ! empty($model) && ! empty($data) ){
				$this->setDatas($model,$data);
			}
			
			$limit = $this->verifyRequireds();
			
			if($new > $limit){
				$limit = $new;
			}
			
			$this->Session->write('Wizard.step',$new);
			
			$this->_dirty = true;
			
			if( $end ){
				$this->quit();
			}
		}
	}
	
    /**
     * Instance parentées.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function reload()
	{
		//if( $this->_dirty ){
			
			$step = (int) $this->Session->read('Wizard.step');
			$config = $this->getConfig($step);
			
			$controller_session = $config['controller'];
			$controller_actif = $this->_getController()->getName();
			$controller_session = Inflector::camelize($controller_session);

			if( $controller_session != $controller_actif ){
				return $this->redirect();
			}
			
		//}
	}

    /**
     * Controller redirect.
     *
     * Permet de remplacer les redirect classique pour garantir le rechargement sur la page wizard déterminée
     */
	public function redirect($url = [], $force = false )
	{
		if( ! $force ){
			$step = (int) $this->Session->read('Wizard.step');
			$config = $this->getConfig($step);
			$url = $config['url'];
		}
		
		if( empty( $url ) ){
			$url = ['action'=>'index'];
		}

		$this->ActiveController->viewBuilder()->setLayout('default');

		return $this->ActiveController->redirect( $url );
		
	}
	
	/**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function testId( $model = 'Demandes' , $value = false )
	{
		$model = TableRegistry::get($model);
		return $model->wizard($value);
		
	}
	
	/**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function injectDatas( $path = '' , $datas = [] )
	{
		$step = $this->Session->read('Wizard.step');
		$config = $this->getConfig($step);
		
		if(isset($config['save'])){
			
			$save = $config['save'];
			
			if($save == $path){
				if(!empty($datas)){
					$this->Session->write('Wizard.datas.'.$path,$datas);
				}
			}			
		}
	}
	
	/**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function setDatas( $model = 'Demandes' , $value = false )
	{
		$model = TableRegistry::get($model);
		$output = $model->wizard($value);
		$output = Hash::expand($output);
		
		$this->Session->write('Wizard.datas',$output);
		
	}
	
	/**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function setData( $data = false )
	{
		if( ! empty($data)){
			$step = $this->Session->read('Wizard.step');
			$config = $this->getConfig($step);
			
			$paths = $config['save'];

			$datas = explode('_',$data);
		
			if(count($datas)==1){
				$datas = $data;
			}

			$this->Session->write('Wizard.datas.'.$paths,$datas);
		}
	}	

	/**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function verifyRequired()
	{
		$step = (int) $this->Session->read('Wizard.step');
		
		if($step == 1){
			return true;
		}
		
		if(!empty($step)){
			
			$fill = array_fill(1,$step,0);
			$fill = array_keys($fill);
			
			$error = [];
			
			foreach($fill as $val){
				$config = $this->getConfig($val);
				$path = $config['save'];
				$read = $this->Session->read('Wizard.datas.'.$path);
				if(empty($read)){
					$error[] = (int) $val;
					// TODO : Vérifier la valeur read
				}
			}
			if(empty($error)){
				return true;
			}
		}
		return false;
	}	
	/**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function verifyRequireds()
	{
		$config = $this->getConfig();
		$config = (array) Hash::extract($config,'{n}.save');
		$datas = (array) $this->Session->read('Wizard.datas');

		if(!empty($datas)){
			
			$tmp = [];
			
			foreach($config as $key => $val){
				if(Hash::check($datas,$val)){
					$tmp[$val] = Hash::extract($datas,$val);
				}
			}
			
			$datas = $tmp;

			$error =[];

			foreach($config as $key => $val){
				if(isset($datas[$val])){
					
					if(empty($datas[$val])){
						$error[] = $key + 1;
					}
				} else {
					$error[] = $key + 1;
				}
				$max = $key;
			}
			
			if(empty($error)){
				$minimal_step = $max + 1;
			} else {
				$minimal_step = (int) array_shift($error);
			}
			
		} else {
			$minimal_step = 1;
		}

		return $minimal_step;
	}		
	/**
     * Instance parentée.
     *
     * Permet de passer les variables wizard utile pour le layout
	 * Si une instance wizard existe, elle crée automatiquement la valeur, sinon wizard n'existe pas
     */	
	public function getDatas( $path = '{*}' )
	{
		return $this->Session->read('Wizard.datas.'.$path);
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

}
