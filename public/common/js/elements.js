class Elements {
    elementPropertySetter(Me, myData) {
        if (myData == undefined) return;
        for (let key in myData) {
            if (myData[key] == null) continue;
            if (key == "text")
                Me.innerText = myData[key];
            else if (key == "html")
                Me.innerHTML = myData[key];
            else if (key == "child") {
                let child = myData[key];
                if (Array.isArray(child))
                    child.forEach(c => Me.appendChild(c));
                else
                    Me.appendChild(child);
            }
            else if (key == "event") {
                let events = myData[key];

                for (let event in events) {
                    Me[event] = () => events[event](Me);
                }
            }
            else
                Me.setAttribute(key, myData[key]);
        }
    }
    addElement(parent, me, myData) {
        let Me = document.createElement(me);
        this.elementPropertySetter(Me, myData);

        parent.appendChild(Me);
    }
    modifyElement(Me, myData) {
        this.elementPropertySetter(Me, myData);
        return Me;
    }
    createChild(me, myData) {
        let Me = document.createElement(me);
        this.elementPropertySetter(Me, myData);

        return Me;
    }

    buttonStartLoader(button) {
        if (button instanceof HTMLButtonElement)
            button.classList.toggle("embed-loader-icon", true);
        else if (button instanceof String)
            document.querySelector(button).classList.toString("embed-loader-icon", true);
    }
    buttonEndLoader(button) {
        if (button instanceof HTMLButtonElement)
            button.classList.toggle("embed-loader-icon", false);
        else if (button instanceof String)
            document.querySelector(button).classList.toString("embed-loader-icon", false);
    }
    triggerEvent(element, eventType) {
        // Create the event (can be any event type: click, input, mouseover, etc.)
        const event = new Event(eventType, {
            bubbles: true, // Allows the event to propagate up the DOM tree
            cancelable: true // Allows the event to be canceled
        });

        // Dispatch the event on the specified element
        element.dispatchEvent(event);
    }
}



var elements = new Elements();

document.elements = elements;
document.createChild = elements.createChild;
document.addElement = elements.addElement;
document.modifyElement = elements.modifyElement;
document.startButtonLoader = elements.buttonStartLoader;
document.endButtonLoader = elements.buttonEndLoader;
document.triggerEvent = elements.triggerEvent;

if (!HTMLElement.prototype._isEnhanced) {
    /* extra property for extra data */
    Object.defineProperty(HTMLElement.prototype, "extra", {
        value: {},
        configurable: true,
        writable: true,
        enumerable: false
    });
    /*  */
    Object.defineProperty(HTMLElement.prototype, "eventListeners", {
        value: {},
        configurable: true,
        writable: true,
        enumerable: false
    });

    const originalAddEventListener = HTMLElement.prototype.addEventListener;
    const originalRemoveEventListener = HTMLElement.prototype.removeEventListener;

    // inhance event add event listener
    function enhanceAddEventListener(type, listener, options) {
        if (!this.eventListeners[type])
            this.eventListeners[type] = [];

        // check if event listener is already defined
        const isDuplicate = this.eventListeners[type].some(
            entry => entry.listener === listener && JSON.stringify(entry.options) === JSON.stringify(options)
        )
        if (isDuplicate) {
            console.warn("Elements.js : Enhanced event listener can't accept redefining the same event");
            return;
        }

        // call the original add event listener
        originalAddEventListener.call(this, type, listener, options);

        // store event listener && options
        this.eventListeners[type].push({ listener, options });
    }

    // inhance event remove event listenr
    function enhanceRemoveEventListener(type, listener, options) {
        originalRemoveEventListener.call(this, type, listener, options);
        if (this.eventListeners[type]) {
            this.eventListeners[type] = this.eventListeners[type].filter(
                entry => entry.listener !== listener || JSON.stringify(entry.options) !== JSON.stringify(options)
            )
        }
    }

    /* remove all listeners for event */
    function removeEventListeners(type) {
        if (this.eventListeners[type]) {
            const arrayOfObjects = this.eventListeners[type];
            /* delete all events */
            arrayOfObjects.forEach(obj => {
                this.originalRemoveEventListener(type, obj.listener, obj.options);
            });
            /* clear all events */
            this.eventListeners[type] = [];
        }
    }

    Object.defineProperty(HTMLElement.prototype, "addEventListener", {
        value: enhanceAddEventListener,
        configurable: false,
        writable: false,
        enumerable: false
    });

    Object.defineProperty(HTMLElement.prototype, "removeEventListener", {
        value: enhanceRemoveEventListener,
        configurable: false,
        writable: false,
        enumerable: false
    });

    Object.defineProperty(HTMLElement.prototype, "removeEvents", {
        value: removeEventListeners,
        configurable: false,
        writable: false,
        enumerable: false
    });

    Object.defineProperty(HTMLElement.prototype, "_isEnhanced", {
        value: true,
        configurable: false,
        writable: false,
        enumerable: false
    });

    Object.defineProperty(HTMLElement.prototype, "allowManyEventListeners", {
        value: false,
        configurable: false,
        writable: true,
        enumerable: false
    });

    Object.defineProperty(Document.prototype, "find", {
        value: (selector) => document.querySelector(selector),
        configurable: false,
        writable: false,
        enumerable: false
    });

    Object.defineProperty(HTMLElement.prototype, "linkedTo", {
        value: null,
        configurable: false,
        enumerable: false,
        writable: true
    });
    // uses the extra property

    Object.defineProperty(HTMLElement.prototype, "linkTo", {
        set: function (e) {
            this.linkedTo = e;  // 'this' refers to the current HTMLElement here
        },
        configurable: false,
        enumerable: false,
        get: function () {
            return this.linkedTo;  // 'this' refers to the current HTMLElement
        }
    });

}

