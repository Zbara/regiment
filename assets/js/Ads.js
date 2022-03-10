import {Ajax} from "./Ajax";
import {toast} from "./src/toast"
import {alert} from "./src/alert";
import {FormValidate} from "./formValidate";
import {butloading, generateTableRow} from "./src/libs";

class Ads extends Ajax {
    static addApi = '/ads';
    static deleteApi = '/ads/remove';

    constructor() {
        const adsForm = document.querySelector('#adsForm')
        const deleteBtn = document.querySelectorAll('#delete-ads');

        if (adsForm) {
            adsForm.addEventListener('submit', (event) => {
                event.preventDefault();

                Ads.created(event);
            });
            FormValidate.isError(adsForm);
        }
        if (deleteBtn) {
            deleteBtn.forEach(function (item) {
                item.addEventListener("click", function () {
                    Ads.delete(this.parentElement.parentNode)
                });
            });
        }
        super();
    }

    static delete(el) {
        this.post(this.deleteApi, this.formData({
            id: el.dataset.adsId ?? 0
        }), {
            onDone: function (result) {
                if (result.messages) {
                    toast('Отлично', result.messages);
                }
                el.remove();
            },
            onFail: function (error) {
                toast('Ошибка', error.messages);
            }
        });
    }

    static created(event) {
        let formData = new FormData(event.target);
        let adsTable = document.querySelector('#adsTable');

        if (FormValidate.isValid(formData)) {
            this.post(this.addApi, formData, {
                onDone: function (result) {
                    event.target.reset();
                    adsTable.insertAdjacentHTML(
                        "afterbegin", result.save);

                    alert(result.messages, 'success')
                },
                onFail: function (error) {
                    FormValidate.getErrorForm(error.messages, event.target);
                },
                showProgress: function () {
                    butloading(event.submitter, true)
                },
                hideProgress: function () {
                    butloading(event.submitter, false, 'Добавить')
                }
            });
        }
    }
}
new Ads();
