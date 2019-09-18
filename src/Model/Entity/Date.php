<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Date Entity
 *
 * @property int $id
 * @property string $module
 * @property \Cake\I18n\FrozenTime $start
 * @property \Cake\I18n\FrozenTime $end
 * @property string $title
 * @property string $informations
 * @property string $url
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Date extends Entity
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
        'module' => true,
        'start' => true,
        'end' => true,
        'title' => true,
        'informations' => true,
        'url' => true,
        'created' => true,
        'modified' => true
    ];
}
