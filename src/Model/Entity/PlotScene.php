<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PlotScene Entity
 *
 * @property int $id
 * @property int $plot_id
 * @property int $scene_id
 * @property string $note
 *
 * @property \App\Model\Entity\Plot $plot
 * @property \App\Model\Entity\Scene $scene
 */
class PlotScene extends Entity
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
        '*' => true,
        'id' => false
    ];
}
