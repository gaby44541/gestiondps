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

use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use InvalidArgumentException;
use RuntimeException;
use Cake\Log\Log;
use Cake\ORM\Entity;


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
class ChronologieBehavior extends Behavior
{

    /**
     * Default config
     *
     * These are merged with user-provided configuration when the behavior is used.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'implementedMethods' => [
            'updateChronologie' => 'updateChronologie',
        ],
        'update' => 'chronologie',
		'listen' => 'config_etat_id',
		'compare' =>[
			13 => 'demande',
			5  => 'etude.envoi',
			6  => 'etude.signee',
			7  => 'convention.envoi',
			8  => 'convention.signee',
			10 => 'facture.envoi',
			11 => 'facture.reglee',
			14 => 'annulation'
		]
    ];


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
        $this->updateChronologie($entity);
    }

    public function updateChronologie(Entity $entity)
    {
        $config = $this->getConfig();

        $listen = $entity->get($config['listen']);
		$update = $entity->get($config['update']);

		$compare = (array) $config['compare'];

		foreach($compare as $key => $item){

			if( $key == $listen){

				$update = empty($update) ? [] : json_decode($update,true);

				if(isset($update[$item])){
					$item .= '.relance.'.strtotime(date('Y-m-d H:i:s'));
				}

				$update[ $item ] = date('Y-m-d H:i');

				$entity->set($config['update'], json_encode($update));
			}
			if( $key != $listen){
				if(empty($update)){
					$entity->set($config['update'], json_encode(['reprise' => date('Y-m-d H:i')]));
				}
			}
		}

    }

}
?>
