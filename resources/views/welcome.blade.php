<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CODIGO QR</title>

     
    </head>
    <body class="antialiased">
        hola novato

    <div class="title m-b-md"> 
    <img src="{ baseEncode(QrCode::generate('http://www.simplesoftware.io')) }" />
    </div>
 
    </body>
</html>


