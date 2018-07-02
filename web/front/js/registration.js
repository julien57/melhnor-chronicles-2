const form1 = document.getElementById('form1');
const form2 = document.getElementById('form2');
const form3 = document.getElementById('form3');

let registration = {
    verify_input: () => {

        document.getElementById('error_input').innerHTML = '';

        const pseudo = document.getElementById('registration_username').value;
        const password = document.getElementById('registration_password').value;
        const mail = document.getElementById('registration_mail').value;

        if (pseudo.length >= 3 && password.length >= 5 && mail !== '') {

            form1.style.display = 'none';
            form2.style.display = 'block';
        } else {

            const pElt = document.createElement('p');
            pElt.textContent = 'Veuillez remplir tous les champs...';

            document.getElementById('error_input').appendChild(pElt);
        }
    },
    init_avatar: () => {

        const img = document.createElement('img');
        img.src = '/web/front/img/avatars/adorateurs1.jpg';
        img.id = 'image_avatar';
        img.style.borderRadius = '5px';
        img.style.boxShadow = '3px 3px 5px black';

        windowAvatar.appendChild(img);
    },
    choice_avatar: (e, imgAvatar) => {
        const windowAvatar = document.getElementById('img_avatars');
        const nameValue = e.target.value;

        if (nameValue >= 1 && nameValue <= 5) {
            imgAvatar.src = '/web/front/img/avatars/adorateurs' + nameValue + '.jpg';

        } else if (nameValue > 5 && nameValue <= 16) {
            imgAvatar.src = '/web/front/img/avatars/redempteurs' + nameValue + '.jpg';

        } else if (nameValue > 16 && nameValue <= 34) {
            imgAvatar.src = '/web/front/img/avatars/nains' + nameValue + '.jpg';

        } else if (nameValue > 34 && nameValue <= 47) {
            imgAvatar.src = '/web/front/img/avatars/nao-oitte' + e.target.value + '.jpg';

        } else if (nameValue > 47 && nameValue <= 56) {
            imgAvatar.src = '/web/front/img/avatars/marchands' + nameValue + '.jpg';

        } else if (nameValue > 56 && nameValue <= 65) {
            imgAvatar.src = '/web/front/img/avatars/horde' + nameValue + '.jpg';

        } else if (nameValue > 65 && nameValue <= 80) {
            imgAvatar.src = '/web/front/img/avatars/pirates' + nameValue + '.jpg';

        } else if (nameValue > 80 && nameValue <= 100) {
            imgAvatar.src = '/web/front/img/avatars/lumiere' + nameValue + '.jpg';

        } else if (nameValue > 100 && nameValue <= 120) {
            imgAvatar.src = '/web/front/img/avatars/elfe' + nameValue + '.jpg';

        }

        windowAvatar.appendChild(imgAvatar);
    }
};

if (form2) {
    form2.style.display = 'none';
    document.getElementById('next1').addEventListener('click', function () {
        registration.verify_input();
    });
}

if (form3) {
    form3.style.display = 'none';
    document.getElementById('next2').addEventListener('click', function () {
        form2.style.display = 'none';
        form3.style.display = 'block';
    });
}


// Choice avatar

const windowAvatar = document.getElementById('img_avatars');
const nameAvatar = document.getElementById('registration_avatar');

nameAvatar.addEventListener('change', function (e) {
    registration.choice_avatar(e, imgAvatar);
});

registration.init_avatar();

const imgAvatar = document.getElementById('image_avatar');