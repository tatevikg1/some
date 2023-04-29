<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- icons from font awesome -->
    <script src="https://kit.fontawesome.com/aac74422ea.js" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="/svg/wings.svg"/>
    <!-- <link rel="stylesheet" href="/css/master.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

{{--    <script src="https://kit.fontawesome.com/yourcode.js"></script>--}}

</head>
<body>
    <div id="app">
        @yield('layout')
    </div>
</body>


<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script>
    const app = firebase.initializeApp({
        apiKey: "AIzaSyA9YV_C2CEe7JUH0kfOwCfdfEjd-eLEbqI",
        authDomain: "cookbook-5fdd3.firebaseapp.com",
        projectId: "cookbook-5fdd3",
        storageBucket: "cookbook-5fdd3.appspot.com",
        messagingSenderId: "500719604396",
        appId: "1:500719604396:web:5041e6221a5667c2a9c509",
        measurementId: "G-0507867HWH"
    });
    const messaging = firebase.messaging();
    function startFCM() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function (response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("store.token") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token stored.');
                    },
                    error: function (error) {
                        alert(error);
                    },
                });
            }).catch(function (error) {
            alert(error);
        });
    }
    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });
</script>

<script async src="https://www.google.com/recaptcha/api.js"></script>
{{--<script>--}}
{{--    // If reCAPTCHA is still loading, grecaptcha will be undefined.--}}
{{--    grecaptcha.ready(function(){--}}
{{--        grecaptcha.render("container", {--}}
{{--            sitekey: "ABC-123"--}}
{{--        })--}}
{{--    });--}}
{{--</script>--}}


</html>
