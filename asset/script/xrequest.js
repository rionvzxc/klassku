class XRequest {
    constructor(url) {
        this.url = url;
        this.method = "GET";
        this.headers = {};
        this.body = null;
        this.responseType = "text";
        this.onSuccessFunc = null;
        this.onErrorFunc = null;
    }
    SetMethod(method) {
        this.method = method;
        return this;
    }
    SetHeader(name, value) {
        this.headers[name] = value;
        return this;
    }
    SetBody(body) {
        this.body = body;
        return this;
    }
    SetResponseType(type) {
        this.responseType = type;
        return this;
    }
    OnSuccess(callback) {
        this.onSuccessFunc = callback;
        return this;
    }
    OnError(callback) {
        this.onErrorFunc = callback;
        return this;
    }
    Send() {
        const xhr = new XMLHttpRequest();
        xhr.open(this.method, this.url, true);

        for (const name in this.headers) {
            xhr.setRequestHeader(name, this.headers[name]);
        }
        xhr.onload = () => {
            let response = xhr.responseText;

            if (this.responseType === "json") {
                try {
                    response = JSON.parse(response);
                } catch (error) {
                    if (this.onErrorFunc) {
                        this.onErrorFunc({
                            type: "parse",
                            error: error,
                            xhr: xhr
                        });
                    }
                    return;
                }
            }
            if (xhr.status >= 200 && xhr.status < 300) {
                if (this.onSuccessFunc) {
                    this.onSuccessFunc(response, xhr);
                }
            } else {
                if (this.onErrorFunc) {
                    this.onErrorFunc({
                        type: "http",
                        status: xhr.status,
                        response: response,
                        xhr: xhr
                    });
                }
            }
        };
        xhr.onerror = () => {
            if (this.onErrorFunc) {
                this.onErrorFunc({
                    type: "network",
                    xhr: xhr
                });
            }
        };
        xhr.send(this.body);
        return xhr;
    }
}
