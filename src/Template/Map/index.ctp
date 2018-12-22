<?php

use App\View\AppView;
use Cake\Core\Configure;

/**
 * @var AppView $this
 * @var bool $isMapAdmin
 * @var array $coords
 * @var array $districtTypes
 * @var array $locationTypes
 */

$this->set('title_for_layout', 'City Map');
$this->addScript('map/game-map');
$this->addScript('map/map-ui');
$this->addScript('map/district');
$this->addScript('map/location');
?>
<?php if($isMapAdmin): ?>
<div>
<!--    <button class="button" id="record-zone-button">New Territory</button>-->
    <button class="button" id="add-location-button">New Location</button>
    <?= $this->Form->select('location-type', [], ['style' => 'display:none;width:200px;', 'id' => 'location-type']); ?>
    <button class="button" id="add-district-button">New District</button>
    <?= $this->Form->select('district-type', [], ['style' => 'display:none;width:200px;', 'id' => 'district-type']); ?>
    <?= $this->Html->link('Location Types', ['controller' => 'location-types', 'action' => 'index'], ['class' => 'button']); ?>
    <?= $this->Html->link('District Types', ['controller' => 'district-types', 'action' => 'index'], ['class' => 'button']); ?>
</div>
<?php endif; ?>
<div id="map"></div>
<div class="reveal" id="detail-modal" data-reveal>
    <div id="detail-modal-content">
        <div class="row">
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<script>
    let locationTypes = [],
        districtTypes = [],
        isEditting = <?= $isMapAdmin ? 'true' : 'false'; ?>,
        mapUI = new MapUI(isEditting),
        myMap = new GameMap(null, isEditting, mapUI);

    JSON.parse('<?= json_encode($locationTypes); ?>').forEach((i) => {
        locationTypes[i.id] = {
            name: i.name,
            icon: i.icon
        }
    });
    JSON.parse('<?= json_encode($districtTypes); ?>').forEach((i) => {
        districtTypes[i.id] = {
            name: i.name,
            color: i.color
        }
    })

    $(function () {
        // setup location type selector
        let $locationType = $("#location-type")
        locationTypes.forEach((i, key) => {
            $locationType.append(
                $("<option>").attr('value', key).text(i.name)
            );
        });
        $locationType.change((event) => {
            myMap.setLocationIcon(locationTypes[$("#location-type").val()].icon);
        })

        // setup district type selector
        let $districtType = $("#district-type")
        districtTypes.forEach((i, key) => {
            $districtType.append(
                $("<option>").attr('value', key).text(i.name)
            );
        });
        $districtType.change((event) => {
            myMap.setZoneColor(districtTypes[$("#district-type").val()].color);
        })

        // setup map defaults
        myMap.setLocationIcon(locationTypes.find(element => { return !!element; }).icon);
        myMap.setZoneColor(districtTypes.find(element => { return !!element; }).color);

        // add location button behavior
        $("#add-location-button").click((e) => {
            if(myMap.isAddingLocation()) {
                myMap.cancelLocation();
            } else {
                myMap.startLocation();
            }
        });

        // setup district button behavior
        $("#add-district-button").click((e) => {
            if (myMap.isCreatingZone()) {
                myMap.finishDistrict();
            } else {
                myMap.startDistrict();
            }
        });
    });

    function initMap() {
        let map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: <?= $coords['lat']; ?>, lng: <?= $coords['long']; ?>},
            zoom: 12
        });

        map.addListener('click', (e) => {
            myMap.checkClick(e);
        });

        myMap.setMap(map);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= Configure::read('Maps.key'); ?>&callback=initMap"
        async defer></script>
<?php $this->start('css'); ?>
<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 800px;
        width: 100%;
    }

    .info-box-content {
        min-width: 200px;
        color: #000000;
    }

    .info-box-content__admin {
        background-color: #ffdddd;
        margin-top: .5rem;
    }

    .info-box-content hr {
        margin: .24rem 0;
    }
</style>
<?php $this->end(); ?>
