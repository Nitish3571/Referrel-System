<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data['title']}}</title>
</head>
<body>

    <p>Hii {{$data['name'] }}, Welcome to Referral System!</p>
    <p><b>Username:-</b> {{$data['email'] }}</p>
    <p><b>Password:-</b> {{$data['password'] }}</p>
    <p>You can add users to your Netwok by share your <a href="{{$data['url']}}">Referral Link</a></p>

    <p>Thank You!</p>

</body>
</html>