/*************************************************************************************\
|||                                 View Loader API                                 |||                            
|||                              V0.0.1 21/11/2024 Thu                              |||
|||                   By Eng Mohammad s. Albay - Software Engineer | IT             |||
\*************************************************************************************/

var ViewloaderInstances = [];
class ViewLoader {
    static drop(...items) {
        for (let i = items.length - 1; i > -1; i--) {
            widnow[`${items[i]}`] = null;
            delete widnow[`${items[i]}`];
        }
        // for(const item of items) {
        //     window[item] = null;
        //     delete window[item];
        // }
    }
    constructor(baseRoute, host = HTMLDivElement, isTemp = false) {
        this.host = host;
        this.baseRoute = baseRoute;
        this.currentView = "";
        this.previousView = "";
        this.currentViewName = "";
        if (!isTemp)
            this.index = ViewloaderInstances.push(this) - 1;
    }

    sendParams(loader, params) {
        if (this.currentView == "") return;
        try {
            //const strParams = JSON.stringify(params);
            const currentViewInstance = window[this.currentViewName];

            if (currentViewInstance && typeof currentViewInstance.paramsPassed === 'function') {
                currentViewInstance.paramsPassed(loader.currentViewName, params);
            } else {
                console.error(`ViewLoader.sendParams() Raised an error: ${this.currentViewName}.paramsPassed is not a function`);
            }
        } catch (error) {
            console.error("ViewLoader.sendParams() Raised an error: " + error);
        }
    }
    setBaseRoute(baseRoute) {
        this.baseRoute = baseRoute;
    }
    setHost(host) {
        this.host = host;
    }
    getHost() {
        return this.host;
    }
    async loadScript(url, place) {
        /* create the script */
        const script = document.createElement('script');
        /* append to parent */
        if (place == "body")
            document.body.appendChild(script);
        else
            document.head.appendChild(script);

        // create a promise
        var promise = new Promise((resolve, reject) => {
            script.onload = () => {
                console.log("script loaded");
                resolve();
            };
            script.onerror = () => {
                console.log("Something happin");
                reject();
            }
        });

        script.src = url;
        script.setAttribute("for-view", this.currentView);

        await promise;
    }
    async loadScriptsSequentially(scripts, place) {
        for (const script of scripts) { /*scritps is just an array of links for js scripts.. so script is just a URL */
            try {
                await this.loadScript(script, place);
            }
            catch (error) { console.error("ViewLoader.loadScriptsSequentially() Raised an error: " + error); break; }
        }
    }

