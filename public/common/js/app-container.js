window.addEventListener("DOMContentLoaded", e => {
    document.querySelectorAll('.app-container-overlay').forEach(overlayView => {
        overlayView.querySelector(".head > .close-icon").addEventListener('click', innerE => {
            ViewLoader.blank(overlayView.querySelector(".body"));
            overlayView.classList.toggle("show", false);
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