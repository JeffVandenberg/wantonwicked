<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 4/19/2018
 * Time: 11:28 PM
 */

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use Cake\Event\Event;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;


/**
 * @property PermissionsComponent Permissions
 */
class MapController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->set('isMapAdmin', $this->Permissions->isMapAdmin());
    }

    public function index($cityId = null): void
    {
        // load city

        // set map coordinates
        $coords = ['lat' => 45.5231, 'long' => -122.6765];

        // load districts

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
            ->where([
                'Locations.id' => 7
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

        $this->set(compact('coords', 'locationTypes', 'districtTypes', 'locations'));
    }

    public function isAuthorized($user): bool
    {
        return true;
    }
}
