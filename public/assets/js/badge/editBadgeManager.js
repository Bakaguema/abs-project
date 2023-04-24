
function editBadgeManager() {
    // Get a reference to the badge edit links
    const badgeEditLinks = document.querySelectorAll('.badge-card-edit-btn');

    // Listen for clicks on the badge edit links
    badgeEditLinks.forEach(function (link) {
      link.addEventListener('click', displayBadgeModal(link.id, 'editBadgeModal', "editBadgeClose", "editBadgeModalSubmit"), false);

      link.addEventListener('click', function (event) {
        event.preventDefault();

        // Get the ID of the selected badge
        const badgeId = link.id.match(/\d+/)[0];
        // Make an AJAX request to retrieve the data for the selected badge
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            // Populate the form fields with the data for the selected badge
            document.getElementById('edit_badge_name').value = response.name;
            document.getElementById('edit_badge_description').value = response.description;
            document.getElementById('edit_badge_badgeCategory').value = response.badgeCategory;
            document.getElementById('edit_badge_threshold').value = response.threshold;
            document.getElementById('edit_badge_hiddenId').value = badgeId;

            inputPositiveValueAssurance('edit_badge_threshold');

            const imageInput = document.getElementById('edit_badge_image');
            const imagePreview = document.getElementById('editImgPreview');
            imagePreview.src = '/uploads/badges/' + response.image;

            imageInput.addEventListener('change', function (event) {
              const file = event.target.files[0];
              const reader = new FileReader();
              reader.addEventListener('load', function (event) {
                imagePreview.src = event.target.result;
              });
              reader.readAsDataURL(file);
            });
            badgeCategoryFormAdequation('edit_badge_badgeCategory', 'editbadgeThreshold', 'edit_badge_threshold');


          } else if (xhr.readyState === 4 && xhr.status !== 200) {
            alert('An error occurred while retrieving the data for the selected badge.');
          }
        };
        xhr.open('GET', '/admin/getBadge/' + badgeId, true);
        xhr.send();
      });
    });
}

function inputPositiveValueAssurance(inputId) {
  const thresholdInput = document.getElementById(inputId);

  thresholdInput.addEventListener('input', () => {
    if (thresholdInput.value < 0) {
      thresholdInput.value = 0;
    }
  }, false);
}

function badgeCategoryFormAdequation(sourceInputId, targetInputId, targetFieldId) {
  const categoryField = document.getElementById(sourceInputId);

  categoryField.addEventListener('change', function () {
    const selectedOption = categoryField.options[categoryField.selectedIndex].value;

    const thresholdInput = document.getElementById(targetFieldId);
    let thresholdField = document.getElementById(targetInputId);

    if (selectedOption === 'honor') {
      thresholdField.style.display = 'none';
      thresholdInput.required = false;
    } else {
      thresholdField.style.display = 'grid';
      thresholdInput.required = true;
    }
  });
}

function displayBadgeModal(idButton, idModal, idClose, idSubmit) {
  var modal = document.getElementById(idModal);
  var btn = document.getElementById(idButton);
  var span = document.getElementById(idClose);
  var submit = document.getElementById(idSubmit);

  btn.onclick = function () {
      modal.style.display = "flex";
  }

  span.onclick = function () {
      modal.style.display = "none";
  }

  submit.onclick = function () {
      modal.style.display = "none";
  }
  window.onclick = function (event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  }   
}

// function displayFile() {
//     const file = uploadedImg.files[0];
//     const fileUrl = URL.createObjectURL(file); // does not work on chrome outside of a secure context (https)
//     // display the file in an image tag or a video tag or whatever element you want
//     const imgPreview = document.getElementById('imgPreview');
//     imgPreview.src = fileUrl;
//     URL.revokeObjectURL(fileUrl);
// }
function displayFile() { // chrome alternative
  const file = uploadedImg.files[0];
  const reader = new FileReader();

  reader.onload = function (event) {
    const fileUrl = event.target.result;
    const imgPreview = document.getElementById('imgPreview');
    imgPreview.src = fileUrl;
  };

  reader.readAsDataURL(file);
}

function displayUserPicture() {

  const userProfilePicture = document.getElementById('user-profile-picture');
  const selectedUserId = this.value; // get the ID of the selected user
  const xhr = new XMLHttpRequest(); // create a new AJAX request
  xhr.open('GET', `/users/${selectedUserId}/profile-picture`); // set the URL to fetch the profile picture
  xhr.onload = function () {
      if (xhr.status === 200 && xhr.responseText !== null) { // if the request was successful
          userProfilePicture.src = '/uploads/profiles/' + xhr.responseText; // set the image source to the response
          // console.log(xhr.responseText);
          userProfilePicture.onerror = ()=>{
          userProfilePicture.src = '/assets/img/badge/placeholderprofilepic.png'; // if no file is found use a placeholder instead
          }
      }
  };
  xhr.send(); // send the request
}

document.getElementById('createBadgeModalButton').addEventListener('click', displayBadgeModal('createBadgeModalButton', 'createBadgeModal', "createBadgeClose", "createBadgeModalSubmit"), false);
document.getElementById('grantBadgeModalButton').addEventListener('click', displayBadgeModal('grantBadgeModalButton', 'grantBadgeModal', "grantBadgeClose", "grantBadgeModalSubmit"), false);
document.getElementById('revokeBadgeModalButton').addEventListener('click',displayBadgeModal('revokeBadgeModalButton', 'revokeBadgeModal',"revokeBadgeClose", "revokeBadgeModalSubmit"),false);
inputPositiveValueAssurance('create_badge_threshold');
badgeCategoryFormAdequation('create_badge_badgeCategory', 'badgeThreshold', 'create_badge_threshold');
editBadgeManager();

const uploadedImg = document.getElementById('create_badge_image');
uploadedImg.addEventListener('change', displayFile, false);

const userSelect = document.getElementById('grant_badge_user');

userSelect.addEventListener('change', displayUserPicture, false);

