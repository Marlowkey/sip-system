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
    <nav id="header" class="fixed z-30 w-full text-white top-5">
        <div class="container flex flex-wrap items-center justify-between w-full py-2 mx-auto mt-0">
            <div class="flex items-center pl-4">
                <a class="text-2xl font-bold text-white no-underline toggleColour hover:no-underline lg:text-4xl"
                    href="#">
                    <!--Icon from: http://www.potlabicons.com/ -->
                    <svg fill="#FFFFFF"class="inline h-12 fill-current" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 318.486 318.486" xml:space="preserve">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <path d="M111.104,114.167h40.639v22.743c0,4.142,3.357,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-22.743h40.639c4.143,0,7.5-3.358,7.5-7.5 V59.455c0-4.142-3.357-7.5-7.5-7.5h-96.277c-4.143,0-7.5,3.358-7.5,7.5v47.211C103.604,110.809,106.962,114.167,111.104,114.167z M118.604,66.955h81.277v32.211h-81.277V66.955z"></path>
                                <path d="M103.776,204.32H7.5c-4.143,0-7.5,3.358-7.5,7.5v47.211c0,4.142,3.357,7.5,7.5,7.5h96.276c4.143,0,7.5-3.358,7.5-7.5 V211.82C111.276,207.678,107.919,204.32,103.776,204.32z M96.276,251.531H15V219.32h81.276V251.531z"></path>
                                <path d="M310.986,227.925c-4.143,0-7.5,3.358-7.5,7.5v16.106H222.21V219.32h88.776c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5 h-40.855c-2.47-29.993-27.652-53.649-58.272-53.649H106.627c-22.51,0-43.274,13.154-52.9,33.511 c-1.771,3.745-0.17,8.216,3.574,9.986c3.746,1.77,8.215,0.17,9.986-3.574c7.159-15.14,22.601-24.923,39.34-24.923h105.232 c22.343,0,40.797,16.94,43.212,38.649H214.71c-4.143,0-7.5,3.358-7.5,7.5v47.211c0,4.142,3.357,7.5,7.5,7.5h96.276 c4.143,0,7.5-3.358,7.5-7.5v-23.606C318.486,231.283,315.129,227.925,310.986,227.925z"></path>
                            </g>
                        </g>
                    </svg>
                     SIP Registry and Monitoring System
                </a>
            </div>
            <div class="block pr-4 lg:hidden">
                <button id="nav-toggle"
                    class="flex items-center p-1 text-pink-800 transition duration-300 ease-in-out transform hover:text-gray-900 focus:outline-none focus:shadow-outline hover:scale-105">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <title>Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>
        </div>
        <hr class="py-0 my-0 border-b border-gray-100 opacity-25" />
    </nav>
    <!--Hero-->
    <div class="pt-24">
        <div class="container flex flex-col flex-wrap items-center px-3 mx-auto md:flex-row">
            <!--Left Col-->
            <div class="flex flex-col items-start justify-center w-full text-center md:w-2/5 md:text-left">
                <p class="w-full uppercase tracking-loose">Student Internship Program (SIP) of CICT CATSU</p>
                <h1 class="my-4 text-5xl font-bold leading-tight">
                    Welcome!
                </h1>
                <p class="mb-8 text-2xl leading-normal">
                    Manage and track key aspects of internships, including document submission, attendance, and journal entries.                </p>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}"
                            class="px-8 py-2 mx-auto my-6 font-bold text-gray-800 transition duration-300 ease-in-out transform bg-white rounded-full shadow-lg lg:mx-0 hover:underline focus:outline-none focus:shadow-outline hover:scale-105">
                            Home
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-8 py-2 mx-auto my-6 font-bold text-gray-800 transition duration-300 ease-in-out transform bg-white rounded-full shadow-lg lg:mx-0 hover:underline focus:outline-none focus:shadow-outline hover:scale-105">
                            Login
                        </a>
                    @endauth
                    </nav>
                @endif
            </div>
            <!--Right Col-->
            <div class="w-full py-6 text-center md:w-3/5">
                <img class="z-50 w-full md:w-4/5" src="{{ asset('images/hero.png') }}" />
            </div>
        </div>
    </div>
</body>
</html>