    async loadLink(url) {
        /* create the link */
        const link = document.createElement('link');
        /* append to parent */
        document.head.appendChild(link);

        // create a promise
        var promise = new Promise((resolve, reject) => {
            link.onload = () => {
                console.log("link loaded");
                resolve();
            };
            link.onerror = () => {
                console.log("Something happin");
                reject();
            }
        });

        link.href = url;
        link.rel = "stylesheet";
        link.setAttribute("for-view", this.currentView);

        await promise;
    }
    async loadLinksSequentially(links) {
        for (const link of links) {
            try {
                await this.loadLink(link);
            }
            catch (error) {
                console.log("ViewLoader.loadLinksSequentially() Raised an error: " + error)
                break;
            }
        }
    }
    async addStylesSequantially(styles) {
        for (const style of styles) {
            const head = document.head;

            const newStyle = elements.createChild("style", { "for-view": this.currentView });
            newStyle.appendChild(document.createTextNode(style.childNodes[0].textContent));
            head.insertAdjacentElement("beforeend", newStyle);
        }
    }
    async loadResources(part, place) {
        // JS Sources
        let scripts = [...part.querySelectorAll('script')];
        let sourceScript = [], textScript = [];

        scripts.forEach(script => {
            if (script.hasAttribute("src"))
                sourceScript.push(script.src);
            else {
                let tempScript = elements.createChild("script",
                    { child: document.createTextNode(script.innerText), "for-view": this.currentView });
                textScript.push(tempScript);
            }

            script.remove();
        });

        await this.loadScriptsSequentially(sourceScript, place);
        textScript.forEach(ts => place == "body" ? document.body.appendChild(ts) : document.head.appendChild(ts));

        // CSS Links
        if (place == "head") {
            let links = [...part.querySelectorAll('link[rel="stylesheet"]')];
            await this.loadLinksSequentially(links.map(link => link.href));
            links.forEach(link => link.remove());
            let styles = [...part.querySelectorAll('style')];
            await this.addStylesSequantially(styles);
            styles.forEach(style => style.remove());
        }


        scripts.forEach(e => e.remove());
    }
    unload() {
        if (this.currentView === "") return;

        try {
            const currentViewInstance = window[this.currentViewName];
            if (currentViewInstance && typeof currentViewInstance.finalize === 'function') {
                currentViewInstance.finalize();
            } else {
                console.error(`ViewLoader.unload() Raised an error: ${this.currentViewName}.finalize is not a function`);
            }
        } catch (error) {
            console.error("ViewLoader.unload() Raised an error: " + error);
        }

        [...document.querySelectorAll(`*[for-view="${this.currentView}"]`)]
            .forEach(element => element.remove());

        this.currentView = "";
        this.currentViewName = "";
    }
    initTheView() {
        let prevView = this.previousView.split('/').pop().replaceAll(/([.-])/gm, "_");
        try {
            const currentViewInstance = window[this.currentViewName];
            if (currentViewInstance) {
                currentViewInstance.loader = this;
                if (typeof currentViewInstance.initialize === 'function') {
                    currentViewInstance.initialize(prevView);
                } else {
                    console.error(`ViewLoader.initTheView() Raised an error: ${this.currentViewName}.initialize is not a function`);
                }
            } else {
                console.error(`ViewLoader.initTheView() Raised an error: ${this.currentViewName} is not defined`);
            }
        } catch (e) {
            console.info("ViewLoader.initTheView() Raised an error: " + e);
        }
    }
    async load(view) {
        try {
            let parser = new DOMParser();
            let page = parser.parseFromString(view, "text/html");
            processProgress.setPercentage(25);
            /* clear the host */
            this.host.replaceChildren();
            let body = page.children[0].children[1];
            let head = page.children[0].children[0];
            /* load resource for head */
            await this.loadResources(head, "head");
            processProgress.setPercentage(50);

            /* add to the head */

            for (const e of head.children) {
                document.head.appendChild(e);
            }
            processProgress.setPercentage(65);

            /* add to the body */
            const bodyElements = [...body.children].filter(e => e.tagName != "SCRIPT");
            //console.log(bodyElements);
            bodyElements.forEach(i => this.host.insertAdjacentElement("beforeEnd", i));
            processProgress.setPercentage(77);
            /* load resource for body */
            await this.loadResources(body, "body");
            processProgress.setPercentage(80);
            this.initTheView();
            processProgress.setPercentage(100);

        } catch (error) {
            console.error("ViewLoader.load() Raised an error : " + error);
        }
    }

