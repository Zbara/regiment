(function () {
        let user = null;


        const myId = function () {
            if (document.body.innerHTML.match(/\"id\":(\d+),/)) {
                user = parseInt(document.body.innerHTML.match(/\"id\":(\d+),/)[1]);
            } else return false;
        };

        function platform_id_type() {
            if (document.querySelector('#profile_edit_act')) {
                return 1;
            }
            return 0;
        }

        function platform_id_search(type = 0, el = null) {
            switch (type) {
                case 1:
                    return user;

                default:

                    return user;
            }
        }


        let insertButton = function () {

            let typeId = platform_id_type();
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
                        "afterbegin", "<div class='button_wide button_blue clear_fix' id='regiment_check_" + userId + "'><button>Досье на игрока Храбрый полк</button></div>" +
                        "<div id='regiment_info" + userId + "' class='post all own post_online'></div>");

                    document.querySelector('#regiment_check_' + userId).addEventListener('click', function () {
                        userInfo(userId)
                    });
                    break;
                default:
                    console.log('id not found')
            }
        };

        function formData(data) {
            const query = new FormData();

            for (let i in data){
                query.append(i, data[i]);
            }
            return query;
        }
        async function postData(url = '', data = {}) {
            const response = await fetch(url, {
                method: 'POST',
                body: formData(data)
            });
            return await response.json();
        }


        let userInfo = function (userId) {
            const regiment = document.querySelector('#regiment_info' + userId);
            regiment.innerHTML = "<div width='100%' align='center'><div class='progress_inline'></div></div>";

            postData('https://regiment.zbara.pro/friends/get', {userId: userId})
                .then((result) => {

                    regiment.innerHTML = result.data.socId;
                });
        }


        myId();

        setInterval(insertButton, 300);
    }
)();
