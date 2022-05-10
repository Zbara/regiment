import fetchJsonp from "fetch-jsonp";

class Ajax {
    static post(url, query, options) {
        let o = this.extend(options || {});

        if (o.showProgress) o.showProgress();

        this.curl(url, query).then((data) => {
            if (o.hideProgress) o.hideProgress();

            return (data.status > 0) ? o.onDone(data.result) : o.onFail(data.error, data);
        });
    }


    static vk(url, query, options) {
        query = new URLSearchParams(query);
        let o = this.extend(options || {});

        if (o.showProgress) o.showProgress();

        fetchJsonp(url + '?' + query.toString()).then(function (response) {
            return response.json();
        }).then(function (json) {
            if (o.hideProgress) o.hideProgress();

            return (json.response) ? o.onDone(json.response) : o.onFail(json.error);
        }).catch(function (ex) {
            console.log('parsing failed', ex)
        })
    }

    static async curlJson(url = '', data = {}) {
        const response = await fetchJsonp(url);
        return await response.json();
    }


    static formData(data) {
        const query = new FormData();

        for (let i in data) {
            query.append(i, data[i]);
        }
        return query;
    }

    static async curl(url = '', data = {}) {
        const response = await fetch(url, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: data
        });
        return await response.json();
    }


    static isFunction(obj) {
        return obj && Object.prototype.toString.call(obj) === '[object Function]';
    }

    static extend() {
        let a = arguments,
            target = a[0] || {},
            i = 1,
            l = a.length,
            deep = false,
            options;

        if (typeof target === 'boolean') {
            deep = target;
            target = a[1] || {};
            i = 2;
        }

        if (typeof target !== 'object' && !this.isFunction(target)) target = {};

        for (; i < l; ++i) {
            if ((options = a[i]) != null) {
                for (let name in options) {
                    let src = target[name], copy = options[name];

                    if (target === copy) continue;

                    if (deep && copy && typeof copy === 'object' && !copy.nodeType) {
                        target[name] = this.extend(deep, src || (copy.length != null ? [] : {}), copy);
                    } else if (copy !== undefined) {
                        target[name] = copy;
                    }
                }
            }
        }
        return target;
    }
}

export {Ajax};
