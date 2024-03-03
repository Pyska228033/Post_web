function checkService() {
    var input = document.getElementById('service_input').value;
    var options = document.getElementById('services').getElementsByTagName('option');
    var match = false;
    for (var i = 0; i < options.length; i++) {
        if (options[i].value === input || input === '') {
            match = true;
            break;
        }
    }
    if (!match) {
        document.getElementById('error').textContent = 'Ошибка';
        document.getElementById('submitButton').disabled = true;
    }
    else {
        document.getElementById('error').textContent = '';
        document.getElementById('submitButton').disabled = false;
    }
}

function checkSenderpostoffice() {
    var input = document.getElementById('senderpostoffice_input').value;
    var options = document.getElementById('posts').getElementsByTagName('option');
    var match = false;
    for (var i = 0; i < options.length; i++) {
        if (options[i].value === input) {
            match = true;
            break;
        }
    }
    if (!match) {
        document.getElementById('error1').textContent = 'Ошибка';
        document.getElementById('submitButton').disabled = true;
    }
    else {
        document.getElementById('error1').textContent = '';
        document.getElementById('submitButton').disabled = false;
    }
}

function checkRecipientpostoffice() {
    var input = document.getElementById('recipientpostoffice_input').value;
    var options = document.getElementById('posts').getElementsByTagName('option');
    var match = false;
    for (var i = 0; i < options.length; i++) {
        if (options[i].value === input || input === '') {
            match = true;
            break;
        }
    }
    if (!match) {
        document.getElementById('error2').textContent = 'Ошибка';
        document.getElementById('submitButton').disabled = true;
    }
    else {
        document.getElementById('error2').textContent = '';
        document.getElementById('submitButton').disabled = false;
    }
}