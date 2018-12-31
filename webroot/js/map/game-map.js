class GameMap {
    constructor(map = null, isEditting = false, mapUI = new MapUI(), dataService = new MapDataService()) {
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
        this.locationTypeId = 0;
        this.districtTypeId = 0;
        this.dataService = dataService;
        this.infoWindows = [];
        this.currentEntity = null;
    }

    setMap(map) {
        this.map = map;
    }

    setZoneColor(zoneColor) {
        this.zoneColor = zoneColor;
    }

    setDistrictTypeId(districtTypeId) {
        this.districtTypeId = districtTypeId;
    }

    setLocationTypeId(locationTypeId) {
        this.locationTypeId = locationTypeId;
    }

    setCurrentEntity(entity) {
        this.currentEntity = entity;
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
            this.newLocationFromPoint(e.latLng);
        }
    }

    startDistrict() {
        this.stagingDistrict = null;
        this.setCreatingZone(true);
        this.mapUI.showDistrictTypeSelect();
        this.mapUI.setActiveDistrictButtonText();
    }

    cancelDistrict() {
        this.clearTempMarkers();
        this.clearZonePoints();
        this.creatingZone = false;
        this.mapUI.setPassiveDistrictButtonText();
        this.mapUI.hideDistrictTypeSelect();
    }

    finishDistrict() {
        if (this.zonePoints.length > 2) {
            this.zonePoints.push(this.zonePoints[0]);
            let district = this.createDistrict('', defaultDistrictDescription, this.districtTypeId,
                this.zoneColor, this.zonePoints);
            this.districts.push(district);
            this.mapUI.showDetailModel(district, this.dataService.saveDistrict);
        } else {
            alert('Not enough points');
        }
        this.clearTempMarkers();
        this.clearZonePoints();
        this.creatingZone = false;
        this.mapUI.setPassiveDistrictButtonText();
        this.mapUI.hideDistrictTypeSelect();
    }

    addDistrict(data) {
        let latLngs = data.points.map(point => new google.maps.LatLng(point.y, point.x)),
            district = this.createDistrict(data.name, data.description, data.district_type_id, data.color, latLngs,
                data.id);
        this.districts.push(district);
    }

    removeDistrict(district) {
        if (district.infoWindow) {
            district.infoWindow.setMap(null);
        }
        district.polygon.setMap(null);
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

    addLocation(data) {
        let latLng = new google.maps.LatLng(data.point.y, data.point.x),
            location = this.createLocation(data.name, data.description, data.location_type_id,
                data.icon, latLng, data.id);
        this.locations.push(location);
    }

    removeLocation(location) {
        if (location.infoWindow) {
            location.infoWindow.setMap(null);
        }
        location.marker.setMap(null);
    }

    newLocationFromPoint(latLng) {
        let location = this.createLocation('', defaultLocationDescription, this.locationTypeId,
            this.locationIcon, latLng);
        this.locations.push(location);
        this.setAddingLocation(false);
        this.mapUI.showDetailModel(location, this.dataService.saveLocation);
    }

    createDistrict(name, description, districtTypeId, color, points, id = null) {
        let polygon = new google.maps.Polygon({
                paths: points,
                fillColor: color,
                fillOpacity: this.zoneOpacity,
                strokeColor: color,
                strokeOpacity: this.strokeOpacity,
                strokeWeight: this.strokeWeight,
                draggable: this.isEditting
            }),
            district = new District(name, description, districtTypeId, color, points, polygon, id);
        polygon.addListener('click', (e) => {
            this.clearInfoWindows();
            let infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(this.mapUI.createInfoBoxContent(district));
            infoWindow.setPosition(e.latLng);
            infoWindow.setMap(this.map);
            this.setCurrentEntity(district);
            this.infoWindows.push(infoWindow);
            district.infoWindow = infoWindow;
        });
        polygon.addListener('dragend', (e) => {
            district.points = polygon.getPath().getArray();
            this.dataService.saveDistrict(district);
        })
        polygon.setMap(this.map);
        return district;
    }

    createLocation(name, description, locationTypeId, icon, latLng, id = null) {
        let marker = new google.maps.Marker({
                position: latLng,
                map: this.map,
                title: name,
                icon: icon,
                draggable: this.isEditting
            }),
            location = new Location(name, description, locationTypeId, icon, latLng, marker, id);
        marker.addListener('click', (e) => {
            this.clearInfoWindows();
            let infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(this.mapUI.createInfoBoxContent(location));
            infoWindow.setPosition(e.latLng);
            infoWindow.setMap(this.map);
            this.setCurrentEntity(location);
            this.infoWindows.push(infoWindow);
            location.infoWindow = infoWindow;
        });
        marker.addListener('dragend', (e) => {
            location.point = e.latLng;
            this.dataService.saveLocation(location);
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
        this.setDistrictsVisible(!addingLocation);
    }

    setDistrictsVisible(visible) {
        this.districts.forEach(district => {
            district.polygon.setVisible(visible);
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

    clearInfoWindows() {
        this.infoWindows.forEach(infoWindow => {
            infoWindow.setMap(null);
        });
        this.infoWindows = [];
    }

    initFeatures(locations, districts) {
        // draw locations
        locations.forEach((i) => {
            this.addLocation(i);
        })

        // draw districts
        districts.forEach((i) => {
            this.addDistrict(i);
        })
    }

    editCurrentEntity() {
        if(this.currentEntity instanceof Location) {
            this.mapUI.showDetailModel(this.currentEntity, this.dataService.saveLocation)
        }
        if(this.currentEntity instanceof District) {
            this.mapUI.showDetailModel(this.currentEntity, this.dataService.saveDistrict)
        }
    }

    deleteCurrentEntity() {
        if (confirm(`Are you sure you want to delete "${this.currentEntity.name}"?`)) {
            if (this.currentEntity instanceof Location) {
                this.dataService.deleteLocation(this.currentEntity, this.removeLocation);
            }

            if (this.currentEntity instanceof District) {
                this.dataService.deleteDistrict(this.currentEntity, this.removeDistrict);
            }
        }
    }
}
