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
            alert(JSON.stringify(result));
        }, "f1");
    </script>
</head>

<body>
    <div class="login-box">
        <img src="/sources/imgs/full_logo.png" alt="">
        <div class="content">
            <h1 class="title">La Porta Di Roma - Me</h1>
            <form action="/auth/consume-otp" method="post" enctype="multipart/form-data" use="fetcher-" handler="f1" want-json="true" id="f1" name="f1">
                @csrf
                <input type="text" name="otp" id="otp" style="display:none">
            </form>
            <div style="width:70%;margin:0 auto; margin-top:2em">
                <h2 style="text-align:left;margin-left: 10px;">Enter your login information:</h2>
                <div class="input-form-v1">
                    <label for="login_email">Business Email</label>
                    <input type="text" id="login_email" name="login_email" form="f1" value="{{$email}}" readonly>
                </div>
                <div class="input-form-v1">
                    <label for="login_password">OTP Code</label>
                    <div class="input">
                        <input type="text" maxlength="1" placeholder="-" required id="otp_code_1" class="input-one-char" oninput="if(preventInput) return; this.value == '' ?otp_code_1.focus() : otp_code_2.focus()">
                        <input type="text" maxlength="1" placeholder="-" required id="otp_code_2" class="input-one-char" oninput="if(preventInput) return; this.value == '' ?otp_code_1.focus() : otp_code_3.focus()">
                        <input type="text" maxlength="1" placeholder="-" required id="otp_code_3" class="input-one-char" oninput="if(preventInput) return; this.value == '' ?otp_code_2.focus() : otp_code_4.focus()">
                        <input type="text" maxlength="1" placeholder="-" required id="otp_code_4" class="input-one-char" oninput="if(preventInput) return; this.value == '' ?otp_code_3.focus() : otp_code_5.focus()">
                        <input type="text" maxlength="1" placeholder="-" required id="otp_code_5" class="input-one-char" oninput="if(preventInput) return; this.value == '' ?otp_code_4.focus() : otp_code_6.focus()">
                        <input type="text" maxlength="1" placeholder="-" required id="otp_code_6" class="input-one-char" oninput="if(preventInput) return; this.value == '' ? otp_code_5.focus() : void(0); checkAllCodes();">
                    </div>
                </div>

                <button id="login_button" disabled class="login-button" type="submit" form="f1">Login</button>
            </div>
        </div>
    </div>


    @include('common.error')

    <script>
        let preventInput = false;
        (function() {
            document.querySelectorAll('.input > input').forEach(e => {
                    e.addEventListener('paste', function(event) {
                        // Prevent the default paste behavior 

                        console.log('fired');
                        const pasteData = (event.clipboardData || window.clipboardData).getData('text') + ""; // Process the pasted data (e.g., insert it into the input field) 
                        console.log(pasteData.length);
                        if (pasteData.length == 6) {
                            event.preventDefault(); // Get the pasted data 
                            otp_code_1.value = pasteData.charAt(0);
                            otp_code_2.value = pasteData.charAt(1);
                            otp_code_3.value = pasteData.charAt(2);
                            otp_code_4.value = pasteData.charAt(3);
                            otp_code_5.value = pasteData.charAt(4);
                            otp_code_6.value = pasteData.charAt(5);
                            otp.value = pasteData;
                            login_button.disabled = false;
                        }
                        //this.value = pasteData.substring(0, this.maxLength); 
                    });

                    e.addEventListener('keydown', ie => {
                        if (ie.key === "Backspace") {
                            const currentId = parseInt(e.id.replace("otp_code_", ""));
                            if(currentId == "1")
                            return;
                            else if(currentId == 6) {
                                e.value = "";
                                preventInput = true;
                                document.getElementById(`otp_code_${(currentId-1)}`).focus();
                                
                                
                            }
                            else {
                                preventInput = true;
                                e.value = "";
                                document.getElementById(`otp_code_${(currentId-1)}`).focus();
                            }
                        }
                        else 
                            preventInput = false;
                    });

                }

            );
        })();

        function checkAllCodes() {
            if (otp_code_1.value != "" && otp_code_2.value != "" && otp_code_3.value != "" &&
                otp_code_4.value != "" && otp_code_5.value != "" && otp_code_6.value != ""
            ) {
                otp.value = otp_code_1.value + otp_code_2.value + otp_code_3.value +
                    otp_code_4.value + otp_code_5.value + otp_code_6.value;
                login_button.disabled = false;
            } else
                login_button.disabled = true;
        }
    </script>
</body>

</html>