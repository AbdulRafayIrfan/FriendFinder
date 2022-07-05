
/**
 * This function takes in a search term which is then sent to the database via
 * an AJAX request and is queried
 * The results are appended below the search bar and displayed appropriately
 * @param search_term
 */
function liveSearch(search_term) {

    // If search bar is empty, any previously existing dropdown is removed and function exited
    if (search_term === "") {
        removeDropdown()
        return
    }

    // Initialize AJAX connections
    let xhr = new XMLHttpRequest()

    // Count variable for counting JSON response objects
    let count = 0

    xhr.open('GET', "connect_ajax.php?q=" + search_term, true)
    xhr.send()

    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            // JSON parse error handling, remove, if existing, the dropdown and exit function
            if (!verifyJSON(xhr.responseText)) {
                removeDropdown()
                return
            }
            // Assign variable to parsed JSON response
            const jsonResponse = JSON.parse(xhr.responseText)
            // Remove the drop down initially (if existing) and replace with new one
            removeDropdown()
            // Unordered list created to display all users within response
            let ul = document.createElement('ul')
            ul.setAttribute("id", "live-search-dropdown")
            document.querySelector("body").append(ul)
            // Loop through the JSON response for each user, append responses to list accordingly
            while (jsonResponse[count] !== undefined) {
                // List tag
                let li = document.createElement('li')
                li.setAttribute("id", "live-search-result")
                let path = `../Controller/user_profile.php?usr=${jsonResponse[count]["userID"]}`
                li.addEventListener("click", () => {location.href=path})
                // Anchor tag
                let a = document.createElement('a')
                a.setAttribute("id", "live-result-anchor")
                // Img tag
                let img = document.createElement('img')
                img.setAttribute("id", "live-profile-image")
                img.style.width = "55px"
                img.src = jsonResponse[count]["profile_image"]
                a.append(img)
                // Bold part of result matching the search term, whether from username or full name
                let uname = jsonResponse[count]["username"]
                let fname = jsonResponse[count]["full_name"]
                let regexp = new RegExp(search_term, "i")
                if (uname.includes(search_term)) {
                    uname = uname.replace(regexp, "<b>"+search_term+"</b>")
                } else {
                    fname = fname.replace(regexp, "<b>"+search_term+"</b>")
                }
                // Span tag with the username
                let span1 = document.createElement('span')
                span1.setAttribute("id", "span-username")
                span1.innerHTML = uname
                // Span tag with the full name
                let span2 = document.createElement('span')
                span2.setAttribute("id", "span-full-name")
                span2.innerHTML = fname
                // append anchor tag and to unordered list
                a.append(span1, span2)
                li.append(a)
                ul.append(li)
                // Count increment to do same for next user in response
                count++
            }
        }
    }
}

/**
 * This function verifies the response catching any errors when
 * performing 'JSON.parse'
 * @param response The response from the XHR
 * @returns {boolean} True/False depending if any errors were caught
 */
function verifyJSON(response) {
    try {
        JSON.parse(response)
    } catch (e) {
        return false
    }
    return true
}

/**
 * This function removes the dropdown list (if existing) of results as and when
 * it is called
 */
function removeDropdown() {
    if (document.querySelector("#live-search-dropdown") !== null) {
        document.querySelector("#live-search-dropdown").remove()
    }
}