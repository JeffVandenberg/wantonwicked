<?php

use App\View\AppView;
use Cake\Core\Configure;

/**
 * @var AppView $this
 * @var bool $isMapAdmin
 * @var array $coords
 * @var array $districtTypes
 * @var array $locationTypes
 * @var array $locations
 */

$this->set('title_for_layout', 'City Map');
$this->addScript('map/game-map');
$this->addScript('map/map-ui');
$this->addScript('map/data-service');
$this->addScript('map/district');
$this->addScript('map/location');
?>
<?php if ($isMapAdmin): ?>
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
            <div class="small-12 columns">
                <label>Name</label>
                <input type="text" name="feature_name" id="detail-modal__feature-name" required/>
            </div>
            <div class="small-12 columns">
                <label>Description</label>
                <textarea name="feature_name" id="detail-modal__feature-description"
                          class="tinymce-textarea"></textarea>
            </div>
            <div class="small-12 columns text-center">
                <button class="button" id="detail-modal__save-button">Save</button>
                <button class="button" id="detail-modal__cancel-button">Cancel</button>
            </div>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<script>
    const locationTypes = [],
        districtTypes = [],
        isEditting = <?= $isMapAdmin ? 'true' : 'false'; ?>,
        mapData = new MapDataService(),
        mapUI = new MapUI(isEditting),
        myMap = new GameMap(null, isEditting, mapUI, mapData),
        defaultDistrictDescription = `
<div>
    <b>Faction Control:</b><br />
    <b>Wiki:</b><br />
    <b>District:</b><br />
    <b>Neighborhood:</b><br />
</div>
`,
        defaultLocationDescription = `No Description Template Defined`,
        locations = JSON.parse('<?= str_replace(["'", '\\"'], ["\'", '\\\"'], json_encode($locations)); ?>');

    JSON.parse('<?= json_encode($locationTypes); ?>').forEach((i) => {
        locationTypes[i.id] = i;
    });
    JSON.parse('<?= json_encode($districtTypes); ?>').forEach((i) => {
        districtTypes[i.id] = i;
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
            let locationTypeId = $("#location-type").val();
            myMap.setLocationIcon(locationTypes[locationTypeId].icon);
            myMap.setLocationTypeId(locationTypeId);
        })

        // setup district type selector
        const $districtType = $("#district-type")
        districtTypes.forEach((i, key) => {
            $districtType.append(
                $("<option>").attr('value', key).text(i.name)
            );
        });
        $districtType.change((event) => {
            let districtTypeId = $("#district-type").val();
            myMap.setZoneColor(districtTypes[districtTypeId].color);
            myMap.setDistrictTypeId(districtTypeId);
        })

        // setup map defaults
        const firstLocationType = locationTypes.find(element => {
                return !!element;
            }),
            firstDistrictType = districtTypes.find(element => {
                return !!element;
            });
        myMap.setLocationIcon(firstLocationType.icon);
        myMap.setLocationTypeId(firstLocationType.id)
        myMap.setZoneColor(firstDistrictType.color);
        myMap.setDistrictTypeId(firstDistrictType.id);

        // add location button behavior
        $("#add-location-button").click((e) => {
            if (myMap.isAddingLocation()) {
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

        // setup detail modal
        $("#detail-modal__save-button").click((e) => {
            mapUI.saveUpdate();
        });
        $("#detail-modal__cancel-button").click((e) => {
            mapUI.cancelUpdate();
        });

        $(document).on('click', '.info-box-content__link--edit', () => {
            myMap.editCurrentEntity();
        });

        $(document).on('click', '.info-box-content__link--delete', () => {
            myMap.deleteCurrentEntity();
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
        myMap.initFeatures(locations, []);
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
        text-align: center;
    }

    .info-box-content__admin a {
        color: #000000;
    }

    .info-box-content__admin a:hover {
        color: #000000;
        text-decoration: underline;
    }

    .info-box-content hr {
        margin: .24rem 0;
    }
</style>
<?php $this->end(); ?>
