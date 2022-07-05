/** The functionality of this class is to provide re-usable methods provided
 * for use on any form */
class FormValidation {
    /**
     * This method checks an input field by allowing only letters and certain
     * special character typically found in a full name
     * @param _field The input form field
     * @returns {boolean} Returns true or false depending on the regx.test
     */
    isName(_field) {
        let text = _field.value
        let regx = /^[A-Za-z\s.']+$/
        return regx.test(text)
    }
    /**
     * This method checks if the input field is only letters and numbers
     * @param _field The input form field
     * @returns {boolean} Returns true or false depending on the regx.test
     */
    isLettersandNum(_field) {
        let text = _field.value
        let regx = /^[A-Za-z0-9]+$/
        return regx.test(text)
    }
    /**
     * The isEmail method checks if the input field contains a valid email
     * @param _field The input form field
     * @returns {boolean} Returns true or false depending on the regx.test
     */
    isEmail(_field) {
        let email = _field.value
        let regx = /(.+)@(.+){2,}\.(.+){2,}/
        return regx.test(email)
    }/**
     * This field takes two parameters and checks the text containing in a field is
     * shorter than the provided parameter
     * @param _field The form field
     * @param len An integer value of length to compare with the field's length
     */
    isShort(_field, len) {
        return _field.value.length < len
    }
}

export default FormValidation