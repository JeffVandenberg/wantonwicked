<?php

use App\View\AppView;
use Cake\Core\Configure;

/**
 * @var AppView $this
 * @var bool $isMapAdmin
 * @var array $coords
 */

$this->set('title_for_layout', 'City Map');
$this->addScript('game-map');
$this->addScript('map-ui');
?>
<?php if($isMapAdmin): ?>
<div>
<!--    <button class="button" id="record-zone-button">New Territory</button>-->
    <button class="button" id="add-location-button">New Location</button>
    <?= $this->Form->select('location-type', [], ['style' => 'display:none;width:200px;', 'id' => 'location-type']); ?>
    <button class="button" id="record-zone-button">New District</button>
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
    let myMap;
    let locationTypes = [];
    let mapUI = new MapUI();

    JSON.parse('<?= json_encode($locationTypes); ?>').forEach((i) => {
        locationTypes[i.id] = {
            name: i.name,
            icon: i.icon
        }
    });

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

        // add location button behavior
        $("#add-location-button").click((e) => {
            if(myMap.isAddingLocation()) {
                e.target.innerText = 'New Location';
                $("#location-type").toggle();
                myMap.setAddingLocation(false);
            } else {
                e.target.innerText = 'Cancel Location';
                $("#location-type").toggle();
                myMap.setAddingLocation(true);
            }
        });
        $("#record-zone-button").click((e) => {
            if (myMap.isCreatingZone()) {
                e.target.innerText = 'New District';
                myMap.finishZone();
            } else {
                e.target.innerText = 'Finish District';
                myMap.setCreatingZone(true);
            }
        });
    });

    function initMap() {
        let map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: <?= $coords['lat']; ?>, lng: <?= $coords['long']; ?>},
            zoom: 12
        });
        myMap = new GameMap(map, <?= $isMapAdmin ? 'true' : 'false'; ?>);

        map.addListener('click', (e) => {
            // this is why you need a backing state to bind to
            if(myMap.isAddingLocation()) {
                $("#location-type").toggle();
                $("#add-location-button").text('New Location');
            }
            myMap.checkClick(e);
        });

        myMap.setLocationIcon(locationTypes.find(element => { return !!element; }).icon);
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
</style>
<?php $this->end(); ?>
