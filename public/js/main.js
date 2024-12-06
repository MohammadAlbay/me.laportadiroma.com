//fetcher.getText("/test-email");

var secondaryViewContainer = new ViewLoader('/view-loader', null);
async function displaySettingsView() {
    secondaryViewContainer.init("/settings");
    const host = secondaryViewContainer.getHost().parentElement;
    host.classList.toggle("show", true);
    host.querySelector(".head > .title").textContent = "Settings";
}