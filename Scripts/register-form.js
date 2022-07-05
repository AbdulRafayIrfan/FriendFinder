import FormValidation from './Modules/form-validation.js'

// Assign all the form fields to variables
const form = document.querySelector('#registerForm')
const email = form.querySelector('#email')
const password = form.querySelector('#password')
const fullName = form.querySelector('#fullname')
const username = form.querySelector('#username')
const allHelpTexts = form.querySelectorAll('.form-text')

// Flags for valid/invalid fields
let nameCorrect, emailCorrect, passCorrect, usernameCorrect
nameCorrect = emailCorrect = passCorrect = usernameCorrect = false
// Instantiate form validation
let fv = new FormValidation();

/**
 * This function changes the CSS for the form field
 * when the input field is valid, displaying green colour
 * with an appropriate message
 * @param _helpText The message below the form field
 * @param _icon The tick mark icon
 */
function setSuccess(_helpText, _icon) {
    _helpText.innerHTML = 'Looks good!'
    _helpText.style.color = 'green'
    _icon.className = 'bi bi-check'
    _icon.style.color = 'green'
}

/**
 * This function changes the CSS for the form field
 * when the input field is invalid, displaying red colour
 * with an appropriate message
 * @param _helpText he message below the form field
 * @param _msg a custom error message to inform the user
 * @param _icon The red warning icon
 */
function setFailure(_helpText, _msg, _icon) {
    _helpText.innerHTML = _msg
    _helpText.style.color = 'red'
    _icon.className = 'bi bi-exclamation'
    _icon.style.color = 'red'
}

// EVENT LISTENERS FOR EACH FORM FIELD
fullName.addEventListener('input',() => {
    let helpText = fullName.nextElementSibling
    let icon = fullName.previousElementSibling
    icon.style.display = 'inline-block'
    if (fv.isName(fullName)) {
        setSuccess(helpText, icon)
        nameCorrect = true
    } else {
        setFailure(helpText, "Invalid name!", icon)
        nameCorrect = false
    }
})

// Event listener for the email field to utilize the validation class
email.addEventListener('input',() => {
    let helpText = email.nextElementSibling
    let icon = email.previousElementSibling
    icon.style.display = 'inline-block'
    if (fv.isEmail(email)) {
        setSuccess(helpText, icon)
        emailCorrect = true
    } else {
        setFailure(helpText, "Invalid email!", icon)
        emailCorrect = false
    }
})

// Event listener for the username field to utilize the validation class
username.addEventListener('input',() => {
    let helpText = username.nextElementSibling
    let icon = username.previousElementSibling
    icon.style.display = 'inline-block'
    if (fv.isLettersandNum(username)) {
        setSuccess(helpText, icon)
        usernameCorrect = true
    } else {
        setFailure(helpText, "Invalid username!", icon)
        usernameCorrect = false
    }
})

// Event listener for the password field to utilize the validation class
password.addEventListener('input',() => {
    let helpText = password.nextElementSibling
    let icon = password.previousElementSibling
    icon.style.display = 'inline-block'
    // Check if length of password entered is short
    if (fv.isShort(password, 6)) {
        setFailure(helpText, "Password must be at least 6 characters!", icon)
        passCorrect = false
    } else {
        setSuccess(helpText, icon)
        passCorrect = true
    }
})

// Prevent form submission accordingly
// Also the incorrect field(s) are animated when user attempts to submit with
// invalid fields
form.addEventListener('submit',(e) => {
    if (!(nameCorrect && emailCorrect && usernameCorrect && passCorrect)) {
        e.preventDefault()
        allHelpTexts.forEach((helptext)=> {
            if (helptext.style.color === 'red') {
                helptext.className = 'form-text shake'
                setTimeout(()=> {helptext.className = 'form-text'}, 600)
            }
        })
    }
})


