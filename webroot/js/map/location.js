class Location {
    constructor (name = '', description = '', icon = '', point = null, marker = null, id = null) {
        this._name = name;
        this._description = description;
        this._icon = icon;
        this._point = point;
        this._marker = marker;
        this._id = id;
    }

    get marker() {
        return this._marker;
    }

    set marker(value) {
        this._marker = value;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
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

    get icon() {
        return this._icon;
    }

    set icon(value) {
        this._icon = value;
    }

    get point() {
        return this._point;
    }

    set point(value) {
        this._point = value;
    }
}
