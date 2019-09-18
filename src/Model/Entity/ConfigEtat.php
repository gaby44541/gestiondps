<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConfigEtat Entity
 *
 * @property int $id
 * @property string $designation
 * @property string $description
 * @property string $class
 * @property int $ordre
 *
 * @property \App\Model\Entity\Demande[] $demandes
 */
class ConfigEtat extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'designation' => true,
        'description' => true,
        'class' => true,
        'ordre' => true,
        'demandes' => true
    ];
	protected $_virtual = [
		'color',
		'color_code'
	];
	
	protected function _getColor()
    {
        return $this->_properties['ordre'];
    }
	
	protected function _getColorCode()
    {
        return $this->_properties['class'];
    }
}
