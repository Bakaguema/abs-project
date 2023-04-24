
function onClickBtnLike(event) {
    event.preventDefault();

    const url = this.href;
    const likeCount = this.querySelector('span.article__reaction-likeNumber');
    const icon = this.querySelector('i');

    axios.get(url).then(function (response) {
        likeCount.textContent = response.data.likes;

        if (icon.classList.contains('bxs-heart')) {
            icon.classList.replace('bxs-heart', 'bx-heart');
        } else {
            icon.classList.replace('bx-heart', 'bxs-heart');
        }
    }).catch(function (error) {
        if (error.response.status === 403) {
            window.alert("Vous ne pouvez pas aimer un article si vous n'êtes pas connecté.");
        } else {
            window.alert("Une erreur s'est produite, réessayer ultérieurement.");
        }
    });
}
document.querySelectorAll('a.article__reaction-like').forEach(function (link) {
    link.addEventListener('click', onClickBtnLike)
});

function onClickBtnSave(event) {
    event.preventDefault();

    const url = this.href;
    const saveCount = this.querySelector('span.article__reaction-saveNumber');
    const icon = this.querySelector('i');

    axios.get(url).then(function (response) {
        saveCount.textContent = response.data.saves;

        if (icon.classList.contains('bxs-bookmark')) {
            icon.classList.replace('bxs-bookmark', 'bx-bookmark');
        } else {
            icon.classList.replace('bx-bookmark', 'bxs-bookmark');
        }
    }).catch(function (error) {
        if (error.response.status === 403) {
            window.alert("Vous ne pouvez pas sauvegarder un article si vous n'êtes pas connecté.");
        } else {
            window.alert("Une erreur s'est produite, réessayer ultérieurement.");
        }
    });
}
document.querySelectorAll('a.article__reaction-save').forEach(function (link) {
    link.addEventListener('click', onClickBtnSave)
});