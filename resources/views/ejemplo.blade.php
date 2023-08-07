<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>hola mundo</p>
    <div class="title m-b-md">
        {!! QrCode::size(500)->color(224, 224, 224)->backgroundColor(102, 0, 204)->generate('Welcome to Makitweb') !!}
    </div>
</body>

</html>
