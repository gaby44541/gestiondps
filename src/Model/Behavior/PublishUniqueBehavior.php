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
namespace Cake\ORM\Behavior;

use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use InvalidArgumentException;
use RuntimeException;

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
class PublishUniqueBehavior extends Behavior
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
            // 'moveUp' => 'moveUp',
            // 'moveDown' => 'moveDown',
            'publishLast' => 'publishLast',
        ],
        'field' => 'publish',
		'unique' => 'uuid',
		'order' => 'id'
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
    public function beforeSave(Event $event, EntityInterface $entity)
    {
        $config = $this->getConfig();

		$entity->set($config['field'], 1);

    }

    /**
     * Returns a single string value representing the primary key of the attached table
     *
     * @return string
     */
    protected function _getPrimaryKey()
    {
        if (!$this->_primaryKey) {
            $primaryKey = (array)$this->_table->getPrimaryKey();
            $this->_primaryKey = $primaryKey[0];
        }

        return $this->_primaryKey;
    }
	
	/**
     * Returns the maximum index value in the table.
     *
     * @return int
     */
    protected function _getMax( $uuid = false )
    {
		if( ! $uuid ){
			return 0;
		}
		
        $field = $this->_getPrimaryKey();
		
		$config = $this->getConfig();
		
        $order = $config['order'];
		$unique = $config['unique'];
		
        $edge = $this->_table->find()
            ->select([$field])
            ->orderDesc($order)
			->where([$unique => $uuid])
            ->first();

        if (empty($edge->{$field})) {
            return 0;
        }

        return $edge->{$field};
    }
    /**
     * After save listener.
     *
     * Manages updating level of descendants of currently saved entity.
     *
     * @param \Cake\Event\Event $event The afterSave event that was fired
     * @param \Cake\Datasource\EntityInterface $entity the entity that is going to be saved
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity)
    {

        $config = $this->getConfig();

		$uuid = $entity->get($config['unique']);
		
		$this->_resetAllWithoutLast( $uuid );
		
    }

	/**
     * Returns the maximum index value in the table.
     *
     * @return int
     */
    protected function _resetAllWithoutLast( $uuid = false )
    {
		if( ! $uuid ){
			return false;
		}
		
		$config = $this->getConfig();

		$last = $this->_getMax( $uuid );
		
		$field = $config['field'];
		$unique = $config['unique'];
		$primary = $this->_getPrimaryKey() . ' IS NOT';
		
        $edge = $this->_table->find()
			->update()
			->set([$field => 0])
			->where([$unique => $uuid,'AND'=>[$primary => $last]])
            ->execute();
		
		return $edge;
    }

}
