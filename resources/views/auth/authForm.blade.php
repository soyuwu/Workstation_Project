<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>WorkStation</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/emailVerify.js', 'resources/js/slipLoginLogout.js'])

    <link rel="stylesheet" href="./resources/css/authForm.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        montserrat: ["Montserrat", "sans-serif"],
                    },
                    keyframes: {
                        move: {
                            "0%, 49.99%": {
                                opacity: "0",
                                zIndex: "1",
                            },
                            "50%, 100%": {
                                opacity: "1",
                                zIndex: "5",
                            },
                        },
                    },
                    zIndex: {
                        1: "1",
                        2: "2",
                        5: "5",
                    },
                },
            },
        };
    </script>
    <style>
        /* Cấu hình thêm reset cho Tailwind để đảm bảo giống hệt bản gốc */
        body {
            font-family: Montserrat, sans-serif;
            margin: 0;
            box-sizing: border-box;
            padding: 0;
        }
    </style>
</head>

<body class="bg-linear-to-r from-[#e2e2e2] to-[#c9d6ff] flex items-center justify-center flex-col h-screen">
    <div class="relative overflow-hidden bg-white rounded-[30px] shadow-[0_5px_15px_rgba(0,0,0,0.35)] w-4xl max-w-full min-h-140 group {{ request()->is('register') ? 'active' : '' }}"
        id="container">
        <div
            class="absolute top-0 h-full w-1/2 left-0 opacity-0 z-1 transition-all duration-[0.6s] ease-in-out group-[.active]:translate-x-full group-[.active]:opacity-100 group-[.active]:z-5 group-[.active]:animate-[move_0.6s] sign-up">
            <form method="POST" action="/register"
                class="bg-white flex items-center justify-center text-center flex-col px-10 h-full">
                @csrf
                <h1 class="text-3xl font-bold m-0">Create Account</h1>
                <div class="my-4 flex gap-2">
                    <div
                        class="hover:text-red-400 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-google"></i>
                    </div>
                    <div
                        class="hover:text-purple-700 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-github"></i>
                    </div>
                    <div
                        class="hover:text-blue-600 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-facebook"></i>
                    </div>
                    <div
                        class="hover:text-orange-500 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-apple"></i>
                    </div>
                </div>
                <span class="text-[12px]">or use your email for registeration</span>
                <input type="text" name="name" placeholder="Name"
                    class="bg-[#eee] border-none my-2 px-3.75 py-2.5 text-[13px] rounded-lg w-full outline-none"
                    required />
                <input type="email" name="email" placeholder="Email"
                    class="bg-[#eee] border-none my-2 px-3.75 py-2.5 text-[13px] rounded-lg w-full outline-none"
                    required />
                <input type="password" name="password" placeholder="Password"
                    class="bg-[#eee] border-none my-2 px-3.75 py-2.5 text-[13px] rounded-lg w-full outline-none"
                    required />
                <button type="submit"
                    class="hover:bg-[#512da8] bg-purple-600 text-white text-[12px] border border-transparent py-2.5 px-11.25 rounded-lg font-semibold uppercase mt-2.5 tracking-[0.5px] cursor-pointer">
                    Sign up
                </button>
            </form>
        </div>
        <div
            class="absolute top-0 h-full w-1/2 left-0 z-2 transition-all duration-[0.6s] ease-in-out group-[.active]:translate-x-full sign-in">
            <form method="POST" action="/logIn"
                class="bg-white flex items-center justify-center flex-col px-10 h-full text-center">
                @csrf
                <h1 class="text-3xl font-bold m-0">Sign In</h1>
                <div class="my-4 flex gap-2">
                    <div
                        class="hover:text-red-400 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-google"></i>
                    </div>
                    <div
                        class="hover:text-purple-700 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-github"></i>
                    </div>
                    <div
                        class="hover:text-blue-600 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-facebook"></i>
                    </div>
                    <div
                        class="hover:text-orange-500 hover:scale-105 transition-transform duration-300 ease-in-out cursor-pointer border rounded-[20%] border-[#ccc] inline-flex items-center justify-center mx-0.75 w-10 h-10 text-[#333] no-underline">
                        <i class="fa-brands fa-apple"></i>
                    </div>
                </div>
                <span class="text-[12px]">or use your email password</span>
                <input type="email" name="email" placeholder="Email"
                    class="bg-[#eee] border-none my-2 px-3.75 py-2.5 text-[13px] rounded-lg w-full outline-none"
                    required />
                <input type="password" name="password" placeholder="Password"
                    class="bg-[#eee] border-none my-2 px-3.75 py-2.5 text-[13px] rounded-lg w-full outline-none"
                    required />
                <a id = "emailVerified-btn"
                    class="text-[#333] text-[13px] no-underline m-1 border-white rounded-3xl py-2 px-4 hover:bg-gray-300 hover:text-red-600 transition-all duration-200 ease-in-out cursor-pointer">Forgot
                    password?</a>
                <button type="submit"
                    class="hover:bg-[#512da8] bg-purple-600 text-white text-[12px] border border-transparent py-2.5 px-11.25 rounded-lg font-semibold uppercase mt-2.5 tracking-[0.5px] cursor-pointer">
                    Sign in
                </button>
            </form>
        </div>
        <div
            class="absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-all duration-[0.6s] ease-in-out rounded-[150px_0_0_100px] z-1000 group-[.active]:-translate-x-full group-[.active]:rounded-[0_150px_100px_0]">
            <div
                class="bg-[url('../../../public/4872300.jpg')] bg-cover bg-center bg-blend-multiply bg-linear-to-r from-[#5c6bc0]/90 to-[#512da8]/90 text-white relative -left-full h-full w-[200%] transition-all duration-[0.6s] ease-in-out translate-x-0 group-[.active]:translate-x-1/2">
                <div
                    class="absolute w-1/2 h-full flex flex-col items-center justify-center px-7.5 text-center top-0 transition-all duration-[0.6s] ease-in-out left-0 -translate-x-[200%] group-[.active]:translate-x-0">
                    <h1 class="text-3xl font-bold m-0">Welcome Back!</h1>
                    <p class="text-[14px] leading-5 tracking-[0.3px] my-5">
                        Enter your personal details to use all of site
                        features
                    </p>
                    <button
                        class="bg-transparent border-white text-white text-[12px] py-2.5 px-11.25 border rounded-lg font-semibold tracking-[0.5px] uppercase mt-2.5 cursor-pointer"
                        id="login">
                        Sign In
                    </button>
                </div>
                <div
                    class="absolute w-1/2 h-full flex flex-col items-center justify-center px-7.5 text-center top-0 transition-all duration-[0.6s] ease-in-out right-0 translate-x-0 group-[.active]:translate-x-[200%]">
                    <h1 class="text-3xl font-bold m-0">Hello, Friend!</h1>
                    <p class="text-[14px] leading-5 tracking-[0.3px] my-5">
                        Register with your personal details to use all of
                        site features
                    </p>
                    <button
                        class="bg-transparent border-white text-white text-[12px] py-2.5 px-11.25 border rounded-lg font-semibold tracking-[0.5px] uppercase mt-2.5 cursor-pointer"
                        id="register">
                        Sign Up
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

