<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Gestão da Copa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#1E392A', secondary: '#FF6B2C' } } } }
    </script>
</head>
<body class="bg-primary flex items-center justify-center min-h-screen p-6">
    <div class="bg-white p-10 lg:p-16 rounded-[3.5rem] shadow-2xl w-full max-w-lg border border-white/20">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-black mb-2 tracking-tighter">Acesso Admin</h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Gincana Solidária 2026</p>
        </div>

        <form method="POST" action="/admin/auth" class="space-y-6">
            <div class="relative group">
                <input type="text" name="username" placeholder="Usuário" required class="w-full p-6 bg-gray-50 rounded-3xl border-none outline-none font-bold text-primary focus:ring-2 focus:ring-secondary transition">
            </div>
            <div class="relative group">
                <input type="password" name="password" placeholder="Senha" required class="w-full p-6 bg-gray-50 rounded-3xl border-none outline-none font-bold text-primary focus:ring-2 focus:ring-secondary transition">
            </div>
            
            <?php if(isset($_GET['error'])): ?>
                <p class="text-red-500 text-xs font-black text-center uppercase tracking-widest">Credenciais Inválidas</p>
            <?php endif; ?>

            <button type="submit" class="w-full bg-primary text-white p-6 rounded-3xl font-black shadow-xl hover:bg-[#FF6B2C] transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                ENTRAR NO SISTEMA
            </button>
        </form>

        <div class="mt-12 text-center">
            <a href="/home" class="text-gray-400 font-black text-xs uppercase tracking-widest hover:text-secondary transition">Voltar ao Início</a>
        </div>
    </div>
</body>
</html>
