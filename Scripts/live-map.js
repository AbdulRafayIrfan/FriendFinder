// Constant watching of user's location and updating database via AJAX
if (navigator.geolocation) {
    navigator.geolocation.watchPosition(position => {
        // Get current timestamp of updated location of user
        // Convert it so it can be stored as timestamp in the Database by dividing by 1000
        let timestamp = Date.now() / 1000

        // AJAX call to update user's location in the DB every
        // json object to be sent
        let jsonObj = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            timestamp: timestamp
        }
        let post = JSON.stringify(jsonObj)

        // XHR object
        let xhr = new XMLHttpRequest()
        xhr.open('POST', "../Controller/update_location.php")
        xhr.setRequestHeader('Content-type', 'application/json')
        xhr.send(post)

        // Error handling here
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
            }
        }
    })
}





