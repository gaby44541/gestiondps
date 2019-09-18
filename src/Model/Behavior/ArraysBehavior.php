<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Text;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;

class ArraysBehavior extends Behavior
{
    protected $_defaultConfig = [
        'fields' =>  [],
		'unset' => []
    ];

    public function convertSet(Entity $entity)
    {
		
		$fields = (array) $this->getConfig('fields');

		foreach( $fields as $field){
			
			//$entity->set( $field , $entity->{$field} );
		}

    }
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
		$fields = (array) $this->getConfig('fields');
		$unsets = (array) $this->getConfig('unset');
		
		foreach( $fields as $field){
			if(isset($data[$field]) && is_array($data[$field])){
				$data[$field] = implode(',',$data[$field]);
			}
		}
		foreach( $unsets as $unset){
			if(isset($data[$unset])){
				unset($data[$unset]);
			}
		}

    }
	
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
		/*
		$unsets = (array) $this->getConfig('unset');
		
		foreach( $unsets as $unset){
			if(isset($entity->{$unset})){
				unset($entity->{$unset});
			}
		}
		*/
    }

}
?>