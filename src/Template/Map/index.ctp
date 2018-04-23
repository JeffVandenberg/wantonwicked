<?php

use App\View\AppView;
use Cake\Core\Configure;

/**
 * @var AppView $this
 */

$this->set('title_for_layout', 'City Map');
?>
<div>
    <button class="button" id="record-zone-button">New Territory</button>
</div>
<div id="map"></div>
<script>
    let myMap;

    class PageMap {
        constructor(map, creatingZone = false, zonePoints = []) {
            this.map = map;
            this.creatingZone = creatingZone;
            this.zonePoints = zonePoints;
            this.drawingPoints = [];
        }

        finishZone() {
            if (myMap.zonePoints.length > 2) {
                myMap.zonePoints.push(this.zonePoints[0]);

                let poly = new google.maps.Polygon({
                    paths: myMap.zonePoints,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });
                poly.setMap(myMap.map);
            } else {
                alert('Not enough points')
            }
            this.clearTempMarkers();
            myMap.creatingZone = false;
        }

        checkClick(e) {
            if (myMap.creatingZone) {
                myMap.zonePoints.push(e.latLng);
                let point = new google.maps.Marker({
                    position: e.latLng,
                    map: myMap.map,
                    title: 'Drawing'
                });
                this.drawingPoints.push(point);
            } else {
            }
        }

        isCreatingZone() {
            return this.creatingZone;
        }

        setCreatingZone(creatingZone) {
            this.creatingZone = creatingZone;
        }

        clearTempMarkers() {
            this.drawingPoints.forEach((point) => {
                point.setMap(null);
            });
            this.drawingPoints = [];
        }
    }

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
        myMap = new PageMap(map);

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
