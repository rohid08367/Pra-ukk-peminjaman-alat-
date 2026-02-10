<?php session_start(); ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-96 animate-fade-in">
    <h2 class="text-2xl font-bold text-center mb-6">Login Aplikasi</h2>

    <form action="process_login.php" method="POST">
        <input type="email" name="email" placeholder="Email"
            class="w-full p-2 border rounded mb-4" required>

        <input type="password" name="password" placeholder="Password"
            class="w-full p-2 border rounded mb-4" required>

        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Login
        </button>
    </form>
</div>

</body>
</html>
