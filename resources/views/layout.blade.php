<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.muicss.com/mui-0.4.6/css/mui.min.css" rel="stylesheet" type="text/css" />
    <script src="//cdn.muicss.com/mui-0.4.6/js/mui.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <style>
        /**
         * Body CSS
         */
        html,
        body {
            height: 100%;
        }

        html,
        body,
        input,
        textarea,
        button {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.004);
        }


        /**
         * Sidebar CSS
         */
        #sidebar {
            background-color: #E57373;
            padding: 15px;
        }

        @media (min-width: 768px) {
            #sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                width: 180px;
                height: 100%;
                padding-top: 30px;
            }
        }

        /**
         * Content CSS
         */
        @media (min-width: 768px) {
            #content {
                margin-left: 180px;
            }
        }
    </style>
</head>
<body>
<div id="sidebar">
    <div class="mui--text-light mui--text-display1 mui--align-vertical">VK.ANAL</div>
</div>
<div id="content" class="mui-container-fluid">
    @yield('content')
</div>
</body>
</html>