<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('/images/logo-icon.svg')}}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{asset('/images/logo-icon.svg')}}">
    <title> Chat App </title>
    @vite('resources/css/app.css')
    @vite('resources/js/app/app.js')
</head>
<body>

    <div id="app" class="hidden"></div>

    <div id="preloader" class="fixed top-0 start-0 w-full h-full flex justify-center items-center bg-white">
        <div class="loader"></div>
    </div>

    <script type="application/javascript">

        document.addEventListener('DOMContentLoaded', function () {
            const preloader = document.querySelector('#preloader');
            const app = document.querySelector('#app');
            app.classList.add('hidden');
            preloader.classList.add('flex');
            window.addEventListener('load', function () {
                preloader.classList.remove('flex');
                preloader.classList.add('hidden');
                app.classList.remove('hidden');
            });
        });

    </script>

</body>
</html>
