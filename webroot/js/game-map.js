class GameMap {
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
