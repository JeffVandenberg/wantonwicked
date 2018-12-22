class GameMap {
    constructor(map = null, isEditting = false, mapUI = new MapUI()) {
        this.map = map;
        this.addingLocation = false;
        this.creatingZone = false;
        this.zonePoints = [];
        this.drawingPoints = [];
        this.zoneColor = '#FF0000';
        this.zoneOpacity = 0.35;
        this.strokeWeight = 2;
        this.strokeOpacity = 1.0;
        this.isEditting = isEditting;
        this.locationIcon = '';
        this.districts = [];
        this.locations = [];
        this.mapUI = mapUI;
        this.stagingLocation = null;
        this.stagingDistrict = null;
    }

    setMap(map) {
        this.map = map;
    }

    setZoneColor(zoneColor) {
        this.zoneColor = zoneColor;
    }

    checkClick(e) {
        if (this.creatingZone) {
            this.zonePoints.push(e.latLng);
            let point = new google.maps.Marker({
                position: e.latLng,
                map: this.map,
                title: 'Drawing District'
            });
            this.drawingPoints.push(point);
        } else if (this.addingLocation) {
            this.mapUI.hideLocationTypeSelect();
            this.mapUI.setPassiveLocationButtonText();
            this.addLocation(e.latLng);
        }
    }

    startDistrict() {
        this.stagingDistrict = null;
        this.setCreatingZone(true);
        this.mapUI.showDistrictTypeSelect();
        this.mapUI.setActiveDistrictButtonText();
    }

    finishDistrict() {
        if (this.zonePoints.length > 2) {
            this.zonePoints.push(this.zonePoints[0]);
            let district = this.createDistrict('New District', 'Test Description', this.zoneColor, this.zonePoints);
            this.districts.push(district);
            this.mapUI.showDetailModel(district, () => { console.log('done!'); });
        } else {
            alert('Not enough points');
        }
        this.clearTempMarkers();
        this.clearZonePoints();
        this.creatingZone = false;
        this.mapUI.setPassiveDistrictButtonText();
        this.mapUI.hideDistrictTypeSelect();
    }

    startLocation() {
        myMap.setAddingLocation(true);
        this.mapUI.showLocationTypeSelect();
        this.mapUI.setActiveLocationButtonText();
    }

    cancelLocation() {
        this.setAddingLocation(false);
        this.mapUI.hideLocationTypeSelect();
        this.mapUI.setPassiveLocationButtonText();
    }

    addLocation(latLng) {
        let location = this.createLocation('New Location', 'Description', this.locationIcon, latLng);
        this.locations.push(location);
        this.setAddingLocation(false);
    }

    createDistrict(name, description, color, points) {
        let polygon = new google.maps.Polygon({
                paths: points,
                fillColor: color,
                fillOpacity: this.zoneOpacity,
                strokeColor: color,
                strokeOpacity: this.strokeOpacity,
                strokeWeight: this.strokeWeight,
                draggable: this.isEditting
            }),
            district = new District(name, description, color, points, polygon);
        polygon.addListener('click', (e) => {
            let infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(this.mapUI.createInfoBoxContent(district));
            infoWindow.setPosition(e.latLng);
            infoWindow.setMap(this.map);
        });
        polygon.addListener('dragend', (e) => {
            district.points = poligy.getPath().getArray();
        })
        polygon.setMap(this.map);
        return district;
    }

    createLocation(name, description, icon, latLng) {
        let marker = new google.maps.Marker({
                position: latLng,
                map: this.map,
                title: name,
                icon: icon,
                draggable: this.isEditting
            }),
            location = new Location(name, description, icon, latLng, marker);
        marker.addListener('click', (e) => {
            let infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(this.mapUI.createInfoBoxContent(location));
            infoWindow.setPosition(e.latLng);
            infoWindow.setMap(this.map);
        });
        marker.addListener('dragend', (e) => {
            location.point = e.latLng;
        });
        return location;
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
        this.setDistrictVisible(!addingLocation);
    }

    setDistrictVisible(visible) {
        this.districts.forEach(district => {
            district.getPolygon().setVisible(visible);
        });
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
