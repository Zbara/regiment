(function () {
        const getUserInfo = 'https://regiment.zbara.ru/friends/get/social';

        let user = 0;

        let achievements = {
            "weapons": [0, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000, 100000],
            "bosses": [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000],
            "boxes": [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000],
            "missions": [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500],
            "sut": [0, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000],
            "exchange_collections": [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500],
            "send_help": [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000],
            "coins": [0, 5000, 10000, 25000, 50000, 100000, 250000, 500000, 1000000, 2500000, 5000000],
            "tokens": [0, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000, 100000, 250000, 500000],
            "encryptions": [0, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000, 100000, 250000, 500000],
            "tickets": [0, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000],
            "damage": [100000, 250000, 500000, 1000000, 3000000, 5000000, 10000000, 20000000, 30000000, 50000000, 75000000, 100000000, 250000000, 500000000, 1000000000, 5000000000, 10000000000, 25000000000, 50000000000, 100000000000],
            "open_package": [1, 5, 10, 25, 50, 100, 200, 300, 500, 750, 1000, 2000]
        };
        let bossList = {
            'win_boss_0': {
                'name': 'Победить Йенеке',
                'id': 0
            },
            'win_boss_1': {
                'name': 'Победить Рундштедт',
                'id': 1
            },
            'win_boss_2': {
                'name': 'Победить Манштейн',
                'id': 2
            },
            'win_boss_3': {
                'name': 'Победить Альмендингер',
                'id': 3
            },
            'win_boss_4': {
                'name': 'Победить Клейст',
                'id': 4
            },
            'win_boss_5': {
                'name': 'Победить Шёрнер',
                'id': 5
            },
            'win_boss_6': {
                'name': 'Победить Хубе',
                'id': 6
            },
            'win_boss_7': {
                'name': 'Победить Вёлер',
                'id': 7
            },
            'win_boss_9': {
                'name': 'Победить Гот',
                'id': 9
            },
            'win_boss_14': {
                'name': 'Победить Геббельс',
                'id': 14
            },
            'win_boss_15': {
                'name': 'Победить Муссолини',
                'id': 15
            }
        }

        let damageColor = ['000000', '009600', '009600', '009600', '000096', '000096', 'E69600', 'E69600', 'E69600', 'E69600', 'FF1111', 'FF1111', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000'];
        let weaponsColor = ['000000', '000000', '000000', '000000', '000000', '000096', '000096', '000096', 'E69600', 'E69600', 'E69600', 'FF1111', 'FF0000', 'FF0000'];
        let sutColor = ['000000', '0000C5', '0000C5', '0000C5', 'E69600', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000']; // таланты

        const myId = function () {
            if (document.body.innerHTML.match(/\"id\":(\d+),/)) {
                user = parseInt(document.body.innerHTML.match(/\"id\":(\d+),/)[1]);
            }
            return user;
        }

        function platform_id_type() {
            if (document.querySelector('#profile_edit_act')) {
                return 1;
            }
            if (document.querySelector('.PageActionCellSeparator')) {
                return 2;
            }
            if (document.querySelector("div[id^='friends_user_row']")) {
                return 3;
            }
            if (document.querySelector("div[id^='request_controls_']")) {
                return 4;
            }
            return 0;
        }

        function platform_id_search(type = 0, el = null) {
            let friends = el;
            switch (type) {
                case 1:
                    return user;
                case 2:
                    const el = document.querySelector('.profile_content').innerHTML;
                    const regex = /Profile.showGiftBox\((\d+),/;

                    if (regex.exec(el)) {
                        return regex.exec(el)[1]
                    } else return user;

                case 3:
                    if (friends === undefined) {
                        return 0;
                    }
                    if (friends.match(/friends_user_row(.+\d)/i)) {
                        return friends.match(/friends_user_row(.+\d)/i)[1];
                    } else return 0;

                case 4:
                    if (friends === undefined) {
                        return 0;
                    }
                    if (friends.match(/request_controls_(.+\d)/i)) {
                        return friends.match(/request_controls_(.+\d)/i)[1];
                    } else return 0;
            }
        }

        let insertButton = function () {
            let typeId = platform_id_type();

            console.log(typeId)

            switch (typeId) {
                case 1:
                case 2:
                    let userId = platform_id_search(typeId)

                    if (isNaN(userId) || (userId < 0) || (userId === 0)) {
                        return;
                    }
                    if (document.querySelector('#regiment_check_' + userId)) {
                        return;
                    }
                    document.querySelector('#profile_short').insertAdjacentHTML(
                        "afterbegin", "<div class='button_wide button_blue clear_fix' id='regiment_check_" + userId + "'><button>Досье на игрока Храбрый Полк</button></div>" +
                        "<div id='regiment_info" + userId + "' class='post all own post_online'  style='padding:16px 0 19px 3px;'></div>");

                    document.querySelector('#regiment_check_' + userId).addEventListener('click', function () {
                        userInfo(userId)
                    });
                    break;
                case 3:
                    document.querySelectorAll('.friends_user_row--fullRow').forEach(function (el) {
                        if (el.getAttribute("regiment") !== "1") {
                            let userId = platform_id_search(typeId, el.getAttribute("id"));

                            el.insertAdjacentHTML(
                                "afterend", "<div class='button_wide button_blue clear_fix' id='regiment_check_" + userId + "'><button>Досье на игрока Храбрый Полк</button></div>" +
                                "<div id='regiment_info" + userId + "' class='user_block'  style='padding:16px 0 19px 3px;'></div>");

                            document.querySelector('#regiment_check_' + userId).addEventListener('click', function () {
                                userInfo(userId)
                            });
                            el.setAttribute('regiment', 1)
                        }
                    })
                    break;
                case 4:
                    document.querySelectorAll('.friends_user_request').forEach(function (value) {
                        if (value.getAttribute("regiment") !== "1") {
                            let userId = platform_id_search(typeId, value.querySelector("div[id^='request_controls_']").getAttribute("id"));
                            value.insertAdjacentHTML(
                                "afterend", "<div class='button_wide button_blue clear_fix' id='regiment_check_" + userId + "'><button>Досье на игрока Храбрый Полк</button></div>" +
                                "<div id='regiment_info" + userId + "' class='user_block'  style='padding:16px 0 19px 3px;'></div>");

                            document.querySelector('#regiment_check_' + userId).addEventListener('click', function () {
                                userInfo(userId)
                            });
                            value.setAttribute('regiment', 1)
                        }
                    })
                    break;
            }
        }

        function formData(data) {
            const query = new FormData();

            for (let i in data) {
                query.append(i, data[i]);
            }
            return query;
        }

        async function postData(url = '', data = {}, dom) {
            loader(dom);

            const response = await fetch(url, {
                method: 'POST',
                mode: 'cors',
                body: formData(data)
            });
            return await response.json();
        }

        function printRow(title, value, xx) {
            return '<div class="clear_fix"><div class="label fl_l" style="width: 165px; color: var(--text_secondary);">' + title + '</div><div class="labeled fl_l" style="word-wrap:break-word;position:relative;overflow:visible;' + (xx ? 'width:345px' : '') + '">' + value + '</div></div>';
        }

        function printTitle(text, a) {
            if (!a) a = '';
            return '<h4 style="height:4px;padding:3px 0 15px 0">' + a + '<b style="padding-right:6px;font-size:11px;background-color:white">' + text + '</b></h4>';
        }

        let loader = function (dom) {
            dom.innerHTML = "<div width='100%' align='center'><div class='progress_inline'></div></div>";
        }
        let userInfo = function (userId) {
            const regiment = document.querySelector('#regiment_info' + userId);

            postData(getUserInfo, {userId: userId, ownerId: user}, regiment)
                .then((response) => {
                    if (response.status === 1) {
                        return renderInfo(regiment, response)
                    }
                    regiment.innerHTML = '<div style="text-align: center"><b style="color:#7F0000;font-weight:bold;">' + response.error.messages + '</b></div>';
                });
        }

        let renderInfo = function (regiment, response) {
            let data = response.result.data;
            let html = printRow('ID: ', data.platform_id);
            html += printRow('Уровень: ', data.level);
            html += printRow('Сут: ', '<b  style="color:#' + sutColor[data.achievements.sut] + ';font-size:10px;background:#fff">' + data.sut + ' </b>');
            html += printRow('Боевые достижения: ', '' +
                '<b  style="color:#' + damageColor[data.totalDamage] + ';font-size:10px;background:#fff">' + intToString(achievements['damage'][data.totalDamage]) + ' урона, </b>' +
                '<b  style="color:#' + weaponsColor[data.achievements.tokens] + ';font-size:10px;background:#fff">' + intToString(achievements['tokens'][data.achievements.tokens]) + ' жетонов, </b>' +
                '<b  style="color:#' + weaponsColor[data.achievements.weapon_5] + ';font-size:10px;background:#fff">' + intToString(achievements['weapons'][data.achievements.weapon_5]) + ' бронейбойных, </b>' +
                '<b  style="color:#' + weaponsColor[data.achievements.weapon_6] + ';font-size:10px;background:#fff">' + intToString(achievements['weapons'][data.achievements.weapon_6]) + ' кумулятивных</b>'
            );
            html += printRow('Таланты: ', data.usedTalents);
            html += printRow('Вход в игру: ', tsFormat(data.loginTime * 1000));

            html += '<div class="clear_fix" style="text-align:center"><b style="color:#777;font-size:10px;">Убитые боссы</b></div>';
            for (let z in bossList) {
                if (data.achievements[z] > 0) {
                    html += printRow(bossList[z].name, intToString(achievements['bosses'][data.achievements[z]]));
                }
            }
            html += printTitle('Достижения', '<a id="achievements_detail_link" class="fl_r" style="color:#A3B0BC;font-size:10px;padding:0 2px 0 6px;background:#fff">Показать подробности</a>');
            html += '<div id="achievements_detail" style="display:none">';
            html += '<div class="clear_fix"><b style="color:#777;font-size:10px">Боевые достижение</b></div>';
            html += printRow('Трассирующие снаряды: ', intToString(achievements['weapons'][data.achievements.weapon_0]));
            html += printRow('Осколочные снаряды: ', intToString(achievements['weapons'][data.achievements.weapon_1]));
            html += printRow('Разрывные снаряды: ', intToString(achievements['weapons'][data.achievements.weapon_2]));
            html += printRow('Зажигательные снаряды: ', intToString(achievements['weapons'][data.achievements.weapon_3]));
            html += printRow('Фугасные снаряды: ', intToString(achievements['weapons'][data.achievements.weapon_4]));
            html += printRow('Бронебойные снаряды: ', intToString(achievements['weapons'][data.achievements.weapon_5]));
            html += printRow('Кумулятивные снаряды: ', intToString(achievements['weapons'][data.achievements.weapon_6]));
            html += printRow('Общий урон: ', intToString(achievements['damage'][data.achievements.total_damage]));
            html += '<div class="clear_fix"><b style="color:#777;font-size:10px">Валютные достижение</b></div>';
            html += printRow('Заработать жетоны: ', intToString(achievements['tokens'][data.achievements.tokens]));
            html += printRow('Заработать шифровки: ', intToString(achievements['encryptions'][data.achievements.encryptions]));
            html += printRow('Заработать монеты: ', intToString(achievements['coins'][data.achievements.coins]));
            html += printRow('Раздобыть талоны: ', intToString(achievements['tickets'][data.achievements.tickets]));
            html += '</div>';

            regiment.innerHTML = html + response.result.messages;

            document.querySelector('#achievements_detail_link').addEventListener('click', function (el) {
                let obj = document.getElementById('achievements_detail');
                let none = obj.style.display === 'none';
                obj.style.display = none ? 'block' : 'none';
                document.getElementById('achievements_detail_link').innerHTML = none ? 'Скрыть подробности' : 'Показать подробности';
            });
        }

        function intToString(value) {
            let str = reverse_string(value.toString()).replace(/000/gi, "k");
            let out = reverse_string(str);
            if (parseInt(value) > 0) {
                out += "+";
            }
            return out;
        }

        function reverse_string(str) {
            let out = "";
            for (let i = str.length - 1; i >= 0; i--) {
                out += str[i];
            }
            return out;
        }

        function tsFormat(ts) {
            let m = JSON.parse('{"Jan":"01", "Feb":"02", "Mar":"03", "Apr":"04", "May":"05", "Jun":"06", "Jul":"07", "Aug":"08", "Sep":"09", "Oct":"10", "Nov":"11", "Dec":"12"}');
            let d = new Date();
            d.setTime(ts.toString().length > 10 ? ts : ts * 1000);
            let a = d.toString().split(' ');
            return a[2] + '.' + m[a[1]] + '.' + a[3] + ' в ' + a[4];
        }

        if (myId() > 0) {
            setInterval(insertButton, 300);
        }
    }
)();
