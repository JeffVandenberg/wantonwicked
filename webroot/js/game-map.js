class GameMap {
    constructor(map, isEditting = false, addingLocation = false, creatingZone = false) {
        this.map = map;
        this.addingLocation = addingLocation;
        this.creatingZone = creatingZone;
        this.zonePoints = [];
        this.drawingPoints = [];
        this.zoneColor = '#FF0000';
        this.zoneOpacity = 0.35;
        this.strokeWeight = 2;
        this.strokeOpacity = 1.0;
        this.isEditting = isEditting;
        this.locationIcon = '';
    }

    finishZone() {
        if (this.zonePoints.length > 2) {
            this.zonePoints.push(this.zonePoints[0]);

            let poly = new google.maps.Polygon({
                paths: myMap.zonePoints,
                fillColor: this.zoneColor,
                fillOpacity: this.zoneOpacity,
                strokeColor: this.zoneColor,
                strokeOpacity: this.strokeOpacity,
                strokeWeight: this.strokeWeight,
                draggable: this.isEditting
            });
            poly.addListener('click', (e) => {
                let infoWindow = new google.maps.InfoWindow();
                infoWindow.setContent("<div style='color:black;'>Description Here!</div>");
                infoWindow.setPosition(e.latLng);
                infoWindow.setMap(this.map);
            })
            poly.setMap(this.map);
        } else {
            alert('Not enough points');
        }
        this.clearTempMarkers();
        this.clearZonePoints();
        this.creatingZone = false;
    }

    checkClick(e) {
        if (this.creatingZone) {
            this.zonePoints.push(e.latLng);
            let point = new google.maps.Marker({
                position: e.latLng,
                map: this.map,
                title: 'Drawing'
            });
            this.drawingPoints.push(point);
        } else if (this.addingLocation) {
            this.addLocation(e.latLng);
        }
    }

    addLocation(latLng) {
        let location = new google.maps.Marker({
            position: latLng,
            map: this.map,
            title: "New Location",
            icon: this.locationIcon
        });
        location.addListener('click', (e) => {
            let infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent("<div style='color:black;'>Description Here!</div>");
            infoWindow.setPosition(e.latLng);
            infoWindow.setMap(this.map);
        })
        this.setAddingLocation(false);
    }
    isCreatingZone() {
        return this.creatingZone;
    }

    setCreatingZone(creatingZone) {
        this.creatingZone = creatingZone;
    }

    setLocationIcon(locationIcon) {
        this.locationIcon = locationIcon;
    }

    isAddingLocation() {
        return this.addingLocation;
    }

    setAddingLocation(addingLocation) {
        this.addingLocation = addingLocation;
    }

    clearTempMarkers() {
        this.drawingPoints.forEach(point => {
            point.setMap(null);
        });
        this.drawingPoints = [];
    }

    clearZonePoints() {
        this.zonePoints = [];
    }
}
