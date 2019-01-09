<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SnsAcount Entity
 *
 * @property int $id
 * @property string $acount_id
 * @property string $sns
 * @property int $user_id
 *
 * @property \App\Model\Entity\Acount $acount
 * @property \App\Model\Entity\User $user
 */
class SnsAcount extends Entity
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
        'acount_id' => true,
        'sns' => true,
        'user_id' => true,
        'acount' => true,
        'user' => true
    ];
}
