(function () {
        let getUserInfo = 'https://regiment.zbara.ru/friends/get/social';
        let user = 0;
        let isGroup = [];

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

        let achievements_list = {
            "win_boss_0": {"title": "Победить Йенеке", "type": "boss", "category": "bosses"},
            "win_boss_1": {"title": "Победить Рундштедт", "type": "boss", "category": "bosses"},
            "win_boss_2": {"title": "Победить Манштейн", "type": "boss", "category": "bosses"},
            "win_boss_3": {"title": "Победить Альмендингер", "type": "boss", "category": "bosses"},
            "win_boss_4": {"title": "Победить Клейст", "type": "boss", "category": "bosses"},
            "win_boss_5": {"title": "Победить Шёрнер", "type": "boss", "category": "bosses"},
            "win_boss_6": {"title": "Победить Хубе", "type": "boss", "category": "bosses"},
            "win_boss_7": {"title": "Победить Вёлер", "type": "boss", "category": "bosses"},
            "win_boss_8": {"title": "Победить Бок", "type": "boss", "category": "bosses"},
            "win_boss_9": {"title": "Победить Гот", "type": "boss", "category": "bosses"},
            "win_boss_14": {"title": "Победить Геббельс", "type": "boss", "category": "bosses"},
            "win_boss_15": {"title": "Победить Муссолини", "type": "boss", "category": "bosses"},
            "tokens": {"title": "Заработать жетоны", "type": "resourse", "category": "tokens"},
            "encryptions": {"title": "Заработать шифровки", "type": "resourse", "category": "encryptions"},
            "coins": {"title": "Заработать монеты", "type": "resourse", "category": "coins"},
            "tickets": {"title": "Раздобыть талоны", "type": "resourse", "category": "tickets"},
            "sut": {"title": "Поднять уровень техники", "type": "other", "category": "sut"},
            "total_damage": {"title": "Общий урон", "type": "other", "category": "damage"},
            "open_package": {"title": "Открыть забытую посылку", "type": "other", "category": "open_package"},
            "send_airstrike": {"title": "Отправить артобстрел", "type": "other", "category": "send_help"},
            "send_ammunition": {"title": "Отправить боезапас", "type": "other", "category": "send_help"},
            "exchange_collections": {"title": "Обменять набор коллекций", "type": "other", "category": "exchange_collections"},
            "weapon_0": {"title": "Трассирующие снаряды", "type": "weapon", "category": "weapons"},
            "weapon_1": {"title": "Осколочные снаряды", "type": "weapon", "category": "weapons"},
            "weapon_2": {"title": "Разрывные снаряды", "type": "weapon", "category": "weapons"},
            "weapon_3": {"title": "Зажигательные снаряды", "type": "weapon", "category": "weapons"},
            "weapon_4": {"title": "Фугасные снаряды", "type": "weapon", "category": "weapons"},
            "weapon_5": {"title": "Бронебойные снаряды", "type": "weapon", "category": "weapons"},
            "weapon_6": {"title": "Кумулятивные снаряды", "type": "weapon", "category": "weapons"},
            "open_box_0": {"title": "Открыть Простой ящик", "type": "boxes", "category": "boxes"},
            "open_box_2": {"title": "Открыть Лёгкий ящик", "type": "boxes", "category": "boxes"},
            "open_box_3": {"title": "Открыть Средний ящик", "type": "boxes", "category": "boxes"},
            "open_box_4": {"title": "Открыть Большой ящик", "type": "boxes", "category": "boxes"},
            "open_box_5": {"title": "Открыть Легендарный ящик", "type": "boxes", "category": "boxes"},
            "open_box_6": {"title": "Открыть Фронтовой ящик", "type": "boxes", "category": "boxes"},
            "open_box_7": {"title": "Открыть ящик с танками", "type": "boxes", "category": "boxes"},
            "open_box_8": {"title": "Открыть ящик с артиллерией", "type": "boxes", "category": "boxes"},
            "open_box_9": {"title": "Открыть ящик с авиацией", "type": "boxes", "category": "boxes"},
            "open_box_11": {"title": "Открыть Лёгкий ящик с техникой", "type": "boxes", "category": "boxes"},
            "open_box_12": {"title": "Открыть Средний ящик с техникой", "type": "boxes", "category": "boxes"},
            "open_box_13": {"title": "Открыть Большой ящик с техникой", "type": "boxes", "category": "boxes"},
            "open_box_14": {"title": "Открыть Легендарный ящик с техникой", "type": "boxes", "category": "boxes"},
            "mission_0_0": {"title": "Пройти миссию Отступать некуда", "type": "mission", "category": "missions"},
            "mission_0_1": {"title": "Пройти миссию Засада в туннеле", "type": "mission", "category": "missions"},
            "mission_0_2": {"title": "Пройти миссию Один путь", "type": "mission", "category": "missions"},
            "mission_0_3": {"title": "Пройти миссию Скрытая угроза", "type": "mission", "category": "missions"},
            "mission_0_4": {"title": "Пройти миссию Город тишины", "type": "mission", "category": "missions"},
            "mission_0_5": {"title": "Пройти миссию Железная дорога", "type": "mission", "category": "missions"},
            "mission_0_6": {"title": "Пройти миссию Поезд спасения", "type": "mission", "category": "missions"},
            "mission_1_0": {"title": "Пройти миссию Атака из леса", "type": "mission", "category": "missions"},
            "mission_1_1": {"title": "Пройти миссию Место отгрузки", "type": "mission", "category": "missions"},
            "mission_1_2": {"title": "Пройти миссию Таинственная река", "type": "mission", "category": "missions"},
            "mission_1_3": {"title": "Пройти миссию Точка доступа", "type": "mission", "category": "missions"},
            "mission_1_4": {"title": "Пройти миссию Сквозь снег", "type": "mission", "category": "missions"},
            "mission_1_5": {"title": "Пройти миссию Закрытая зона", "type": "mission", "category": "missions"},
            "mission_1_6": {"title": "Пройти миссию Перекрёсток", "type": "mission", "category": "missions"},
            "mission_2_0": {"title": "Пройти миссию Затопленный груз", "type": "mission", "category": "missions"},
            "mission_2_1": {"title": "Пройти миссию Пролив", "type": "mission", "category": "missions"},
            "mission_2_2": {"title": "Пройти миссию Залечь на дно", "type": "mission", "category": "missions"},
            "mission_2_3": {"title": "Пройти миссию Два берега", "type": "mission", "category": "missions"},
            "mission_2_4": {"title": "Пройти миссию Доставка из штаба", "type": "mission", "category": "missions"},
            "mission_2_5": {"title": "Пройти миссию Площадь возмездия", "type": "mission", "category": "missions"},
            "mission_2_6": {"title": "Пройти миссию Портовый город", "type": "mission", "category": "missions"},
            "mission_3_0": {"title": "Пройти миссию Захват", "type": "mission", "category": "missions"},
            "mission_3_1": {"title": "Пройти миссию Шпионский мост", "type": "mission", "category": "missions"},
            "mission_3_2": {"title": "Пройти миссию В тылу врага", "type": "mission", "category": "missions"},
            "mission_3_3": {"title": "Пройти миссию Занять высоту", "type": "mission", "category": "missions"},
            "mission_3_4": {"title": "Пройти миссию Надзорная вышка", "type": "mission", "category": "missions"},
            "mission_3_5": {"title": "Пройти миссию Укрепление", "type": "mission", "category": "missions"},
            "mission_3_6": {"title": "Пройти миссию Штурм крепости", "type": "mission", "category": "missions"},
        }
        let category = {
            'weapon': 'Боевые достижение',
            'other': 'Другие',
            'resourse': 'Валютные достижение',
            'mission': 'Прохождение миссий',
            'boxes': 'Открытие ящиков'
        };

        let damageColor = ['000000', '009600', '009600', '009600', '000096', '000096', 'E69600', 'E69600', 'E69600', 'E69600', 'FF1111', 'FF1111', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000'];
        let weaponsColor = ['000000', '000000', '000000', '000000', '000000', '000096', '000096', '000096', 'E69600', 'E69600', 'E69600', 'FF1111', 'FF0000', 'FF0000'];
        let sutColor = ['000000', '0000C5', '0000C5', '0000C5', 'E69600', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000'];


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
                                "<div id='regiment_info" + userId + "' class='post all own post_online'  style='padding:16px 0 19px 3px;'></div>");

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
                    body: formData({userId: userId, ownerId: user})
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

                    regiment.innerHTML = '<div style="text-align: center"><b style="color:#7F0000;font-weight:bold;">Ошибка при подключение к серверу скрипта.</b></div>';
                })
            );
        }

        function renderInfo(regiment = {}, response = {}) {
            let data = response.result.data;

            let html = printRow('ID: ', data.platform_id);
            html += printRow('Уровень: ', data.level);
            html += printRow('Сут: ', '<b  style="color:#' + sutColor[data.achievements.sut] + ';font-size:10px;background:#fff">' + data.sut + ' </b>');
            html += printRow('Боевые достижения: ', '' +
                '<b  style="color:#' + damageColor[data.totalDamage] + ';font-size:10px;background:#fff">' + intToString(achievements['damage'][data.totalDamage]) + ' урона, </b>' +
                '<b  style="color:#' + weaponsColor[data.achievements.tokens] + ';font-size:10px;background:#fff">' + intToString(achievements['tokens'][data.achievements.tokens]) + ' жетонов, </b>' +
                '<b  style="color:#' + weaponsColor[data.achievements.weapon_6] + ';font-size:10px;background:#fff">' + intToString(achievements['weapons'][data.achievements.weapon_6]) + ' кумулятивных</b>'
            );
            html += printRow('Таланты: ', data.usedTalents);
            html += printRow('Вход в игру: ', tsFormat(data.loginTime * 1000));

            html += '<div class="clear_fix" style="text-align:center"><b style="color:#777;font-size:10px;">Убитые боссы</b></div>';
            for (let z in achievements_list) {
                if (achievements_list[z].type === 'boss') {
                    if (data.achievements[z] > 0) {
                        html += printRow(achievements_list[z].title, intToString(achievements['bosses'][data.achievements[z]]));
                    }
                }
            }
            html += printTitle('Достижения', '<a id="achievements_detail_link" class="fl_r" style="color:#A3B0BC;font-size:10px;padding:0 2px 0 6px;background:#fff">Показать подробности</a>');
            html += '<div id="achievements_detail" style="display:none">';

            for (let z in achievements_list) {
                for (let i in category) {
                    if (achievements_list[z].type === i && !isGroup[i]) {
                        html += '<div class="clear_fix" style="text-align:center"><b style="color:#777;font-size:10px">' + category[i] + '</b></div>';
                        isGroup[i] = true;
                    }
                }
                if (achievements_list[z].type !== 'boss' && data.achievements[z] > 0) {
                    html += printRow(achievements_list[z].title, intToString(achievements[achievements_list[z].category][data.achievements[z]]), 250);
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
