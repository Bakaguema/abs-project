
//Input de l'image de profil
if (document.querySelector('#register_picture') !== null) {
    var inputPicture = document.querySelector('#register_picture');
} else {
    var inputPicture = document.querySelector('#user_picture');
}
//Masque l'input 
inputPicture.style.display = "none";
////////Gerer modal///////
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> and valider element that closes the modal
var span = document.getElementsByClassName("close")[0];
var valider = document.getElementById("valider");

// When the user clicks the button, open the modal
btn.onclick = function () {
    modal.style.display = "block";
}
// When the user clicks on calider, close the modal
valider.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

//// Changer le label de l'input 
inputPicture.addEventListener("click", function () {
    FileChanger();
});
function FileChanger() {
    let fichierSelectionne = inputPicture.files[0];
    let fichierselectionneold = document.getElementById('labelfile').innerHTML;
    if ((fichierSelectionne != null) && (fichierSelectionne.name != fichierselectionneold)) {
        document.getElementById('labelfile').innerHTML = fichierSelectionne.name;
    } else {
        setTimeout(FileChanger, 3000);
    }
};

////////////////Gerer les images///////////

function chargerImageApercu() {
    // Generer l'apercu
    var img_preview = document.querySelector('#img-preview');
    console.log(img_preview);
    loadInputFieldToPreview(img_preview);
}

function loadAvatar() {
    // Au changement de selection d'avatar
    var url = document.querySelector('input[name="avatar"]:checked').value;
    loadURLToInputField(url)
}
inputPicture.addEventListener('change', function () {
    //Lors de la selection d'une image dans les fichiers
    chargerImageApercu();
});

function loadURLToInputField(url) {
    // Créer un nouveau fichier input image avec l'url de l'avatar selectionné et le met dans l'input de selection d'image du profil
    // Pour des raisons de sécurité on ne peut pas charger directement une url dans un input de type file car il est en readonly
    getImgURL(url, (imgBlob) => { // Load img blob to input equivalent d'un .then
        console.log(imgBlob);
        let fileName = 'commeFichier.jpg' //should .replace(/[/\\?%*:|"<>]/g, '-') for remove special char like / \
        let file = new File([imgBlob], fileName, {
            type: "image/jpeg",
            lastModified: new Date().getTime()
        }, 'utf-8');
        let container = new DataTransfer();
        container.items.add(file);
        inputPicture.files = container.files;
        // document.querySelector('#status').files = container.files;
        chargerImageApercu();
    })
}

function loadInputFieldToPreview(imgElement) { // Load preview to img tag
    var reader = new FileReader();
    reader.onload = function (e) {
        imgElement.src = e.target.result
    }
    reader.readAsDataURL(inputPicture.files[0]); // convert to base64 string
}

// xml blob res
function getImgURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        callback(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}
