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
    <script>
        viewLoader.setHost(document.getElementById('app-container'));
    </script>

    @include("common.error")

    <script>
        function clicked() {
            alert("clicked");
        }
    </script>
</body>

</html>