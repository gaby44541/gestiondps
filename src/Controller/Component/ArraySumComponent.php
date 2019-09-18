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
class ArraySumComponent extends Component
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
        'paths' =>  [],
		'devise' => false,
		'round' => 2
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

    }
	
	public function getMapper(){
		
		$this->config = $this->getConfig();
		
		return function ($item, $status, $mapReduce) {
			$item = $this->somme( $item );			
			$mapReduce->emit($item, $status);
		};
	}
	
    /**
     * Handles automatic pagination of model records.
     *
     * @param \Cake\Datasource\RepositoryInterface|\Cake\Datasource\QueryInterface $object The table or query to paginate.
     * @param array $settings The settings/configuration used for pagination.
     * @return \Cake\Datasource\ResultSetInterface Query results
     * @throws \Cake\Http\Exception\NotFoundException
     */
	public function getReduce(){
		return function ($items, $status, $mapReduce) {
			$mapReduce->emit($items, $status);
		};
	}
	
    /**
     * Handles automatic pagination of model records.
     *
     * @param \Cake\Datasource\RepositoryInterface|\Cake\Datasource\QueryInterface $object The table or query to paginate.
     * @param array $settings The settings/configuration used for pagination.
     * @return \Cake\Datasource\ResultSetInterface Query results
     * @throws \Cake\Http\Exception\NotFoundException
     */
	public function mergeLast(array $merge =[]){
		return array_merge( $merge , $this->_array );
	}
	
    /**
     * Handles automatic pagination of model records.
     *
     * @param \Cake\Datasource\RepositoryInterface|\Cake\Datasource\QueryInterface $object The table or query to paginate.
     * @param array $settings The settings/configuration used for pagination.
     * @return \Cake\Datasource\ResultSetInterface Query results
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function somme($item,$object = false)
    {
			$this->_array = [];
			$this->config = $this->getConfig();
			
			$paths = $this->config['paths'];
	
			foreach($paths as $key => $path ){
				
				$operateur = substr($path,0,1);
				
				$strings = [];
				$template = [];
				
				switch( $operateur ){
					case '¤':
						$path = substr($path,1);
						$string = substr($path,1);
						$strings = '¤';
						break;
					case '*':
					case '+':
					case '/':
					case '-':
						$string = substr($path,1);
						$strings = explode('/',$string );
						$count = count($strings);
						for($i=1;$i<=$count;$i++){
							$template[] = '%'.$i.'$d';
						}
						$template = '$result='.implode($operateur,$template).';';
						break;
					default:
						$operateur = false;
						$string = $path;
						$strings = $path;
				}

				if( is_array($strings) ){
					$array_sum = [];
					$tmp = json_decode(json_encode($item), true);
					$tmp = Hash::format($tmp, (array)$strings, $template);
					foreach( $tmp as $val){
						eval( $val );
						$array_sum[] = $result;
					}
				} else {
					$array_sum = Hash::extract( $item , $path );
					
					if($strings=='¤'){
						$array_sum = array_filter($array_sum);
						$array_sum = array(count($array_sum));
					}
				}
				
				
				if($object){
					$item->{$key} = array_sum( $array_sum );	
				} else {
					$item[ $key ] = array_sum( $array_sum );			
				}
				
				$this->_array[$key] = array_sum( $array_sum );	
			
			}
			
			return $item;

    }

}
