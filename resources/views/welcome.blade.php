<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
    <!--Replace with your tailwind.css once created-->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet" />
    <!-- Define your gradient here - use online tools to find a gradient matching your branding-->
    <style>
        .gradient {
            background: rgb(2, 0, 36);
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(34, 34, 171, 1) 0%, rgba(0, 212, 255, 1) 100%);
        }
    </style>
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
    @routes
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
</head>

<body class="leading-normal tracking-normal text-white gradient" style="font-family: 'Source Sans Pro', sans-serif;">
    <nav id="header" class="fixed w-full z-30 top-5 text-white">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 py-2">
            <div class="pl-4 flex items-center">
                <a class="toggleColour text-white no-underline hover:no-underline font-bold text-2xl lg:text-4xl"
                    href="#">
                    <!--Icon from: http://www.potlabicons.com/ -->
                    <svg class="h-8 fill-current inline" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512.005 512.005">
                        <rect fill="#2a2a31" x="16.539" y="425.626" width="479.767" height="50.502"
                            transform="matrix(1,0,0,1,0,0)" />
                        <path class="plane-take-off"
                            d=" M 510.7 189.151 C 505.271 168.95 484.565 156.956 464.365 162.385 L 330.156 198.367 L 155.924 35.878 L 107.19 49.008 L 211.729 230.183 L 86.232 263.767 L 36.614 224.754 L 0 234.603 L 45.957 314.27 L 65.274 347.727 L 105.802 336.869 L 240.011 300.886 L 349.726 271.469 L 483.935 235.486 C 504.134 230.057 516.129 209.352 510.7 189.151 Z " />
                    </svg>
                    SIP Registry and Monitoring System
                </a>
            </div>
            <div class="block lg:hidden pr-4">
                <button id="nav-toggle"
                    class="flex items-center p-1 text-pink-800 hover:text-gray-900 focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                    <svg class="fill-current h-6 w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <title>Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>
        </div>
        <hr class="border-b border-gray-100 opacity-25 my-0 py-0" />
    </nav>
    <!--Hero-->
    <div class="pt-24">
        <div class="container px-3 mx-auto flex flex-wrap flex-col md:flex-row items-center">
            <!--Left Col-->
            <div class="flex flex-col w-full md:w-2/5 justify-center items-start text-center md:text-left">
                <p class="uppercase tracking-loose w-full">Student Internship Program (SIP) of CICT CATSU</p>
                <h1 class="my-4 text-5xl font-bold leading-tight">
                    Welcome!
                </h1>
                <p class="leading-normal text-2xl mb-8">
                    Manage and track key aspects of internships, including document submission, attendance, and journal entries.                </p>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}"
                            class="mx-auto lg:mx-0 hover:underline bg-white text-gray-800 font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                            Home
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="mx-auto lg:mx-0 hover:underline bg-white text-gray-800 font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                            Login
                        </a>
                    @endauth
                    </nav>
                @endif
            </div>
            <!--Right Col-->
            <div class="w-full md:w-3/5 py-6 text-center">
                <img class="w-full md:w-4/5 z-50" src="{{ asset('images/hero.png') }}" />
            </div>
        </div>
    </div>
</body>
</html>
