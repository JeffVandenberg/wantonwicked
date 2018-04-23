<?php

use App\View\AppView;
use Cake\Core\Configure;

/**
 * @var AppView $this
 */

$this->set('title_for_layout', 'City Map');
$this->addScript('game-map');
?>
<div>
    <button class="button" id="record-zone-button">New Territory</button>
</div>
<div id="map"></div>
<script>
    let myMap;

    $(function () {
        $("#record-zone-button").click((e) => {
            if (myMap.isCreatingZone()) {
                e.target.innerText = 'New Territory';
                myMap.finishZone();
            } else {
                e.target.innerText = 'Finish Territory';
                myMap.setCreatingZone(true);
            }
        });
    });

    function initMap() {
        let map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 45.5231, lng: -122.6765},
            zoom: 12
        });
        myMap = new GameMap(map);

        map.addListener('click', (e) => {myMap.checkClick(e); });
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
