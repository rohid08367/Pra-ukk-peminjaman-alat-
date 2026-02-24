<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Background slow animation */
        body {
            background-size: 400% 400%;
            animation: bgMove 12s ease infinite;
        }

        @keyframes bgMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Blobs Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .blob {
            position: absolute;
            z-index: -1;
            filter: blur(60px);
            opacity: 0.5;
            animation: float 8s ease-in-out infinite;
        }

        /* Card animation */
        .fade-slide {
            animation: fadeSlide 0.7s ease-out forwards;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Hover glow card */
        .card-glow:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(-4px);
        }

        /* Floating label logic */
        .input-group {
            position: relative;
        }

        .input-group label {
            position: absolute;
            top: 50%;
            left: 14px;
            color: #9ca3af;
            font-size: 14px;
            transform: translateY(-50%);
            pointer-events: none;
            transition: 0.25s ease;
            background: white;
            padding: 0 4px;
        }

        .input-group.active label {
            top: -6px;
            font-size: 12px;
            color: #3b82f6;
        }

        .input-group input {
            padding-top: 14px;
        }

        /* Input focus */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59,130,246,0.3);
            border-color: #3b82f6;
        }

        /* Button press */
        .btn-press:active {
            transform: scale(0.97);
        }

        /* Error shake */
        .shake {
            animation: shake 0.4s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }

        /* FIX AUTOFILL COLOR */
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
            box-shadow: 0 0 0 1000px white inset !important;
            -webkit-text-fill-color: #000 !important;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-600 via-purple-600 to-blue-700 flex items-center justify-center h-screen overflow-hidden relative">

    <div class="blob w-72 h-72 bg-blue-300 rounded-full -top-10 -left-10"></div>
    <div class="blob w-96 h-96 bg-purple-400 rounded-full bottom-[-10%] right-[-5%]" style="animation-delay: -2s;"></div>
    <div class="blob w-64 h-64 bg-pink-400 rounded-full top-[20%] right-[20%]" style="animation-delay: -4s;"></div>
    <div class="blob w-48 h-48 bg-cyan-300 rounded-full bottom-[20%] left-[15%]" style="animation-delay: -6s;"></div>

    <div class="bg-white/90 backdrop-blur-sm p-8 rounded-2xl shadow-2xl w-96 fade-slide card-glow transition-all duration-300 border border-white/20">

        <div class="flex justify-center mb-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Selamat Datang</h2>
        <p class="text-center text-gray-500 mb-8 text-sm">
            Silakan masuk untuk melanjutkan akses
        </p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4 text-sm shake">
                <?= $_SESSION['error']; ?>
            </div>
        <?php unset($_SESSION['error']); endif; ?>

        <form action="process_login.php" method="POST" id="loginForm" novalidate>

            <div class="input-group mb-5">
                <input type="email" name="email" id="email" required
                       class="w-full p-3 border border-gray-300 rounded-xl outline-none input-focus transition-all">
                <label for="email">Email</label>
            </div>

            <div class="input-group mb-8">
                <input type="password" name="password" id="password" required
                       class="w-full p-3 border border-gray-300 rounded-xl outline-none input-focus transition-all">
                <label for="password">Password</label>
            </div>

            <button type="submit" id="loginBtn"
                class="w-full bg-blue-600 text-white py-3 rounded-xl 
                       hover:bg-blue-700 hover:shadow-lg transition-all duration-200 btn-press font-semibold shadow-md">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-gray-400">
            &copy; 2026 Your App. All rights reserved.
        </p>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const btn  = document.getElementById('loginBtn');

        // Floating label handler
        document.querySelectorAll('.input-group input').forEach(input => {
            const group = input.parentElement;

            const checkValue = () => {
                if (input.value.trim() !== '') {
                    group.classList.add('active');
                } else {
                    group.classList.remove('active');
                }
            };

            input.addEventListener('focus', () => group.classList.add('active'));
            input.addEventListener('blur', checkValue);
            input.addEventListener('input', checkValue);

            // Initial check for browser autofill
            setTimeout(checkValue, 100);
        });

        // Submit loading state
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) return;

            btn.disabled = true;
            btn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            `;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>

</body>
</html>