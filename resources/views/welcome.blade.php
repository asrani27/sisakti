<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI SAKTI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #0f172a;
            color: #e2e8f0;
        }

        /* Gradient background - light on right side */
        .gradient-bg {
            background: linear-gradient(90deg, #020617 0%, #0f172a 25%, #1e293b 50%, #334155 75%, #475569 100%);
            position: relative;
            overflow: hidden;
        }

        /* Moving shapes */
        .shape {
            position: absolute;
            opacity: 1;
            pointer-events: none;
        }

        /* Circles */
        .circle {
            border-radius: 50%;
            border: 5px solid rgba(99, 102, 241, 1);
            background: rgba(99, 102, 241, 0.35);
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.7), inset 0 0 30px rgba(99, 102, 241, 0.3);
            animation: floatCircle 20s ease-in-out infinite;
        }

        .circle-1 {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            left: 15%;
            animation-delay: 2s;
            animation-direction: reverse;
        }

        .circle-3 {
            width: 120px;
            height: 120px;
            top: 30%;
            left: 70%;
            animation-delay: 4s;
        }

        .circle-4 {
            width: 160px;
            height: 160px;
            top: 75%;
            left: 80%;
            animation-delay: 6s;
            animation-direction: reverse;
        }

        .circle-5 {
            width: 100px;
            height: 100px;
            top: 85%;
            left: 40%;
            animation-delay: 8s;
        }

        .circle-6 {
            width: 130px;
            height: 130px;
            top: 15%;
            left: 60%;
            animation-delay: 10s;
            animation-direction: reverse;
        }

        /* Squares */
        .square {
            border: 5px solid rgba(139, 92, 246, 1);
            background: rgba(139, 92, 246, 0.35);
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.7), inset 0 0 30px rgba(139, 92, 246, 0.3);
            animation: floatSquare 25s ease-in-out infinite;
        }

        .square-1 {
            width: 120px;
            height: 120px;
            top: 20%;
            left: 30%;
            animation-delay: 1s;
            animation: rotateSquare 15s linear infinite;
        }

        .square-2 {
            width: 100px;
            height: 100px;
            top: 70%;
            left: 50%;
            animation-delay: 3s;
            animation: rotateSquare 20s linear infinite reverse;
        }

        .square-3 {
            width: 140px;
            height: 140px;
            top: 50%;
            left: 85%;
            animation-delay: 5s;
            animation: rotateSquare 18s linear infinite;
        }

        .square-4 {
            width: 90px;
            height: 90px;
            top: 40%;
            left: 10%;
            animation-delay: 7s;
            animation: rotateSquare 22s linear infinite reverse;
        }

        .square-5 {
            width: 110px;
            height: 110px;
            top: 90%;
            left: 20%;
            animation-delay: 9s;
            animation: rotateSquare 16s linear infinite;
        }

        /* Circle animations */
        @keyframes floatCircle {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            25% {
                transform: translateY(-30px) scale(1.1);
            }

            50% {
                transform: translateY(15px) scale(0.95);
            }

            75% {
                transform: translateY(-20px) scale(1.05);
            }
        }

        /* Square animations */
        @keyframes floatSquare {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(20px, -25px) scale(1.08);
            }

            50% {
                transform: translate(-15px, 18px) scale(0.92);
            }

            75% {
                transform: translate(-25px, -15px) scale(1.03);
            }
        }

        @keyframes rotateSquare {
            0% {
                transform: rotate(0deg) translate(0, 0);
            }

            25% {
                transform: rotate(90deg) translate(20px, -20px);
            }

            50% {
                transform: rotate(180deg) translate(-20px, 20px);
            }

            75% {
                transform: rotate(270deg) translate(-20px, -20px);
            }

            100% {
                transform: rotate(360deg) translate(0, 0);
            }
        }

        /* Glass morphism effect for dark theme */
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), 0 0 40px rgba(99, 102, 241, 0.1);
        }

        /* Input focus effect */
        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .input-group:focus-within input {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            background: rgba(30, 41, 59, 0.8);
        }

        /* Button hover effect */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4), 0 0 30px rgba(99, 102, 241, 0.2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Social button hover */
        .social-btn {
            transition: all 0.3s ease;
            background: rgba(30, 41, 59, 0.5);
            border-color: rgba(148, 163, 184, 0.2);
        }

        .social-btn:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
        }

        /* Text colors for dark theme */
        .text-gray-800 {
            color: #e2e8f0 !important;
        }

        .text-gray-700 {
            color: #cbd5e1 !important;
        }

        .text-gray-600 {
            color: rgba(226, 232, 240, 0.7) !important;
        }

        .text-gray-500 {
            color: rgba(226, 232, 240, 0.5) !important;
        }

        .text-gray-400 {
            color: rgba(226, 232, 240, 0.4) !important;
        }

        /* Input styles */
        input {
            background: rgba(30, 41, 59, 0.5) !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
            color: #e2e8f0 !important;
        }

        input::placeholder {
            color: rgba(226, 232, 240, 0.4) !important;
        }

        input:focus {
            background: rgba(30, 41, 59, 0.8) !important;
            border-color: #6366f1 !important;
        }

        /* Checkbox styles */
        input[type="checkbox"] {
            background: rgba(30, 41, 59, 0.5) !important;
            border-color: rgba(148, 163, 184, 0.3) !important;
        }

        /* Link colors */
        .text-indigo-600 {
            color: #a5b4fc !important;
        }

        .text-indigo-600:hover {
            color: #c4b5fd !important;
        }

        /* Divider */
        .border-gray-200 {
            border-color: rgba(148, 163, 184, 0.2) !important;
        }

        /* Success message */
        .bg-green-50 {
            background: rgba(34, 197, 94, 0.15) !important;
            border-color: rgba(34, 197, 94, 0.3) !important;
        }

        .text-green-600 {
            color: #86efac !important;
        }

        .text-green-800 {
            color: #86efac !important;
        }

        /* Error message */
        .bg-red-50 {
            background: rgba(239, 68, 68, 0.15) !important;
            border-color: rgba(239, 68, 68, 0.3) !important;
        }

        .text-red-600 {
            color: #fca5a5 !important;
        }

        .text-red-800 {
            color: #fca5a5 !important;
        }

        /* Form labels */
        label {
            color: #cbd5e1 !important;
        }
    </style>
