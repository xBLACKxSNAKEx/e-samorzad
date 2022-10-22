let fileName;
let file;
let address = 'Polska';
let mail = '';

const setAddress = () => {
    let addr = document.getElementById('address').value;
    let type = document.getElementById('type').value;

    for (let ind = 0; ind < authorities.length; ind++) {
        if (authorities[ind].get('address_id') == addr && authorities[ind].get('type_id') == type) {
            address = authorities[ind].get('geo');
            changePanel('find-close');
            document.getElementById('address').value = addr;
            document.getElementById('type').value = type;
            document.getElementById('response').innerHTML = `nazwa: <b>${authorities[ind].get('name')}</b><br>adress e-mail: <b>${authorities[ind].get('mail')}</b>`;
            mail = authorities[ind].get('mail');
            document.getElementById('response').addEventListener('click', () => {
                changePanel('write-mail');
                document.getElementById('receiver-email').value = mail;
            });

            return 0;
        }
    }
    address = 'Polska';
    changePanel('find-close');
    document.getElementById('address').value = addr;
    document.getElementById('type').value = type;
    alert('Nie znaleziono odpowiadającej instytucji');

}

const fillValues = (arr) => {
    let result = '';
    for (let ind = 1; ind < arr.size + 1; ind++) {
        result += `'<option value="${ind}">${arr.get(`${ind}`)}</option>"`

    }
    return result;
};

const goBack = (mode) => {
    if (mode == 'main') {
        changePanel('')
    }
    else changePanel(mode);
}


let changePanel = (mode) => {
    const panel = document.getElementById('panel');
    if (mode == '') {
        panel.innerHTML = '<div id="find-close" class="panel-button"><i class="fa-solid fa-map-location-dot"></i><span class="panel-label">Znajdź instytucję w twojej okolicy</span></div><div id="write-mail" class="panel-button"><i class="fa-solid fa-envelope-open-text"></i><span class="panel-label">Skontaktuj się z instytucją</span></div>';
        const data = ['find-close', 'write-mail'];
        for (let ind = 0; ind < data.length; ind++) {
            document.getElementById(data[ind]).addEventListener('click', () => { changePanel(data[ind]) });
        }

    }

    if (mode == 'find-close') {
        panel.innerHTML = `<span id="return"><i class="fa-solid fa-arrow-left-long"></i>Wróć do poprzedniej karty</span><div class="address-wrapper"><label class="first" for="address">Wybierz miejscowość:</label><br><select id="address" name="address">${fillValues(addresses)}</select><br><label for="type">Wybierz typ instytucji:</label><br><select id="type" name="type">${fillValues(types)}</select><div id="find-institution">Szukaj</div><p id="response"></p></div><div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="100%" id="gmap_canvas" src="https://maps.google.com/maps?q=${address}&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://123movies-to.org"></a><br><style>.mapouter{margin-top: 6vh; position:relative;text-align:right;height:60vh}</style><a href="https://www.embedgooglemap.net">google map for my website</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:60vh;width:100%}</style></div></div>`;

        document.getElementById('return').addEventListener('click', () => { goBack('main') });
        document.getElementById('find-institution').addEventListener('click', () => { setAddress() });
    }

    if (mode == 'write-mail') {
        panel.innerHTML = '<span id="return"><i class="fa-solid fa-arrow-left-long"></i>Wróć do poprzedniej karty</span><div id="load-file" class="panel-button"><i class="fa-solid fa-upload"></i><span class="panel-label">Prześlij plik ze swojego urządzenia</span><input type="file" name="panel-file-input" id="panel-file-input" form="send-mail" accept=".txt,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"></div><div id="text-editor-button" class="panel-button"><i class="fa-solid fa-keyboard"></i><span class="panel-label">Skorzystaj z naszego edytora tekstu</span></div>';

        document.getElementById('return').addEventListener('click', () => { goBack('main') });

        document.getElementById('panel-file-input').addEventListener('change', () => {
            console.log(document.getElementById('panel-file-input').files[0]);
            fileName = document.getElementById('panel-file-input').files[0].name;
            file = document.getElementById('panel-file-input').files[0];

            reader.readAsText(document.getElementById('panel-file-input').files[0]);
        });

        document.getElementById('text-editor-button').addEventListener('click', () => { changePanel('text-editor') });

        const reader = new FileReader();
        reader.addEventListener('loadend', () => {
            if (document.getElementById('panel-file-input').files[0].name.split('.')[1] == 'txt') {
                changePanel('text-editor');
                document.getElementById('text-editor').value = reader.result;
            } else {
                changePanel('its-word');
            }

        });


    }

    if (mode == 'text-editor') {
        panel.innerHTML = '<span id="return"><i class="fa-solid fa-arrow-left-long"></i>Wróć do poprzedniej karty</span><textarea name="text-editor" id="text-editor" form="send-mail"></textarea><div class="text-editor-panel"><form id="send-mail" name="send-mail" method="post" action="send_mail.php" class="text-editor-panel"><label for="sender-email">Adres e-mail wysyłającego:</label><br><input type="email" name="sender-email" id="sender-email" required><br><label for="receiver-email">Adres e-mail instytucji:</label><br><input type="email" name="receiver-email" id="receiver-email" required><br><input id="send-mail-submit" type="submit" value="Wyślij"></form></div>';

        document.getElementById('return').addEventListener('click', () => { goBack('write-mail') });
        document.getElementById('receiver-email').value = mail;

        const reader = new FileReader();
        reader.addEventListener('loadend', () => { alert(reader.result) });


    }

    if (mode == 'its-word') {
        panel.innerHTML = `<span id="return"><i class="fa-solid fa-arrow-left-long"></i>Wróć do poprzedniej karty</span><div id="word"><i class="fa-solid fa-file-word"></i><span>${fileName}</span></div><div class="text-editor-panel"><form id="send-mail" name="send-mail" method="post" action="send_mail.php" class="text-editor-panel" enctype="multipart/form-data"><label for="sender-email">Adres e-mail wysyłającego:</label><br><input type="email" name="sender-email" id="sender-email" required><br><label for="receiver-email">Adres e-mail instytucji:</label><br><input type="email" name="receiver-email" id="receiver-email" required><br><input id="send-mail-submit" type="submit" value="Wyślij"><input form="send-mail" type="file" id="file-input-is-word" name="file-input-is-word" hidden></form></div>`;
        document.getElementById('receiver-email').value = mail;

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('file-input-is-word').files = dataTransfer.files;

        document.getElementById('return').addEventListener('click', () => { goBack('write-mail') });

        const reader = new FileReader();
        reader.addEventListener('loadend', () => { alert(reader.result) });


    }
};

window.addEventListener('load', () => {
    let mode = '';
    changePanel(mode);
});