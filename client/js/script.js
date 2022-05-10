(function () {
        let getUserInfo = 'https://regiment.zbara.ru/friends/get/social';
        let user = 0;
        let version = 0.3;

        async function myId() {
            if (document.body.innerHTML.match(/"id":(\d+),/)) {
                user = parseInt(document.body.innerHTML.match(/"id":(\d+),/)[1]);
            }
            return user;
        }

        async function platform_id_type() {
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

        async function platform_id_search(type = 0, el = null) {
            let friends = el;
            switch (type) {
                case 1:
                    return user;
                case 2:
                    const el = document.querySelector('.profile_content');
                    const regex = /Profile.showGiftBox\((\d+),/;

                    if (el) {
                        if (regex.exec(el.innerHTML)) {
                            return regex.exec(el.innerHTML)[1]
                        }
                    }
                    return 0;

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

        function insertButton() {
            platform_id_type().then(function (typeId) {
                switch (typeId) {
                    case 1:
                    case 2:
                        platform_id_search(typeId).then(function (userId) {
                            if (isNaN(userId) || (userId < 0) || (userId === 0)) {
                                return;
                            }
                            if (document.querySelector('#regiment_check_' + userId)) {
                                return;
                            }
                            document.querySelector('#profile_short').insertAdjacentHTML(
                                "afterbegin", "<div class='button_wide button_blue clear_fix' id='regiment_check_" + userId + "'><button>Досье на игрока Храбрый Полк</button></div>" +
                                "<div id='regiment_info" + userId + "' class='post all own post_online'  style='padding:16px 0 19px 3px;'></div>" +
                                "<div id='regiment_add" + userId + "' class='profile_info' style='display:none;min-height:250px;'></div>");

                            document.querySelector('#regiment_check_' + userId).addEventListener('click', function () {
                                userInfo(userId)
                            })
                        });
                        break;
                    case 3:
                        document.querySelectorAll('.friends_user_row--fullRow').forEach(function (el) {
                            if (el.getAttribute("regiment") !== "1") {
                                platform_id_search(typeId, el.getAttribute("id")).then(function (userId) {
                                    el.insertAdjacentHTML(
                                        "afterend", "<div class='button_wide button_blue clear_fix' id='regiment_check_" + userId + "'><button>Досье на игрока Храбрый Полк</button></div>" +
                                        "<div id='regiment_info" + userId + "' class='user_block'  style='padding:16px 0 19px 3px;'></div>");

                                    document.querySelector('#regiment_check_' + userId).addEventListener('click', function () {
                                        userInfo(userId)
                                    });
                                    el.setAttribute('regiment', 1)
                                })
                            }
                        });
                        break;
                    case 4:
                        document.querySelectorAll('.friends_user_request').forEach(function (value) {
                            if (value.getAttribute("regiment") !== "1") {
                                platform_id_search(typeId, value.querySelector("div[id^='request_controls_']").getAttribute("id")).then(function (userId) {
                                    value.insertAdjacentHTML(
                                        "afterend", "<div class='button_wide button_blue clear_fix' id='regiment_check_" + userId + "'><button>Досье на игрока Храбрый Полк</button></div>" +
                                        "<div id='regiment_info" + userId + "' class='user_block'  style='padding:16px 0 19px 3px;'></div>");

                                    document.querySelector('#regiment_check_' + userId).addEventListener('click', function () {
                                        userInfo(userId)
                                    });
                                    value.setAttribute('regiment', 1)
                                })
                            }
                        });
                        break;
                }
            });
        }

        function formData(data = {}) {
            const query = new FormData();

            for (let i in data) {
                query.append(i, data[i]);
            }
            return query;
        }

        function printRow(title = '', value = '', x = 165, y = 0) {
            return '<div class="clear_fix"><div class="label fl_l" style="width: ' + x + 'px; color: #818c99;">' + title + '</div><div class="labeled fl_l" style="word-wrap:break-word;position:relative;overflow:visible;' + (y ? 'width:345px' : '') + '">' + value + '</div></div>';
        }

        function printTitle(text = '', a = '') {
            if (!a) a = '';
            return '<h4 style="height:4px;padding:3px 0 15px 0">' + a + '<b style="padding-right:6px;font-size:11px;background-color:white">' + text + '</b></h4>';
        }

        async function loader(dom = {}) {
            dom.innerHTML = "<div style='text-align: center'><div class='progress_inline'></div></div>";

            return dom;
        }

        function userInfo(userId = 0) {
            loader(document.querySelector('#regiment_info' + userId)).then(regiment =>
                fetch(getUserInfo, {
                    method: 'POST',
                    mode: 'cors',
                    body: formData({
                        userId: userId,
                        ownerId: user,
                        version: version
                    })
                }).then(function (response) {
                    if (!response.ok) {
                        throw response;
                    }
                    return response.json();
                }).then(function (response) {
                    if (response.status === 1) {
                        return renderInfo(regiment, response)
                    }
                    regiment.innerHTML = '<div style="text-align: center"><b style="color:#7F0000;font-weight:bold;">' + response.error.messages + '</b></div>';
                }).catch(function (error) {
                    console.log(error)

                    regiment.innerHTML = '<div style="text-align: center"><b style="color:#7F0000;font-weight:bold;">Ошибка при подключении к серверу скрипта.</b></div>';
                })
            );
        }

        function renderInfo(regiment = {}, response = {}) {
            let isGroup = [];
            let data = response.result.data;
            let library = response.result.library;
            let gExpDiff = data.xp - library.level[data.level - 1];
            let html = '<a class="wall_post_source_icon wall_post_source_default fl_r" id="regiment_stats' + data.platform_id + '" title="Статистка продвижения пользователя."></a>';
            html += printRow('ID', data.platform_id);
            if (data.clan) {
                html += printRow(data.clan.category, '<a target="_blank" href="' + data.clan.url + '">' + data.clan.name + '</a>');
            }
            html += printRow('Уровень', data.level + ' (' + Math.floor(gExpDiff * 100 / (library.level[data.level] - library.level[data.level - 1])) + '%)');
            html += printRow('Сут', '<b  style="color:#' + library.color.sut[data.achievements.sut] + ';font-size:10px;background:#fff">' + data.sut + ' </b>');
            html += printRow('Боевые достижения', '' +
                '<b  style="color:#' + library.color.damage[data.totalDamage] + ';font-size:10px;background:#fff">' + intToString(library.achievements['damage'][data.totalDamage]) + ' урона, </b>' +
                '<b  style="color:#' + library.color.weapons[data.achievements.tokens] + ';font-size:10px;background:#fff">' + intToString(library.achievements['tokens'][data.achievements.tokens]) + ' жетонов, </b>' +
                '<b  style="color:#' + library.color.weapons[data.achievements.weapon_6] + ';font-size:10px;background:#fff">' + intToString(library.achievements['weapons'][data.achievements.weapon_6]) + ' кумулятивных</b>'
            );
            html += printRow('Валюта:', printCurrency(data, library));

            for (let z in library.color.talant) {
                if (data.usedTalents >= library.color.talant[z].min && data.usedTalents <= library.color.talant[z].max) {
                    html += printRow('Вложено талантов', '<b  style="color:' + library.color.talant[z].color + ';font-size:10px;background:#fff">' + data.usedTalents + ' </b>');
                }
            }

            html += printRow('Вход в игру', tsFormat(data.loginTime * 1000));
            html += printRow('Примерное место в топе', data.top);
            html += printTitle('Победы');

            let isGroupB1 = false;
            let isGroupB2 = false;

            for (let z in library.achievements_list) {
                if (library.achievements_list[z].type === 'boss') {
                    if (library.achievements_list[z].group === 'usual' && !isGroupB1) {
                        html += '<div class="clear_fix" style="text-align:center"><b style="color:#777;font-size:10px">Обычные боссы</b></div>';
                        isGroupB1 = true;
                    }
                    if (library.achievements_list[z].group === 'night' && !isGroupB2) {
                        html += '<div class="clear_fix" style="text-align:center"><b style="color:#777;font-size:10px">Ночные боссы</b></div>';
                        isGroupB2 = true;
                    }
                    if (data.achievements[z] > 0) {
                        html += printRow(library.achievements_list[z].title, intToString(library.achievements['bosses'][data.achievements[z]]));
                    }
                }
            }
            html += printTitle('Достижения', '<a id="achievements_detail_link" class="fl_r" style="color:#A3B0BC;font-size:10px;padding:0 2px 0 6px;background:#fff">Показать подробности</a>');
            html += '<div id="achievements_detail" style="display:none">';

            for (let z in library.achievements_list) {
                for (let i in library.achievements_category) {
                    if (library.achievements_list[z].type === i && !isGroup[i]) {
                        html += '<div class="clear_fix" style="text-align:center"><b style="color:#777;font-size:10px">' + library.achievements_category[i] + '</b></div>';
                        isGroup[i] = true;
                    }
                }
                if (library.achievements_list[z].type !== 'boss' && data.achievements[z] > 0) {
                    html += printRow(library.achievements_list[z].title, intToString(library.achievements[library.achievements_list[z].category][data.achievements[z]]), 250);
                }
            }
            html += '</div>';

            regiment.innerHTML = html + response.result.messages;

            document.querySelector('#achievements_detail_link').addEventListener('click', function (el) {
                let obj = document.getElementById('achievements_detail');
                let none = obj.style.display === 'none';
                obj.style.display = none ? 'block' : 'none';
                document.getElementById('achievements_detail_link').innerHTML = none ? 'Скрыть подробности' : 'Показать подробности';
            });
            document.querySelector('#regiment_stats' + data.platform_id).addEventListener('click', function (el) {

                var obj=document.getElementById('regiment_info' + data.platform_id);
                var obj2=document.getElementById('regiment_add' + data.platform_id);
                if(obj.style.display=='none'){
                    obj.style.display='block';
                    obj2.style.display='none';
                } else {
                    obj.style.display='none';
                    obj2.style.display='block';
                }
            });
        }

        function printCurrency(d, l) {
            let cc = '';
            for (let z in l.images) {
                cc += '<div style="float:left;margin-right:2px;margin-left:2px;width:16px;height:18px;background-size:auto 100%; background:url(' + l.images[z] + ') 0 0 no-repeat"></div><div style="float:left;">' + intToString(l.achievements[z][d.achievements[z]]) + '</div>';
            }
            return cc;
        }

        function intToString(value = '') {
            let str = reverse_string(value.toString()).replace(/000/gi, "k");
            let out = reverse_string(str);
            if (parseInt(value) > 0) {
                out += "+";
            }
            return out;
        }

        function reverse_string(str = '') {
            let out = "";
            for (let i = str.length - 1; i >= 0; i--) {
                out += str[i];
            }
            return out;
        }

        function tsFormat(ts = 0) {
            let m = JSON.parse('{"Jan":"01", "Feb":"02", "Mar":"03", "Apr":"04", "May":"05", "Jun":"06", "Jul":"07", "Aug":"08", "Sep":"09", "Oct":"10", "Nov":"11", "Dec":"12"}');
            let d = new Date();
            d.setTime(ts.toString().length > 10 ? ts : ts * 1000);
            let a = d.toString().split(' ');
            return a[2] + '.' + m[a[1]] + '.' + a[3] + ' в ' + a[4];
        }

        myId().then(r =>
            setInterval(insertButton, 300)
        )
    }
)();