</head>

<body class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <!-- Moving circles -->
    <div class="shape circle circle-1"></div>
    <div class="shape circle circle-2"></div>
    <div class="shape circle circle-3"></div>
    <div class="shape circle circle-4"></div>
    <div class="shape circle circle-5"></div>
    <div class="shape circle circle-6"></div>

    <!-- Moving squares -->
    <div class="shape square square-1"></div>
    <div class="shape square square-2"></div>
    <div class="shape square square-3"></div>
    <div class="shape square square-4"></div>
    <div class="shape square square-5"></div>

    <!-- Main container -->
    <div class="relative z-10 w-full max-w-5xl">
        <div class="flex flex-col lg:flex-row glass-card rounded-3xl overflow-hidden shadow-2xl">
            <!-- Left Column - Branding -->
            <div
                class="lg:w-1/2 bg-gradient-to-br from-indigo-600/90 to-purple-700/90 p-8 lg:p-12 flex flex-col justify-center items-center text-center relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2">
                </div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/5 rounded-full blur-3xl">
                </div>

                <div class="relative z-10">
                    <!-- Logo -->
                    <div class="mb-8 flex justify-center">
                        <img src="{{ asset('logo/sakti.png') }}" alt="SI SAKTI Logo"
                            class="w-32 lg:w-40 h-auto drop-shadow-lg">
                    </div>

                    <!-- Brand info -->
                    <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">SI SAKTI</h1>
                    <p class="text-white/80 text-sm lg:text-base leading-relaxed mb-8">
                        Sistem Integrasi Sinkronisasi Audit Ketataan Instansi - Solusi terpercaya untuk manajemen audit
                        yang efisien dan akuntabel.
                    </p>

                    <!-- Features -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-center space-x-3">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-shield-alt text-white"></i>
                            </div>
                            <span class="text-white/90 text-sm">Keamanan Terjamin</span>
                        </div>
                        <div class="flex items-center justify-center space-x-3">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-sync-alt text-white"></i>
                            </div>
                            <span class="text-white/90 text-sm">Sinkronisasi Real-time</span>
                        </div>
                        <div class="flex items-center justify-center space-x-3">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-chart-bar text-white"></i>
                            </div>
                            <span class="text-white/90 text-sm">Laporan Komprehensif</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom text -->
                <div class="relative z-10 mt-8 lg:mt-0">
                    <p class="text-white/60 text-xs">
                        <i class="fas fa-lock mr-1"></i> Login Aman & Terenkripsi
                    </p>
                </div>
            </div>

            <!-- Right Column - Login Form -->
            <div
                class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center bg-gradient-to-br from-slate-800/50 to-slate-900/50">
                <div class="max-w-md mx-auto w-full">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2">Selamat Datang Kembali</h2>
                        <p class="text-gray-400 text-sm">Silakan login untuk mengakses dashboard</p>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-green-800 text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2 mt-0.5"></i>
                            <div class="text-red-800 text-sm">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                        @csrf
                        <!-- Username Field -->
                        <div class="input-group transition-all duration-300">
                            <label for="username"
                                class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input id="username" name="username" type="text" required
                                    class="pl-11 pr-4 py-3 w-full bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-200"
                                    placeholder="Masukkan username Anda">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="input-group transition-all duration-300">
                            <label for="password"
                                class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" name="password" type="password" required
                                    class="pl-11 pr-12 py-3 w-full bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-200"
                                    placeholder="Masukkan password Anda">
                            </div>
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" id="remember"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                            <a href="#"
                                class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full btn-primary py-3.5 px-6 rounded-xl text-white font-semibold text-base shadow-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Akun
                        </button>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 text-gray-500 font-medium">Atau lanjutkan dengan</span>
                            </div>
                        </div>

                        <!-- Alternative Login -->
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button"
                                class="social-btn flex items-center justify-center py-3 px-4 border-2 border-gray-200 rounded-xl hover:border-indigo-300">
                                <i class="fas fa-id-card text-indigo-400 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">SSO</span>
                            </button>
                            <button type="button"
                                class="social-btn flex items-center justify-center py-3 px-4 border-2 border-gray-200 rounded-xl hover:border-indigo-300">
                                <i class="fas fa-fingerprint text-indigo-400 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Biometrik</span>
                            </button>
                        </div>
                    </form>

                    <!-- Help text -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">
                            Butuh bantuan?
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                                Hubungi Support
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-gray-400 text-sm">
                &copy; 2024 SI SAKTI. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</body>

</html>