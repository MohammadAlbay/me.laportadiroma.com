window.addEventListener("DOMContentLoaded", e => {
    document.querySelectorAll('.app-container-overlay').forEach(overlayView => {
        overlayView.querySelector(".head > .close-icon")?.addEventListener('click', innerE => {
            document.querySelector(`.app-container-backdrop[for='${overlayView.id}']`)?.classList.toggle("show", false);
            ViewLoader.blank(overlayView.querySelector(".body"));
            overlayView.classList.toggle("show", false);
        });
    });

    document.querySelectorAll('.app-container-backdrop[for]').forEach(overlayView => {
        overlayView.addEventListener('click', innerE => {
            document.querySelector(`#${overlayView.getAttribute("for")} > .head > .close-icon`).click();
        });
    });
});


window.addEventListener("popstate", e => {
    let data = e.state;
    const view = data.view;    
    const loader = ViewloaderInstances[data.loaderIndex];
    //loader.restoreHostByView();
    //loader.currentView = view;
    loader.moveBack();
});