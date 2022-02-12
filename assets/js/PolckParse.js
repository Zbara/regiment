import {Ajax} from "./Ajax";
import {toast} from "./src/toast"
import {alert} from "./src/alert";
import {FormValidate} from "./formValidate";
import {butloading, generateTableRow} from "./src/libs";

class PolckParse extends Ajax {

    static getApi = '/friends/get';

    constructor() {
        const informationForm = document.querySelector('#informationForm')

        if (informationForm) {
            informationForm.addEventListener('submit', (event) => {
                event.preventDefault();

                PolckParse.information(event);
            });
        }
        super();
    }

    static information(event) {
        let formData = new FormData(event.target);
        let replaceTable = document.querySelector('#replaceTable');

        if (FormValidate.isValid(formData)) {
            this.post(this.getApi, formData, {
                onDone: function (result) {
                   const userInfo = document.querySelector('#userInfo');

                    userInfo.innerHTML = result.html;
                },
                onFail: function (error) {
                    alert(error.messages, 'warning')
                },
                showProgress: function () {
                    butloading(event.submitter, true)
                },
                hideProgress: function () {
                    butloading(event.submitter, false, 'Показать информацию')
                }
            });
        }
    }
}
new PolckParse();
