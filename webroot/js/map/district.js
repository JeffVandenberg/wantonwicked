class District {
    constructor(name, description, districtTypeId, color, points, polygon, id = null) {
        this._name = name;
        this._description = description;
        this._color = color;
        this._points = points;
        this._polygon = polygon;
        this._id = id;
        this._districtTypeId = districtTypeId;
    }

    get districtTypeId() {
        return this._districtTypeId;
    }

    set districtTypeId(value) {
        this._districtTypeId = value;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get polygon() {
        return this._polygon;
    }

    set polygon(value) {
        this._polygon = value;
    }

    get name() {
        return this._name;
    }

    set name(value) {
        this._name = value;
    }

    get description() {
        return this._description;
    }

    set description(value) {
        this._description = value;
    }

    get color() {
        return this._color;
    }

    set color(value) {
        this._color = value;
    }

    get points() {
        return this._points;
    }

    set points(value) {
        this._points = value;
    }
}
