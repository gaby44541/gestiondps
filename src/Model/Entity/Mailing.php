<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mailing Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $destinataire
 * @property \Cake\I18n\FrozenTime $send
 * @property \Cake\I18n\FrozenTime $read
 * @property string $message
 * @property int $mail_id
 *
 * @property \App\Model\Entity\Mail $mail
 */
class Mailing extends Entity
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
        'uuid' => true,
        'destinataire' => true,
        'send' => true,
        'read' => true,
        'message' => true,
        'mail_id' => true,
        'mail' => true
    ];
}
