class LiveMapIcon {
    /**
     * The constructor involves creation of the icon with a size of 40x40 with the
     * url of the provided image
     * @param iconURL
     */
    constructor(iconURL) {
        let myIcon = L.icon({
            iconUrl: iconURL,
            iconSize: [40, 40]
        });
        this.icon = myIcon
    }

    /**
     * @returns icon The icon within this instance
     */
    getIcon() {
        return this.icon
    }
}

export default LiveMapIcon