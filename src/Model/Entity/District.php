<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * District Entity
 *
 * @property int $id
 * @property int $city_id
 * @property string $district_name
 * @property string|null $district_description
 * @property string|null $district_image
 * @property bool $is_active
 * @property int $created_by_id
 * @property \Cake\I18n\FrozenTime $created_on
 * @property int $updated_by_id
 * @property \Cake\I18n\FrozenTime $updated_on
 * @property int $reality_id
 * @property int $district_type_id
 * @property string $slug
 * @property string $points
 *
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\CreatedBy $created_by
 * @property \App\Model\Entity\UpdatedBy $updated_by
 * @property \App\Model\Entity\Reality $reality
 * @property \App\Model\Entity\DistrictType $district_type
 * @property \App\Model\Entity\Location[] $locations
 */
class District extends Entity
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
        'city_id' => true,
        'district_name' => true,
        'district_description' => true,
        'district_image' => true,
        'is_active' => true,
        'created_by_id' => true,
        'created_on' => true,
        'updated_by_id' => true,
        'updated_on' => true,
        'reality_id' => true,
        'district_type_id' => true,
        'slug' => true,
        'city' => true,
        'created_by' => true,
        'updated_by' => true,
        'reality' => true,
        'district_type' => true,
        'locations' => true
    ];
}
