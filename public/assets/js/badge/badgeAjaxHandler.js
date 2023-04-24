/**
 *
 * @typedef {Object} BadgeFormResponse
 * @property {string} code
 * @property {Object} errors
 * @property {string} html
 */

ajaxBadgeSubmission('form_createBadge');
ajaxBadgeSubmission('form_editBadge');

function ajaxBadgeSubmission(formId) {
    const formCreateBadge = document.querySelector('#' + formId);
    const badgesList = document.querySelector('#badges_list');


    formCreateBadge.addEventListener('submit', async function (e) {
        e.preventDefault();

        const response = await fetch(this.action, {
            body: new FormData(e.target),
            method: 'POST'
        })
        const json = await response.json();
        handleResponse(json);

        await
            setTimeout(function () {
                updateBadgeSelectForm();
                deleteBadgeEventListener();
                editBadgeManager();
            }, 1000);
    });

    /**
     *
     * @param {BadgeFormResponse} response
     */
    const handleResponse = function (response) {
        removeErrors();
        switch (response.code) {
            case 'BADGE_ADDED_SUCCESSFULLY':
                badgesList.innerHTML = '';
                badgesList.innerHTML = response.html;
                break;
            case 'BADGE_UPDATED_SUCCESSFULLY':
                badgesList.innerHTML = '';
                badgesList.innerHTML = response.html;
                break;
            case 'BADGE_INVALID_FORM':
                handleErrors(response.errors);
                break;
        }
    }

    const removeErrors = function () {
        const invalidFeedbackElements = document.querySelectorAll('.invalid-feedback');
        const isInvalidElements = document.querySelectorAll('.is-invalid');

        invalidFeedbackElements.forEach(errorElement => errorElement.remove());
        isInvalidElements.forEach(isInvalidElement => isInvalidElement.classList.remove('is-invalid'));
    }

    /**
     *
     * @param {Object} errors
     */
    const handleErrors = function (errors) {
        if (errors.length === 0) return;

        for (const key in errors) {
            let element = document.querySelector(`#badge_${key}`);
            element.classList.add('is-invalid');

            let div = document.createElement('div');
            div.classList.add('invalid-feedback', 'd-block');
            div.innerText = errors[key];

            element.after(div);
        }
    }
}