  // Get a reference to the user select element
  const revokeUserSelect = document.querySelector('#revoke_badge_user');
  
  // Get a reference to the badge select element
  const revokeBadgeSelect = document.querySelector('#revoke_badge_badge');
  
  // Listen for changes to the user select element
  revokeUserSelect.addEventListener('change', function() {
    // Get the ID of the selected user
    const userId = revokeUserSelect.value;
	revokeBadgeSelect.innerHTML = '';
    
    // Make an AJAX request to your Symfony controller to retrieve the list of badges that the selected user owns
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/admin/userBadgesList/' + userId);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
		if(response.badges.length !== 0){
			revokeBadgeSelect.disabled = false;
        // Update the options of the badge select element with the new list of badges
        const options = response.badges.map(badge => `<option value="${badge.id}">${badge.name}</option>`).join('');
        revokeBadgeSelect.innerHTML = options;}
		else{
			revokeBadgeSelect.innerHTML = '<option value="-1">Aucun badge disponible</option>';
			revokeBadgeSelect.disabled = true;
		}
      } else {
        alert('An error occurred while retrieving the badges for the selected user.');
      }
    };
    xhr.send();
  });
  revokeBadgeAjaxRequest()

  function revokeBadgeAjaxRequest() {
    let revokeBadgeForm = document.getElementById('form_revokeBadge');

    revokeBadgeForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetch('/admin/badge/revoke', {
            method: 'POST',
            body: new FormData(revokeBadgeForm)
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    Swal.fire({
                        title: 'Succès !',
                        text: data.message,
                        icon: 'success'
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => console.error(error));
    });
}