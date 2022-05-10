import {Ajax} from "./Ajax";
import {toast} from "./src/toast"
import {alert} from "./src/alert";
import {FormValidate} from "./formValidate";
import {butloading, generateTableRow} from "./src/libs";
import {chunk} from "./src/chunk";


class ScannerFriends extends Ajax {

    /** vk api **/
    static getExecute = 'https://api.vk.com/method/execute';

    /** backend api **/
    static getUsers = '/scanner/api/getUsers';


    static friends = [];
    static users = [];

    constructor() {
        const informationForm = document.querySelector('#scannerForm')

        if (informationForm) {
            informationForm.addEventListener('submit', (event) => {
                event.preventDefault();
                ScannerFriends.init();
            });
        }
        super();
    }

    static init() {
        let code = 'var counters = API.users.get({"fields": "counters"});'
            + 'var members = API.friends.get({"count": "1000", "offset": 0}).items;'
            + 'var offset = 1000;'
            + 'while (offset < counters[0].counters.friends && (offset + 0) < 10000)'
            + '{'
            + 'members = members + API.friends.get({"count": "1000", "offset": (0 + offset)}).items;'
            + 'offset = offset + 1000;'
            + '};'
            + 'return members;';

        this.vk(this.getExecute, this.formData({access_token: window.access_token, v: 5.161, code: code}), {
            onDone: function (result) {
                ScannerFriends.friends = chunk(result, 30);

                 ScannerFriends.friends.forEach(function (users, key) {
                      ScannerFriends.loadUsers(users, key).then(r => function (el) {
                        console.log(el)
                    });
                })
            },
            onFail: function (error) {
                alert("Токен не верный", 'warning');
            },
        });
    }

    static async loadUsers(users) {
        this.post(this.getUsers, this.formData({users_ids: users.join(',')}), {
            onDone: function (result) {
                if (result.users.length > 0) {
                    return ScannerFriends.users = ScannerFriends.users.concat(result.users);
                }
            },
            onFail: function (error) {
                alert(error.messages, 'warning')
            },
        });
    }


}

new ScannerFriends();