    restoreHostByView() {
        this.host = document.querySelector("div[view='" + this.currentView + "']");
        //this.host = document.querySelector(`div[view="${this.currentView}"]`);
    }
    async init(view) {
        if (this.currentView != "" && this.host == null)
            this.restoreHostByView();
        if (view == this.currentView && this.host.children.length != 0) return;
        processProgress.setPercentage(0);
        let response = await fetcher.getText(`${this.baseRoute}${view}`);
        processProgress.setPercentage(20);
        if (response.error != null) {
            Swal.fire({
                title: "Network disconnected", text: "Failed to load the resources from the origin. Plase check your Network and try again.",
                showConfirmButton: true,
                confirmButtonText: "Try Again"
            }).then(async result => {
                if (result.isConfirmed) {
                    await viewLoader.init(view);
                }
            });
        } else {

            if (this.currentView != "") {
                this.previousView = this.currentView;
            }
            this.unload();
            this.currentView = view;
            this.currentViewName = view.split('/').pop();
            history.pushState({ view: view, loaderIndex: this.index }, `${this.currentView}`, `#${this.currentViewName}`);
            if (this.currentView.match(/([.-])/gm)) {
                this.currentViewName = this.currentViewName.replaceAll(/([.-])/gm, "_");
            }
            this.host.setAttribute("view", view);
            await this.load(response.result);

            window.document.dispatchEvent(new Event("DOMContentLoaded", {
                bubbles: true,
                cancelable: true
            }));
        }
    }

    moveBack() {
        if (this.previousView != "")
            this.init(this.previousView);
        else
            throw new Error("ViewLoader.moveBack() Raised an error: previousView is empty");
    }

    static blank(host) {
        if (host == null) return;

        let viewName = host.getAttribute("view");
        [...document.querySelectorAll(`*[for-view="${viewName}"]`)]
            .forEach(element => element.remove());
        host.replaceChildren();
    }
}

function cloneWithFunctions(obj) {
    // Create a shallow clone of the object
    let clone = Object.create(Object.getPrototypeOf(obj));

    // Copy all properties (including functions) to the new object
    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            clone[key] = obj[key];
        }
    }

    return clone;
}


