<?php
namespace App\View\Cell;
use Cake\View\Cell;
use Cake\Routing\Router;

class MenuCell extends Cell {

	public function display($menu) {
		
        $this->loadModel('Menus');

        $menus = $this->Menus->find('children', ['for' => 8])->find('threaded')->toArray();

        $menus = $this->buildTree($menus);

        $this->set(compact('menus'));
        $this->set('_serialize', ['menus']);
    }

    //Recursive function:

    protected function buildTree($menus) {

        foreach($menus as $element) {

            if(!is_array($element)) {
                $element = $element->toArray();
            }

            //Elements each menu array  
            $menu['id'] = $element['id'];
			$menu['icone'] = $element['icone'];
			$menu['name'] = $element['intitule'];
            $menu['url'] = ['controller'=>$element['controller'],'action'=>$element['actions']];
			
			if( ! empty( $element['params'] ) ){
				array_push( $menu['url'] , $element['params'] );
			}

            foreach($element as $key => $value){

                if(is_array($value)) {
                    $menu[$key] = $this->buildTree($value);
                }

            }
            $resultMenu[] = $menu;
        }

        if(isset($resultMenu)) {
            return $resultMenu;
        }
    }

}