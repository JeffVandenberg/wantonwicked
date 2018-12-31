class MapDataService {
    constructor(isDebug = false) {
        this._isDebug = isDebug;
    };

    get isDebug() {
        return this._isDebug;
    }

    set isDebug(value) {
        this._isDebug = value;
    }

    saveLocation(location) {
        const url = `/locations/save.json`,
            data = {
                id: location.id,
                name: location.name,
                description: location.description,
                location_type_id: location.locationTypeId,
                point: {
                    x: location.point.lng(),
                    y: location.point.lat()
                }
            };
        $.post(url, data, (response) => {
            location.id = response.location.id;
            $.toast({
                text: `${location.name} has been saved.`,
                heading: 'Successs',
                position: {top:20, right:70},
                icon: 'info',
                allowToastClose: true
            });
        });
    }

    deleteLocation(location, next) {
        const url = `/locations/delete/${location.id}.json`,
            data = {};
        $.post(url, data, (response) => {
            if (response.data.success) {
                $.toast({
                    text: `${location.name} has been deleted.`,
                    heading: 'Successs',
                    position: {top:20, right:70},
                    icon: 'info',
                    allowToastClose: true
                });
                if (next) {
                    next(location);
                }
            } else {
                $.toast({
                    text: `${response.data.message}`,
                    heading: 'Error',
                    position: {top:20, right:70},
                    icon: 'error',
                    allowToastClose: true
                });
            }
        })
    }

    saveDistrict(district) {
        const url = `/districts/save.json`,
            data = {
                id: district.id,
                name: district.name,
                description: district.description,
                district_type_id: district.districtTypeId,
                points: []
            };
        district.points.forEach((point) => {
            data.points.push({
                x: point.lng(),
                y: point.lat()
            });
        });
        $.post(url, data, (response) => {
            district.id = response.district.id;
            $.toast({
                text: `${district.name} has been added.`,
                heading: 'Successs',
                position: {top:20, right:70},
                icon: 'info',
                allowToastClose: true
            });
        });
    }

    deleteDistrict(district, next) {
        const url = `/districts/delete/${district.id}.json`,
            data = {};
        $.post(url, data, (response) => {
            if (response.data.success) {
                $.toast({
                    text: `${district.name} has been deleted.`,
                    heading: 'Successs',
                    position: {top:20, right:70},
                    icon: 'info',
                    allowToastClose: true
                });
                if (next) {
                    next(district);
                }
            } else {
                $.toast({
                    text: `${response.data.message}`,
                    heading: 'Error',
                    position: {top:20, right:70},
                    icon: 'error',
                    allowToastClose: true
                });

                alert(response.data.message);
            }
        })
    }
}
