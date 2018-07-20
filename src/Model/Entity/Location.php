<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Location Entity
 *
 * @property int $id
 * @property int $district_id
 * @property string $location_name
 * @property string $location_description
 * @property string $location_image
 * @property bool $is_active
 * @property int $created_by_id
 * @property \Cake\I18n\Time $created_on
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated_on
 * @property bool $is_private
 * @property int $character_id
 * @property string $owning_character_name
 * @property int $location_type_id
 * @property string $location_rules
 *
 * @property \App\Model\Entity\District $district
 * @property \App\Model\Entity\CreatedBy $created_by
 * @property \App\Model\Entity\UpdatedBy $updated_by
 * @property \App\Model\Entity\Character[] $characters
 * @property \App\Model\Entity\LocationType $location_type
 * @property \App\Model\Entity\LocationTrait[] $location_traits
 */
class Location extends Entity
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
