
function validatePassword() {
    var password = document.getElementById('password').value;
    var regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}$/;
    if (!regex.test(password)) {
        alert('Длина пароля должна составлять не менее 10 символов и включать буквы и цифры.');
        return false;
    }
    return true;
}

document.querySelector('form').addEventListener('submit', function (event) {
    if (!validatePassword()) {
        event.preventDefault();
    }
});