class ProcessProgress {
    #baseStyleForProgress = `width:0px; max-width:100%; height:0.2em; 
                            max-height:0.2em; background-color:rgb(204, 102, 102); transition:width 0.3s ease-out; 
                            z-index:100000; position:fixed; top:0px; left:0px;`;

    constructor(host) {
        this.progressBar = elements.createChild("DIV", { style: this.#baseStyleForProgress });
        host.appendChild(this.progressBar);
    }

    setPercentage(percentage = 0) {
        if (percentage < 0 || percentage > 100)
            throw new Error("Invalid value for ProcessProgress.setPercentage()");

        this.progressBar.style.width = `${percentage}%`;
        if (percentage == 100) {
            setTimeout(() => {
                this.progressBar.style.display = 'none';
                this.progressBar.style.width = '0px';
            }, 50);
            setTimeout(() => { this.progressBar.style.display = 'block'; }, 150);

        }
    }
}

var processProgress;
var viewLoader = new ViewLoader('/view-loader', null);
window.loadedBasicEvents = false;
window.addEventListener('DOMContentLoaded', e => {
    processProgress = new ProcessProgress(document.body);

    /* set default action for navigation menu item click */
    if (!window.loadedBasicEvents) {
        [...document.querySelectorAll(".nav-menu > div > .menu > ul > li:has(ul)")].forEach(li => {
            li.addEventListener("mouseup", e => {
                li.classList.toggle("open");
            });
        });
        window.loadedBasicEvents = true;
    }

    window.addEventListener("keydown", ev => {
        [...document.querySelectorAll("dialog")].forEach(d => {
            if (ev.key == "Escape") {
                if (d.classList.contains("dialog-open")) {
                    d.classList.replace("dialog-open", "dialog-close");
                }
            }
        })
    });

    [...document.querySelectorAll("*[close-dialog]")].forEach(ev => {

        ev.addEventListener('click', c => {
            let tempDialog = null;
            if (ev.parentElement.tagName == "DIALOG")
                tempDialog = ev.parentElement;
            else if (ev.hasAttribute("dialog-id") && ev.getAttribute("dialog-id") != "")
                tempDialog = document.getElementById(ev.getAttribute("dialog-id"));

            tempDialog.classList.toggle("dialog-open", false);
            tempDialog.classList.toggle("dialog-close", true);
            tempDialog.close();
        });

    });

    [...document.querySelectorAll(".dialog-overlay[for]")].forEach(el => {
        let tempDialog = document.getElementById(el.getAttribute('for'));
        if (tempDialog == null) return;
        if (tempDialog.hasAttribute("allow-ignore")) {
            el.addEventListener('click', ev => {
                var event = new KeyboardEvent('keydown', {
                    key: 'Escape', // The key that was pressed
                    code: 'Escape', // The physical key on the keyboard
                    keyCode: 27, // The keyCode for the Escape key (optional)
                    bubbles: true, // Allow the event to bubble up through the DOM
                    cancelable: true // The event can be canceled
                });

                // Dispatch the event on the document or any specific element
                document.dispatchEvent(event);
            });
        }
    });

    [...document.querySelectorAll("*[open-dialog]")].forEach(ev => {
        ev.addEventListener('click', c => {
            let tempDialog = null;
            if (ev.parentElement.tagName == "DIALOG")
                tempDialog = ev.parentElement;
            else if (ev.hasAttribute("dialog-id") && ev.getAttribute("dialog-id") != "")
                tempDialog = document.getElementById(ev.getAttribute("dialog-id"));

            tempDialog.classList.toggle("dialog-open", true);
            tempDialog.classList.toggle("dialog-close", false);
            tempDialog.showModal();
        });

    });
});

// success

function successCard(message, title) {
    Swal.fire({
        position: "top-end",
        icon: "success",
        title: title ?? "Process Completed",
        text: message ?? "",
        showConfirmButton: false,
        timer: 4000
    });
}

function successAlert(message, title) {
    Swal.fire({
        icon: "success",
        title: title ?? "Process Completed",
        text: message ?? "",
        showConfirmButton: true,
        timer: 6000
    });
}

// error

function errorCard(message, title) {
    Swal.fire({
        position: "top-end",
        icon: "error",
        title: title ?? "Process Failed",
        text: message ?? "",
        showConfirmButton: false,
        timer: 4000
    });
}

function errorAlert(message, title) {
    Swal.fire({
        icon: "success",
        title: title ?? "Process Failed",
        text: message ?? "",
        showConfirmButton: true,
        timer: 6000
    });
}

// generate

function swalCard(icon, message, title) {
    Swal.fire({
        position: "top-end",
        icon: icon ?? info,
        title: title ?? "Alert!",
        text: message ?? "",
        showConfirmButton: false,
        timer: 4000
    });
}

function swalAlert(icon, message, title) {
    Swal.fire({
        icon: icon ?? info,
        title: title ?? "Alert!",
        text: message ?? "",
        showConfirmButton: true,
        timer: 6000
    });
}

// confirm

/**
 * 
 * @param {string} icon accepts success, error, warning, question and info
 * @param {string} title 
 * @param {string} message 
 * @returns 
 */
async function confirmAlert(icon = "warning", title, message) {
    return await Swal.fire({
        title: title,
        text: message,
        icon: icon ?? 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    });
} 