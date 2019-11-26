<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Antenne Entity
 *
 * @property int $id
 * @property string $nom
 * @property string $id_ville
 *
 * @property \App\Model\Entity\Caserne[] $casernes
 */
class Caserne extends Entity
{

    /**
     * Fields
     */
    protected $_accessible = [
        'id' => true,
        'nom' => true,
        'id_ville' => true,
        'dimensionnements' => true
    ];
}
