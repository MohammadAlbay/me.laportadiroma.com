<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ @csrf_token()}}">
    <!-- External resource -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui@5.0.18/material-ui.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Internal js -->
    <script src="/common/js/fetch.js"></script>
    <script src="/common/js/elements.js"></script>
    <script src="/common/js/app-container.js"></script>
    <script src="/js/main.js"></script>
    <!-- Internal css -->
    <link rel="stylesheet" href="/common/css/normalize.css">
    <link rel="stylesheet" href="/common/css/forms.css">
    <link rel="stylesheet" href="/common/css/navigation.css">
    <link rel="stylesheet" href="/common/css/app-container.css">
    <title>Document</title>
</head>

<body>
    @include("common.navbar")
    @include("common.navmenu")
    

    <div id="app-container" class="app-container"></div>
    <div id="overlay-app-container" class="app-container-overlay">
        <div class="head">
            <b class="title"></b>
            <img class="close-icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABlklEQVR4nO2aUU7DMAyGcwi0jQvBPdgrG+/sUuMM8MAxGKj8fyftpSdARi6qqtKmTZdkmj+p2h7syb+c2Ek95wzDmERRFPcA9iS/SJ4SP58SS1mWd6NEkNxlEPzpn+fZOxPq8A3gkeTSJYbkEsBGYpLYvDID4EWMRYTLDABbjW0/aCzrUY0XLjMALHS1HAaN67XoMoW+8ZmQSNAykhm0jAwA4KGqqhs3EfGV30iaEQBrtX+bIkZ8xFf71jqZkKIoJJBX9Xkfc5xp+/o2YJ5rj0wRM1XE2Tf7GDEhIqJULR8xoSKild8+MXOIiNpHusTMJSJ6Q2yWVf38+x7Sc5J09lYWgjORlZA5rs20pTWSro0dcgLIovw298RcYhi7IXZt7DnEMOYRpa86hYphrEOjT4kNEcMYx/gxfWKqGNrF6hquuimgCckMWkYyg9eYkYMaJx+5tTkej7ca24cbQie58uZv4zKD5JP36E0GjY1h6LYsy1WUKPtjWqkIeA9DBRkBt0bClzeerhHVusx+h6MX+YcBwzBczQ8x2MZtl/ABXgAAAABJRU5ErkJggg==" alt="close-window--v1">
        </div>
        <div class="body">

        </div>
    </div>
    <script>
        viewLoader.setHost(document.getElementById('app-container'));
        secondaryViewContainer.setHost(document.querySelector('.app-container-overlay > .body'));
    </script>

    @include("common.error")

    <script>
        function clicked() {
            alert("clicked");
        }
    </script>
</body>

</html>