<!DOCTYPE html>
<html>
    <head>
        <title>IFS Global Logistics - @yield('title')</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .logo {
                margin-top: -35%;
                margin-bottom: 5%;
            }

            .message {
                font-size: 72px;
                margin-bottom: 5%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class=""><img alt="IFS Global Logistics" src="/images/ifs_logo.png"></div>
                <div class="message">@yield('message')</div>
            </div>
        </div>
    </body>
</html>