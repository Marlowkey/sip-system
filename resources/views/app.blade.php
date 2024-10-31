<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!--Replace with your tailwind.css once created-->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet" />
    <!-- Define your gradient here - use online tools to find a gradient matching your branding-->
    <style>
        .gradient {
            background: rgb(2, 0, 36);
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(34, 34, 171, 1) 0%, rgba(0, 212, 255, 1) 100%);
        }
    </style>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
    @if(Auth::check() && Auth::user()->role === 'student')
    <script>
        var botmanWidget = {
            chatServer: '/botman',
            frameEndpoint: '/botman/chat',
            title: 'Chatbot',
            introMessage: "✋ Hi! I'm here to help. Ask me anything!",
            placeholderText: "Type a message...",
            mainColor: '#0096FF',
            bubbleBackground: '#0096FF',
            desktopHeight: 450,
            desktopWidth: 370,
            mobileHeight: '100%',
            timeFormat: 'HH:MM',
            displayMessageTime: false,
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endif
</body>

</html>
