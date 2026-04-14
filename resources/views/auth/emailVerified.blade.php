<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>WorkStation</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/emailVerify.js', 'resources/js/slipLoginLogout.js'])

    <link rel="stylesheet" href="style.css" />

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

<body class="bg-linear-to-r from-[#e2e2e2] to-[#c9d6ff] flex items-center justify-center h-screen">
    <div
        class="flex overflow-hidden bg-white rounded-[30px] shadow-[0_5px_15px_rgba(0,0,0,0.35)] w-3xl max-w-full min-h-120">
        <div
            class="flex-1 bg-[url(../../public/4872300.jpg)] bg-cover bg-center bg-blend-multiply rounded-[0px_150px_100px_0]">
        </div>
        <div class="flex-1 flex flex-col text-center">
            <i class="fa-regular fa-envelope-open text-4xl text-blue-400 mt-28"></i>
            <h1 class="text-2xl font-bold text-blue-400 mt-5">
                Forgot your password?
            </h1>
            <p class="text-xs mt-2 font-medium">
                Your password will be reset by email
            </p>
            <div class="text-left mt-8 ml-15 h-10 w-70">
                <p class="ml-1 text-xs font-bold">Email Address</p>
                <input type="email"
                    class="mt-1 border border-gray-400 h-full w-full rounded-xl hover:border-gray-900 focus:ring-4 focus:ring-blue-400 p-2 text-[13px]" />
            </div>
            <div class="mt-10 ml-15 h-8 w-70 px-5">
                <button
                    class="border w-full h-full bg-white rounded-lg text-blue-400 text-xs uppercase cursor-pointer hover:text-white hover:bg-blue-400 font-bold transition-all duration-300 ease-in-out mb-4">
                    Send email
                </button>
                <button onclick="window.location.href='/logIn'"
                    class="border w-full h-full bg-white rounded-lg text-gray-800 font-bold text-xs uppercase cursor-pointer hover:text-white hover:bg-gray-400 transition-all duration-300 ease-in-out">
                    Back to sign in
                </button>
            </div>
        </div>
    </div>
</body>

</html>
