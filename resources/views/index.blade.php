<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token"content="{{ @csrf_token()}}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Me </title>
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
    <link rel="stylesheet" href="/css/login_styles.css">
    <script>
        fetcher.addHandler(function(result) {
            alert(JSON.stringify(result));
        }, "f1");
    </script>
</head>

<body>
    <div class="login-box">
        <img src="/sources/imgs/full_logo.png" alt="">
        <div class="content">
            <h1 class="title">La Porta Di Roma - Me</h1>
            <form action="/auth/login" method="post" enctype="multipart/form-data" use="fetcher-" handler="f1"want-json="true" id="f1" name="f1">
                @csrf
            </form>
            <div style="width:70%;margin:0 auto; margin-top:2em">
                
                <button class="login-button" onclick="location.href='/auth/'">Login</button>
            </div>
        </div>
    </div>


    @include('common.error')
</body>

</html>