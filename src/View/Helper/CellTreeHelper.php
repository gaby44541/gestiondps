<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class CellTreeHelper extends Helper {
	
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
		]
	];

	protected function getDatas($controller = 'Menus',$id=0) {
		// Get datas from the table
		$menus = TableRegistry::get($controller);
		
		// Query database
		$menus =  $menus->find('children', ['for' => $id])
						->find('threaded')
						->toArray();

		return $menus;
		
    }
	
	public function map($controller = 'Menus',$id=0 ) {
		// Get datas from table who storage menu data
		$menus = $this->getDatas($controller,$id);
		//var_dump( $menus );
		// Load bootstrap menu
		echo $this->buildTree( $menus , "nav navbar-nav" );
	}
	
    protected function buildTree($menus , $class = "dropdown-menu") {

        echo '<ul class="'.$class.'" role="menu">';
		foreach($menus as $element) {

            if(!is_array($element)) {
                $element = $element->toArray();
            }
			//var_dump( $element['intitule'] );
			if( $element['icone'] != 'divider' ) {

				if( ! empty( $element['icone'] ) ) {
					$element['intitule'] = $this->Html->icon( $element['icone'] ) . ' ' . $element['intitule'];
				}
				
				if( is_array( $element['children'] ) && ! empty( $element['children'] ) ){
					if( $class == 'dropdown-menu' ){
						echo '<li class="dropdown-submenu">';
					} else {
						echo '<li>';
					}
					
					echo $this->Html->link( $element['intitule'] .' <span class="caret"></span>',
											['controller'=>$element['controller'],'action'=>$element['actions']],
											['escape'=>false,'class'=>'dropdown-toggle','data-toggle'=>'dropdown'] );
					$this->buildTree( $element['children'] );
					echo '</li>';
				} else {
					echo '<li>';
					echo $this->Html->link( $element['intitule'] , ['controller'=>$element['controller'],'action'=>$element['actions']] , ['escape' => false] );
					echo '</li>';
				}
			} else {
				echo '<li class="divider"></li>';
			}
        }
		echo '</ul>';
    }
	
}
?>
