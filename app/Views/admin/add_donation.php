<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Lançamento | Copa Sustentável</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#1E392A', secondary: '#FF6B2C' } } } }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-6 flex flex-col items-center justify-center min-h-screen">
    <!-- Mobile Back Button -->
    <div class="lg:hidden fixed top-6 left-6 z-[60]">
        <a href="/admin/dashboard" class="w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-primary">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
    </div>

    <div class="max-w-xl w-full bg-white p-10 lg:p-16 rounded-[4rem] shadow-2xl border border-gray-100 mt-12 lg:mt-0">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-black mb-2 tracking-tighter">Novo Lançamento</h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Registre os materiais coletados</p>
        </div>

        <form method="POST" action="/admin/save-donation" class="space-y-8">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Turma / Nação</label>
                <select name="team_id" required class="w-full p-6 bg-gray-50 rounded-3xl border-none outline-none font-bold text-primary focus:ring-2 focus:ring-secondary appearance-none">
                    <?php foreach($teams as $t): ?>
                        <option value="<?php echo $t['id']; ?>"><?php echo $t['name']; ?> - <?php echo $t['country']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Tipo de Material</label>
                <select name="material_type" required class="w-full p-6 bg-gray-50 rounded-3xl border-none outline-none font-bold text-primary focus:ring-2 focus:ring-secondary appearance-none">
                    <option value="higiene">Higiene (02 pts/un)</option>
                    <option value="vestuario">Vestuário (05 pts/peça)</option>
                    <option value="leite">Leite Caixinha (10 pts/litro)</option>
                    <option value="reciclável">Recicláveis (05 pts/3un)</option>
                    <option value="lacre">Lacre PET 2L (30 pts/garrafa)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Quantidade</label>
                <input type="number" name="quantity" step="any" required placeholder="Digite a quantidade..." class="w-full p-6 bg-gray-50 rounded-3xl border-none outline-none font-bold text-primary focus:ring-2 focus:ring-secondary">
            </div>

            <button type="submit" class="w-full bg-primary text-white p-7 rounded-3xl font-black shadow-xl hover:bg-[#2D5A42] transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                REGISTRAR PONTUAÇÃO
            </button>
        </form>

        <div class="mt-12 text-center">
            <a href="/admin/dashboard" class="text-gray-400 font-black text-xs uppercase tracking-widest hover:text-secondary transition">Voltar ao Painel</a>
        </div>
    </div>
</body>
</html>
