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

        $this->set(compact('coords', 'locationTypes'));
    }

    public function isAuthorized($user): bool
    {
        return true;
    }
}
