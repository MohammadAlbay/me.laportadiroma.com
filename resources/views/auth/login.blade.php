<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ @csrf_token()}}">
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
            //alert(JSON.stringify(result));
        }, "f1");
    </script>
</head>

<body>
    <div class="login-box">
        <img src="/sources/imgs/full_logo.png" alt="">
        <div class="content">
            <h1 class="title">La Porta Di Roma - Me</h1>
            <form action="/auth/login" method="post" enctype="multipart/form-data" use="fetcher-" handler="f1" want-json="true" id="f1" name="f1">
                @csrf
            </form>
            <div style="width:70%;margin:0 auto; margin-top:2em">
                <h2 style="text-align:left;margin-left: 10px;">Enter your login information:</h2>
                <div class="input-form-v1">
                    <label for="login_email">Business Email</label>
                    <input type="text" id="login_email" name="login_email" form="f1">
                </div>
                <div class="input-form-v1">
                    <label for="login_password">Password</label>
                    <input type="password" id="login_password" name="login_password" form="f1">
                </div>

                <button class="login-button" type="submit" form="f1">Login</button><br><br>
                <button class="login-button"
                    style="transform:scale(0.7);background-color:black"
                    onclick="redirectToOTPPage()">login using OTP code
                </button>

            </div>
        </div>
    </div>


    @include('common.error')

    <script>
        async function redirectToOTPPage() {
            if (login_email.value == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Business email required',
                    text: 'Input your business email address first to redirect you to OTP login page!'
                });
                return;
            }

            const responsd = await fetcher.sendFormData('/auth/generate-otp', {
                type: 'POST',
                data: {
                    'email': login_email.value
                },
                wantJSON: true
            });
            console.log(responsd);
            if (responsd.error != null) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error occured while processing request',
                    text: responsd.error
                });
            } else {
                if (responsd.result.State == 0) {
                    location.href = '/auth/login-otp';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong!',
                        text: responsd.result.Message
                    });
                }
            }

        }
    </script>
</body>

</html>