// Live Map Script and Icons script
import LiveMapIcon from "../Scripts/Modules/live-map-icon.js"
import LeafletMap from "../Scripts/Modules/leaflet-map.js"

// Instantiate new live map
let livemap = new LeafletMap();

// Dictionary for all markers
let markers = {}

// Map updates periodically (10secs) with new friends location set by markers
// Get JSON response of friends location from Database
// Append friend's location as markers to livemap
// Repeat periodically

refreshMarkers()
setInterval(() => {
    refreshMarkers()
}, 10000)

function refreshMarkers() {
    // Count variable for counting JSON response objects
    let count = 0

    // Get JSON response
    let xhr = new XMLHttpRequest()
    xhr.open('GET', "get_friends_location.php",true)
    xhr.send()

    // On successful response
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const jsonResponse = JSON.parse(xhr.responseText)
            // Loop through whole JSON response
            while (jsonResponse[count] !== undefined) {
                // Check if the marker already exists
                if (!markers[jsonResponse[count]['userID']]) {
                    // Create icon with friend's profile picture
                    let friendIcon = new LiveMapIcon(jsonResponse[count]['profile_image'])
                    // Add a marker with the created icon on to the map and add it to the layer group
                    let customMarker = livemap.addCustomMarker(jsonResponse[count]['latitude'], jsonResponse[count]['longitude'], friendIcon.getIcon(), livemap.getMap())
                    // Add tooltip to the marker displaying how long ago the marker location was updated
                    livemap.addMarkerTooltip(customMarker, jsonResponse[count]['username']+" "+calculateLastUpdated(jsonResponse[count]['last_updated']))
                    // Set map to fly to location when user clicks on marker
                    livemap.onClickFly(customMarker)
                    // Add the marker to the list of markers
                    markers[jsonResponse[count]['userID']] = customMarker
                    count++
                } else {
                    // Otherwise just update the tooltip and position of the marker
                    livemap.updateMarkerTooltip(markers[jsonResponse[count]['userID']], jsonResponse[count]['username']+" "+calculateLastUpdated(jsonResponse[count]['last_updated']))
                    livemap.changeMarkerPos(jsonResponse[count]['latitude'], jsonResponse[count]['longitude'], markers[jsonResponse[count]['userID']])
                    count++
                }
            }
        }
    }

}

/**
 * Calculate the time difference for when the location was last updated
 * @param secs Time in seconds for when the location was updated
 */
function calculateLastUpdated(secs) {
    let secondsDiff = Math.abs((Date.now() /1000) - secs)

    // Divide secondsDiff with number of seconds in a year to allow checking
    let x = secondsDiff / 31536000

    // Check if it more than a year
    if (x > 1) {
        return "was last seen here " + Math.floor(x) + " years ago"
    }

    // Divide with number of secs in a month
    x = secondsDiff / 2592000
    if (x > 1) {
        return "was last seen here " + Math.floor(x) + " months ago"
    }

    // Divide with number of seconds in a day
    x = secondsDiff / 86400;
    if (x > 1) {
        return "was last seen here " + Math.floor(x) + " days ago";
    }

    // Divide with number of seconds in an hour
    x = secondsDiff / 3600;
    if (x > 1) {
        return "was last seen here " + Math.floor(x) + " hours ago";
    }

    // Divide with number of seconds in a minute
    x = secondsDiff / 60;
    if (x > 1) {
        return "was last seen here " + Math.floor(x) + " minutes ago";
    }

    // If seconds is 0 return 'just now'
    if (Math.floor(x) === 0) return "was last seen here just now";

    // Else just return the number of seconds
    return "was last seen here " + Math.floor(x) + " seconds ago";
}