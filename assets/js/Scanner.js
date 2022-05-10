import {Ajax} from "./Ajax";
import {toast} from "./src/toast"
import {alert} from "./src/alert";
import {FormValidate} from "./formValidate";
import {butloading, generateTableRow} from "./src/libs";

class Scanner extends Ajax {

    static setToken = '/scanner/api/setToken';
    static removeToken = '/scanner/api/removeToken';

    static getServerTime = 'https://api.vk.com/method/utils.getServerTime';
    static regex = /access_token=([a-z0-9]+)&expires_in=/;

    constructor() {
        const informationForm = document.querySelector('#addToken')

        if (informationForm) {
            informationForm.addEventListener('submit', (event) => {
                event.preventDefault();
                Scanner.authorized(event);
            });
        }
        super();
    }

    static authorized(event) {
        let accessToken = new FormData(event.target).get('access_token');

        if(this.regex.exec(accessToken)){
            accessToken = this.regex.exec(accessToken)[1];

            this.vk(this.getServerTime, this.formData({access_token: accessToken, v: 5.161 }), {
                onDone: function (result) {
                    Scanner.addToken(accessToken);
                },
                onFail: function (error) {
                    alert("Токен не верный", 'warning');
                },
            });
        } else alert("Введите ссылку с адресной строки, как на скрине выше.", 'warning');
    }

    static addToken(accessToken){
        this.post(this.setToken, this.formData({access_token: accessToken}), {
            onDone: function (result) {
                alert(result.messages, 'success')

                setInterval(function (){
                    return window.location.href = "/scanner/friends";
                }, 3000)
            },
            onFail: function (error) {
                alert(error.messages, 'warning')
            },
        });
    }
}

new Scanner();
