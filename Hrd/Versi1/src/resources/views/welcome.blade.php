<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HRTOOL | PT Sankei Medical</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: '#00756c', // Teal medical
                        secondary: '#0e2927',
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-slate-50 font-sans">
    <div class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden">
        
        <div class="absolute inset-0 z-0 opacity-20" style="background-image: radial-gradient(#0D9488 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>

        <div class="relative z-10 w-full max-w-xl px-6 text-center">
            
            <div class="mb-8 flex justify-center">
                <div class="w-20 h-20 bg-primary text-white rounded-2xl shadow-xl flex items-center justify-center rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>

            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight sm:text-5xl mb-4">
                HR TOOL
                <span class="block text-primary text-3xl sm:text-4xl mt-2 font-semibold">PT Sankei Medical Industries</span>
            </h1>
            
            <p class="text-lg text-slate-600 mb-10 max-w-md mx-auto leading-relaxed">
                Sistem Informasi Sumber Daya Manusia manajemen data karyawan dan monitoring perusahaan.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @auth
                    <a href="{{ url('/admin') }}" class="w-full sm:w-auto px-8 py-4 bg-primary hover:bg-secondary text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}" class="w-full sm:w-auto px-8 py-4 bg-primary hover:bg-secondary text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                        Masuk ke Portal
                    </a>

                    
                @endauth
            </div>

            <footer class="mt-20 text-slate-400 text-sm">
                &copy; {{ date('D-M-Y') }} PT Sankei Medical Industries. Managed by HR Dept.
            </footer>
        </div>

        <div class="absolute top-0 -left-4 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
</body>
</html>