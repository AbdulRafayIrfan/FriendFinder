class LeafletMap {
    /**
     * The constructor method creates a new map displaying the whole world initially
     * Also a locate control button is added to the map to allow user to show their
     * own location
     */
    constructor() {
        let map = L.map('map').fitWorld()
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map)
        // Locate control button in the map
        L.control.locate({flyTo: true}).addTo(map)
        this.map = map
    }

    /**
     * @returns map The map of the particular instance of the leaflet map
     */
    getMap() {
        return this.map
    }

    /**
     * Creates a custom marker with location details and an icon
     * @param _lat Latitude of the marker
     * @param _long Longitude of the marker
     * @param _icon The icon of the marker
     * @param _map The map to which it appends the marker
     */
    addCustomMarker(_lat, _long, _icon, _map, _popup) {
        return L.marker([_lat, _long], {icon: _icon}).addTo(_map)
    }

    /**
     * This method creates a tool tip for the provided marker with the provided message
     * @param _marker The marker
     * @param _tooltipMsg The message to be added in the tool tip
     */
    addMarkerTooltip(_marker, _tooltipMsg) {
        _marker.bindTooltip(_tooltipMsg)
    }

    /**
     * This method updates the existing tool tip of a marker with the new provided message
     * @param _marker The marker on the map
     * @param _tooltipMsg The updated tool tip message
     */
    updateMarkerTooltip(_marker, _tooltipMsg) {
        _marker.setTooltipContent(_tooltipMsg)
    }

    /**
     * This method updates the position of an existing marker on a map
     * @param _latitude The new latitude of the marker
     * @param _longitude The new longitude of the marker
     * @param _marker The marker to be updated
     */
    changeMarkerPos(_latitude, _longitude, _marker) {
        let cords = L.latLng(_latitude, _longitude)
        _marker.setLatLng(cords)
    }

    /**
     * This method adds an onclick function to a marker on a map, once clicked
     * the map does a smooth fly animation towards the location of the clicked marker
     * @param _marker
     */
    onClickFly(_marker) {
        _marker.on('click', (e) => {
            this.map.flyTo(e.latlng, 16)
        })
    }

}

export default LeafletMap