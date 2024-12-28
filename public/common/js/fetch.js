class Fetch {

    #handlerCollections = {};
    /**
     * Adds functions(handles) to handle requests for auto-handling forms.
     * The default collection is Main
     * @param {The collection where a set of special handlers will be stored} collection Object
     * @param {the name of the handler. this name should be special} handlerName String
     * @param {the actual handling function} handler Function
     */
    addHandler(handler, handlerName, collection = "main") {
        if (!(collection in this.#handlerCollections))
            this.#handlerCollections[collection] = {};

        if (handlerName in this.#handlerCollections[collection])
            console.info(`Fetch.addHandler Raised an info: Warning the collection ${collection} has a handler named ${handlerName} defined already. updated the handler.`);
        this.#handlerCollections[collection][handlerName] = handler;
    }
    getHandler(handlerName, collection = "main") {
        if (collection in this.#handlerCollections) {
            if (handlerName in this.#handlerCollections[collection])
                return this.#handlerCollections[collection][handlerName];
        }

        throw new Error(`Fetch.getHandler Raised an error: The handler or the collection is not exist. [${collection}][{${handlerName}]`);
    }
    constructor(domain) {
        this.domain = domain;
        this.queue = [];
        this.isProcessing = false;

    }
    addRequest(request) {
        this.queue.push(request);
        if (!this.isProcessing) {
            this.processQueue();
        }
    }
    async processQueue() {
        this.isProcessing = true;
        while (this.queue.length > 0) {
            const currentRequest = this.queue.shift();
            try {
                const response = await fetch(currentRequest.url, currentRequest.options);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                } const data = await response.json(); currentRequest.resolve(data);
            } catch (error) {
                currentRequest.reject(error);
            }
        }
        this.isProcessing = false;
    }
    getJSONLazy(route, success, failure) {

    }



    /**
     * This method doesn't use the queue for requests
     * @param {string} route 
     * @returns 
     */
    async getJSON(route) {
        try {
            let result = await (await fetch(`${this.domain}${route}`, {headers: {"Content-Type": "application/json", "Accept": "application/json"},})).json();
            return { error: null, result: result };
        } catch (e) {
            console.error("Fetch:getJSON() Raised an error: " + e);
            return { error: e, result: null };
        }
    }

    async getText(route) {
        try {
            let result = await (await fetch(`${this.domain}${route}`)).text();
            return { error: null, result: result };
        } catch (e) {
            console.error("Fetch:getJSON() Raised an error: " + e);
            return { error: e, result: null };
        }
    }

    async sendFormDataCallback(url, options, callback) {
        let result = await this.sendFormData(url, options);
        callback(result);
    }
    async sendFormData(url, options) {
        try {
            let headers = {
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            };

            if (!(options.data instanceof FormData)) {
                headers['Content-Type'] = 'application/json';
                options.data = JSON.stringify(options.data);
            }

            let options_ = {
                method: options.type || "GET",
                credentials: "same-origin",
                headers: headers,
                body: options.data
            };

            if (options.type.toLowerCase() === "get") {
                delete options_['body'];
            }

            let promise;
            if (options.wantJSON == true)
                promise = await (await fetch(url, options_)).json();
            else
                promise = await (await fetch(url, options_)).text();

            return { error: null, result: promise };
        } catch (e) {
            console.error("Fetch:sendFormData() Raised an error: " + e);
            return { error: e, result: null };
        }
    }
}

var fetcher = new Fetch(location.origin);


window.addEventListener("DOMContentLoaded", e => {

    /*
     Modify the base submit method
    */
    // const originalSubmit = HTMLFormElement.prototype.submit; 
    // HTMLFormElement.prototype.submit = function() { 
    //     const event = new Event('submit', { bubbles: true, cancelable: true }); 
    //     if (this.dispatchEvent(event)) { 
    //         originalSubmit.call(this); 
    //     }
    // };

    /*
     Pre-set auto-handling forms
    */
    [...document.querySelectorAll('form[use="fetcher"]')]
        .forEach(e => {
            let handlerName = e.getAttribute('handler');
            if (!e.action)
                throw new Error(`fetcher Raised an error: the form ${(e.id ?? e.name)} action value is not set or is empty`);
            else if (!handlerName)
                throw new Error(`fetcher Raised an error: the form ${(e.id ?? e.name)} handler value is not set or is empty`);
            
            e.addEventListener("submit", function(ev) {
                ev.preventDefault();
                
                let collection = e.getAttribute("collection") ?? "main";
                let handler = fetcher.getHandler(handlerName, collection);
                fetcher.sendFormDataCallback(e.action,
                    {
                        wantJSON: e.getAttribute('want-json') ?? true,
                        type: e.method || "get", 
                        data: new FormData(e)
                    }, handler);
            }); 

        });
});
