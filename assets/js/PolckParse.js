import {Ajax} from "./Ajax";
import {toast} from "./src/toast";

let md5 = require('md5');
let pako = require("pako");
let Base64 = require("js-base64");

class PolckParse extends Ajax {

    static getApi = '/hp/api';

    static game_login = 135057576;
    static game_token = "eacb78bec65760e7d5fd4373389ab2e1";
    static current_time = 1644488343;
    static secret = "Ko7zWN";
    static game_key = 'Y4zT1AvovM';
    static server = "vk.regiment.bravegames.ru/" + this.game_login + "/" + this.game_token + "/";
    static last_rnd;
    static num;


    constructor() {
        Ajax.curl('/hp/users').then(r =>
            PolckParse.usersStart(r.response)
        );
        super();
    }

    static async usersStart(el) {
        async function timer(ms) {
            return new Promise(res => setTimeout(res, ms));
        }

        for (let z in el) {
            await timer(1000);

            let requests = [];

            requests.push({"method": 'friends.view', "params": {"friend": el[z]}});
            this.server_query("action", "requests=" + JSON.stringify(requests));
        }
    }

    static get_current_timestamp() {
        return this.current_time;
    }

    static random_int(min, max) {
        return Math.round(min - 0.5 + Math.random() * (max - min + 1));
    };

    static server_query(method, data) {
        let str = "ts=" + this.get_current_timestamp();
        if (data !== "") {
            str += "&" + data;
        }
        let rnd = this.random_int(1001, 9999);


        console.log(rnd);

        while (rnd === this.last_rnd) {
            rnd = this.random_int(1001, 9999);
        }
        this.last_rnd = rnd;
        str += "&rnd=" + rnd;
        let hash = md5(this.secret + str + this.secret);
        str += "&sign=" + hash;

        console.log(str )

        return this.xhr_query(this.server + method, str, hash);
    }

    static xhr_query(address, data, sign) {
        this.post(this.getApi, this.formData({
            url: address,
            data: this.epl_compress(data),
            gameCheck: md5(sign),
            gameKey: this.game_key,

        }), {
            onDone: async function (result) {
                let data = JSON.parse(JXG.decompress(result));

                if(data.result === 'ok'){
                    Ajax.curl('/hp/api/save', Ajax.formData({user: JSON.stringify(data)})).then(r =>
                        console.log(r)
                    );
                }
            },
            onFail: function (error) {
                toast('Ошибка', error.messages);
            }
        });
    }




    static epl_compress(data) {
        data = encodeURIComponent(data);


        console.log(data)

        let bytes = pako.deflate(data, {level: 9});


        return Base64.fromUint8Array(bytes);
    }
}

new PolckParse();
