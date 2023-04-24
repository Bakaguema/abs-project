//-----------------------Without SweetAlert2----------------------

// const delBadgeBtn = document.querySelectorAll('.badge-card-delete-btn');
// delBadgeBtn.forEach((badge) => {
//   badge.addEventListener('click', () => {
//     const badgeId = badge.id.match(/\d+/)[0]; // Get the ID of the clicked badge
//     console.log(badgeId);

//     // Display a confirmation pop-up
//     const confirmation = window.confirm(`Are you sure you want to delete badge ${badgeId}?`);
//     if (confirmation) {
//       // Send an AJAX request to delete the badge and update the list
//       const xhr = new XMLHttpRequest();
//       xhr.open('POST', '/admin/badge/'+badgeId+'/del', true);
//       xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
//       xhr.onreadystatechange = function() {
//         if (xhr.readyState == 4 && xhr.status == 200) {
//           // Update the list of badges (e.g. by reloading the page)
//           location.reload();
//         }
//       }
//       xhr.send(`badgeId=${badgeId}`);
//     }
//   });
// });


//-----------------------SweetAlert2 version----------------------

deleteBadgeEventListener();

function deleteBadgeEventListener() {
    let delButtons = document.querySelectorAll('.badge-card-delete-btn');
    for (let i = 0; i < delButtons.length; i++) {
        delButtons[i].addEventListener('click', () => {
            let badgeId = delButtons[i].id.match(/\d+/)[0];

            // Display a SweetAlert2 popup message
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                html: 'Attention vous êtes sur le point de supprimer le badge n°' + badgeId + ' !<br>Cette action est irréversible',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send an AJAX request to delete the badge
                    fetch(`/admin/badge/${badgeId}/del`, {
                        method: 'DELETE'
                    }).then(response => {
                        if (response.ok) {
                            // Remove the deleted badge from the DOM
                            delButtons[i].closest('.badge-card').remove();
                    
                            return response.json();
                        } else {
                            throw new Error('Network connexion error');
                        }
                    }).then(data => {
                        // Display a success message
                        Swal.fire({
                            title: 'Succès !',
                            text: data.message, // Access the message property
                            icon: 'success'
                        });
                    
                        // Reloading the options of our select with the updated list of badges
                        updateBadgeSelectForm();
                    }).catch(error => {
                        // Display an error message
                        Swal.fire({
                            title: 'Erreur!',
                            text: error.message,
                            icon: 'error'
                        });
                    });
                }
            });
        });
    }
}

function updateBadgeSelectForm() {
    fetch('/admin/badgesList')
        .then(response => response.json())
        .then(badges => {
            // Update the HTML of the select element with the new options
            let selectElement = document.getElementById('grant_badge_badge');
            selectElement.innerHTML = '';
            badges.forEach(badge => {
                if (badge.category === 'honor') {
                    let option = document.createElement('option');
                    option.value = badge.id;
                    option.text = badge.name;
                    selectElement.appendChild(option);
                }
            });
        });
}