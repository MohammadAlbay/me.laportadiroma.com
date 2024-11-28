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
}

var elements = new Elements();

document.elements = elements;
document.createChild = elements.createChild;
document.addElement = elements.addElement;
document.modifyElement = elements.modifyElement;



/*************************************************************************************\
|||                                 View Loader API                                 |||                            
|||                              V0.0.1 21/11/2024 Thu                              |||
|||                   By Eng Mohammad s. Albay - Software Engineer | IT             |||
\*************************************************************************************/

class ViewLoader {
    static drop(...items) {
        for(let i = items.length-1; i>-1; i--) {
            widnow[`${items[i]}`] = null;
            delete widnow[`${items[i]}`];
        }
        // for(const item of items) {
        //     window[item] = null;
        //     delete window[item];
        // }
    }
    constructor(baseRoute, host = HTMLDivElement) {
        this.host = host;
        this.baseRoute = baseRoute;
        this.currentView = "";
        this.currentViewName = "";
    }

    setBaseRoute(baseRoute) {
        this.baseRoute = baseRoute;
    }
    setHost(host) {
        this.host = host;
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
        }


        scripts.forEach(e => e.remove());
    }
    unload() {
        if(this.currentView === "") return;
        try{eval(`${this.currentViewName}.finalize();`);}
        catch(error) {
            console.error("ViewLoader.unload() Raised an error: "+error);
        }
        [...document.querySelectorAll(`*[for-view="${this.currentView}"]`)]
            .forEach(element => element.remove());
    }
    initTheView() {
        try{eval(`${this.currentViewName}.initialize();`);}
        catch(e) {
            console.error("ViewLoader.initTheView() Raised an error : "+e);
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
            /* load resource for body */
            await this.loadResources(body, "body");
            processProgress.setPercentage(65);
            this.initTheView();
            processProgress.setPercentage(77);
            /* add to the head */
            
            for (const e of head.children) {
                document.head.appendChild(e);
            }
            processProgress.setPercentage(80);
            /* add to the body */
            for (const e of body.children) {
                this.host.appendChild(e);
            }
            processProgress.setPercentage(100);

        } catch (error) {
            console.error("ViewLoader.load() Raised an error : " + error);
        }
    }

    async init(view) {
        if(view == this.currentView) return;
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
            this.unload();
            this.currentView = view;
            this.currentViewName = view.split('/').pop();
            if(this.currentView.match(/([.-])/gm)) {
                this.currentViewName = this.currentViewName.replaceAll(/([.-])/gm, "_");
            }
            this.host.setAttribute("view", view);
            await this.load(response.result);
        }
    }
}




class ProcessProgress {
     #baseStyleForProgress = `width:0px; max-width:100%; height:0.2em; 
                            max-height:0.2em; background-color:rgb(204, 102, 102); transition:width 0.3s ease-out; 
                            z-index:100000; position:fixed; top:0px; left:0px;`;

     constructor(host) {
        this.progressBar = elements.createChild("DIV", {style: this.#baseStyleForProgress});
        host.appendChild(this.progressBar);
     }

     setPercentage(percentage = 0) {
        if(percentage < 0 || percentage > 100)
            throw new Error("Invalid value for ProcessProgress.setPercentage()");

        this.progressBar.style.width = `${percentage}%`;
        if(percentage == 100) {
            setTimeout(() => {this.progressBar.style.display = 'none';
                this.progressBar.style.width = '0px';
            }, 50);
            setTimeout(() => {this.progressBar.style.display = 'block';},150);

        }
     }
}

var processProgress;
var viewLoader = new ViewLoader('/view-loader', null);

window.addEventListener('DOMContentLoaded', e => {
    processProgress = new ProcessProgress(document.body);

    /* set default action for navigation menu item click */
    [...document.querySelectorAll(".nav-menu > div > .menu > ul > li:has(ul)")].forEach(li => {
        li.addEventListener("mouseup", e => {
            li.classList.toggle("open");
        });
    });
});



