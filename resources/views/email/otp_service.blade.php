<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>You reguested an OTP to access your account</h1>
    <hr>
    <p>Here's your OTP code: <b>{{$otp}}</b></p>
    <p>the code is valid untile <b>{{$due}}</b></p>
    <p style="color:orange;background-color:black;">
        <b style="color:red;">Warning:</b> 
        If you did not requested the OTP login code jsut ignore this email and don't share it with anyone!
    </p>
</body>
</html>