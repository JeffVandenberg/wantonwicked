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
        $.post(url, data, () => console.log('saved location'));
    }
}
