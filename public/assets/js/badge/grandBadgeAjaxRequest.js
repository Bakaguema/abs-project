grantBadgeAjaxRequest();

function grantBadgeAjaxRequest() {
    let grantBadgeForm = document.getElementById('form_grantBadge');

    grantBadgeForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetch('/admin/badge/grant', {
            method: 'POST',
            body: new FormData(grantBadgeForm)
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