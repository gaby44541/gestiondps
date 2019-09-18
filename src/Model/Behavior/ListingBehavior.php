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
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * Makes the table to which this is attached to behave like a nested set and
 * provides methods for managing and retrieving information out of the derived
 * hierarchical structure.
 *
 * Tables attaching this behavior are required to have a column referencing the
 * parent row, and two other numeric columns (lft and rght) where the implicit
 * order will be cached.
 *
 * For more information on what is a nested set and a how it works refer to
 * https://www.sitepoint.com/hierarchical-data-database-2/
 */
class ListingBehavior extends Behavior
{

    /**
     * Cached copy of the first column in a table's primary key.
     *
     * @var string
     */
    protected $_primaryKey;

    /**
     * Default config
     *
     * These are merged with user-provided configuration when the behavior is used.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'implementedMethods' => [
            'alone' => 'alone',
            'listing' => 'listing',
            //'reOrder' => 'reOrder',
        ],
		'binding_key' => false,
		'binding_val' => false
    ];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {

    }

    /**
     * Before save listener.
     * Transparently manages setting the lft and rght fields if the parent field is
     * included in the parameters to be saved.
     *
     * @param \Cake\Event\Event $event The beforeSave event that was fired
     * @param \Cake\Datasource\EntityInterface $entity the entity that is going to be saved
     * @return void
     * @throws \RuntimeException if the parent to set for the node is invalid
     */
    //public function beforeSave(Event $event, EntityInterface $entity)
    //{

    //}

    /**
     * After save listener.
     *
     * Manages updating level of descendants of currently saved entity.
     *
     * @param \Cake\Event\Event $event The afterSave event that was fired
     * @param \Cake\Datasource\EntityInterface $entity the entity that is going to be saved
     * @return void
     */
    // public function afterSave(Event $event, EntityInterface $entity)
    // {
		//$this->reOrder();
    // }

    /**
     * Also deletes the nodes in the subtree of the entity to be delete
     *
     * @param \Cake\Event\Event $event The beforeDelete event that was fired
     * @param \Cake\Datasource\EntityInterface $entity The entity that is going to be saved
     * @return void
     */
    // public function afterDelete(Event $event, EntityInterface $entity)
    // {

    // }

    /**
     * Returns the depth level of a node in the tree.
     *
     * @param int|string|\Cake\Datasource\EntityInterface $entity The entity or primary key get the level of.
     * @return int|bool Integer of the level or false if the node does not exist.
     */
	public function alone($id = 0)
	{
		$id = (int) $id;

		$val = $this->_table->getDisplayField();
		$key = $this->_table->getPrimaryKey();

		return $this->_table->find()->where([
            'id' => $id
        ])->combine($key,$val)->toArray();
	}
	
    /**
     * Returns the depth level of a node in the tree.
     *
     * @param int|string|\Cake\Datasource\EntityInterface $entity The entity or primary key get the level of.
     * @return int|bool Integer of the level or false if the node does not exist.
     */
	public function listing($conditions = [],$key=false,$val=false)
	{
		$conditions = (array) $conditions;
		
		$bindings = $this->getConfig();
		
		$val = $val ? $val : $bindings['binding_val'];
		$key = $key ? $key : $bindings['binding_key'];
		
		$val = $val ? $val : $this->_table->getDisplayField();
		$key = $key ? $key : $this->_table->getPrimaryKey();

		return $this->_table->find('all',$conditions)->order([
            $key => 'ASC'
        ])->combine($key,$val)->toArray();
	}

}
