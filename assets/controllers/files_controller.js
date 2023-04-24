import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["formMessage", "fileButton", "fileName"]

    connect() {
        this.fileButtonTarget.addEventListener("change", this.updateFileName)
    }

    updateFileName = () => {
        const formData = new FormData(this.formMessageTarget)
        let fileName = ''
        formData.forEach((value) => {
            if (value.name != undefined) {
                fileName += ' ' + value.name
            }
        })
        this.fileNameTarget.value = fileName
    }
}