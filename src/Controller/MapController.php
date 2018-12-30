<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 4/19/2018
 * Time: 11:28 PM
 */

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\District;
use Cake\Event\Event;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;


/**
 * @property \App\Controller\Component\ConfigComponent $Config
 * @property PermissionsComponent Permissions
 */
class MapController extends AppController
{
    public $components = array(
        'Config'
    );

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->set('isMapAdmin', $this->Permissions->isMapAdmin());
    }

    public function index($cityId = null): void
    {
        // load city

        // get configurations
        $coords = ['lat' => 45.5231, 'long' => -122.6765];
        $defaultLocationDescription = $this->Config->read('default_location_description');
        $defaultDistrictDescription = $this->Config->read('default_district_description');

        // load district types
        $districtTypes = TableRegistry::getTableLocator()->get('DistrictTypes')
            ->find()
            ->select([
                'name',
                'color',
                'id'
            ])
            ->order([
                'DistrictTypes.name'
            ])
            ->toArray();

        // load location types
        $locationTypes = TableRegistry::getTableLocator()->get('LocationTypes')
            ->find()
            ->select([
                'name',
                'icon',
                'id',
            ])
            ->order([
                'LocationTypes.name'
            ])
            ->toArray();

        // load locations
        $locations = TableRegistry::getTableLocator()->get('Locations')
            ->find()
            ->select([
                'id',
                'name' => 'location_name',
                'description' => 'location_description',
                'location_type_id',
                'slug',
                'point',
                'LocationTypes.icon'
            ])
            ->contain([
                'LocationTypes'
            ])
            ->toArray();

        // decode the points
        $locations = array_map(function($item) {
            $item->point = json_decode($item->point);
            $item->icon = $item->location_type->icon;
            unset($item->location_type);
            return $item;
        }, $locations);

        // load districts
        $districts = TableRegistry::getTableLocator()->get('districts')
            ->find()
            ->select([
                'id',
                'name' => 'district_name',
                'description' => 'district_description',
                'district_type_id',
                'slug',
                'points',
                'DistrictTypes.color'
            ])
            ->contain([
                'DistrictTypes'
            ])
            ->toArray();

        $districts = array_map(function($item) {
            /** @var District $item */
            $item->points = json_decode($item->points);
            $item->color = $item->district_type->color;
            unset($item->district_type);
            return $item;
        }, $districts);

        $this->set(compact('coords', 'locationTypes', 'districtTypes', 'locations', 'districts',
            'defaultDistrictDescription', 'defaultLocationDescription'));
    }

    public function isAuthorized($user): bool
    {
        return true;
    }
}
