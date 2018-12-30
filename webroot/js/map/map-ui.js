class MapUI {
    constructor(isEditting = false) {
        this._isEditting = isEditting;
        this._currentEntity = null;
        this._currentCallback = null;
        this.detailModal = $("#detail-modal");
    }

    get currentEntity() {
        return this._currentEntity;
    }

    set currentEntity(value) {
        this._currentEntity = value;
    }

    get currentCallback() {
        return this._currentCallback;
    }

    set currentCallback(value) {
        this._currentCallback = value;
    }

    get isEditting() {
        return this._isEditting;
    }

    set isEditting(value) {
        this._isEditting = value;
    }

    showLocationTypeSelect() {
        $("#location-type").show();
    }

    hideLocationTypeSelect() {
        $("#location-type").hide();
    }

    showDistrictTypeSelect() {
        $("#district-type").show();
    }

    hideDistrictTypeSelect() {
        $("#district-type").hide();
    }

    setPassiveLocationButtonText() {
        $("#add-location-button").text('New Location');
    }

    setActiveLocationButtonText() {
        $("#add-location-button").text('Cancel Location');
    }

    setPassiveDistrictButtonText() {
        $("#add-district-button").text('New District');
    }

    setActiveDistrictButtonText() {
        $("#add-district-button").text('Finish District');
    }

    createInfoBoxContent(entity) {
        let content = `
<div class="info-box-content">
<div class="info-box-content__body">
<b>Name:</b><br />${entity.name}</div>
<hr />
<div>
${entity.description}
</div>
`;

        if (this.isEditting) {
            content += `
<div class="info-box-content__admin">
<b>Admin</b><hr />
<a href="#" class="info-box-content__link--edit">Edit</a> 
-
<a href="#" class="info-box-content__link--delete">Delete</a> 
</div>
`;
        }

        content += `</div>`;
        return content;
    }

    showDetailModel(entity, callback) {
        this.detailModal.find('#detail-modal__feature-name').val(entity.name);
        this.detailModal.find('#detail-modal__feature-description').val(entity.description);
        tinymce.activeEditor.setContent(entity.description);
        this.detailModal.foundation('open');

        this.currentEntity = entity;
        this._currentCallback = callback;
    }

    saveUpdate() {
        this.currentEntity.name = this.detailModal.find('#detail-modal__feature-name').val();
        this.currentEntity.description = this.detailModal.find('#detail-modal__feature-description').val();
        this.detailModal.foundation('close');
        this.currentCallback(this.currentEntity);
    }

    cancelUpdate() {
        if(!this.currentEntity.id) {
            if(this.currentEntity instanceof Location) {
                this.currentEntity.marker.setMap(null);
                this.currentEntity = null;
            } else if (this.currentEntity instanceof District) {
                this.currentEntity.polygon.setMap(null);
                this.currentEntity = null;
            }
        }
        this.detailModal.foundation('close');
    }
}
