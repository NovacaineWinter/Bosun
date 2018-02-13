<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('pagetitle')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,300,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,600" rel="stylesheet" type="text/css">

        <!--  Master Stylesheet  -->
        <link href="/css/style.css" rel="stylesheet" type="text/css">

        <!-- Bootstrap CSS  -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!--  jQuery from Google CDN  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!-- Bootstrap JS  -->
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->




        <!-- Styles -->
        <style>
            html, body {
                background-color: #202020;
                /* color: #636b6f;  */
                color: #fff;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                /* color: #636b6f; */
                color: #fff;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
           
           

            <div id="ajax-target" class="content col-sm-12">
                <div class="title m-b-md">
                	@yield('page_header_1')
                </div>

                @yield('page_content')


            </div>
        </div>
    </body>
</html>