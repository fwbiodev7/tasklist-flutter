<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Copa Sustentável</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#1E392A', secondary: '#FF6B2C' } } } }
    </script>
</head>
<body class="bg-gray-100 font-sans text-primary">
    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-6 right-6 z-[60]">
        <button onclick="toggleMenu()" class="w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-primary">
            <i class="fas fa-bars text-xl" id="menu-icon"></i>
        </button>
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-primary text-white p-8 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out flex flex-col">
            <div class="flex items-center justify-between mb-12">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-medal text-2xl text-secondary"></i>
                    <span class="font-black text-xl tracking-tighter uppercase">Painel ADM</span>
                </div>
                <button onclick="toggleMenu()" class="lg:hidden text-white/50 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="space-y-4 flex-1">
                <a href="/admin/dashboard" class="flex items-center space-x-4 bg-white/10 p-5 rounded-3xl font-black text-sm uppercase tracking-widest transition-all">
                    <i class="fas fa-chart-line w-6 text-secondary"></i> 
                    <span>Dashboard</span>
                </a>
                <a href="/admin/add-donation" class="flex items-center space-x-4 hover:bg-white/5 p-5 rounded-3xl font-black text-sm uppercase tracking-widest transition-all text-white/60 hover:text-white">
                    <i class="fas fa-plus-circle w-6"></i> 
                    <span>Novo Lançamento</span>
                </a>
            </nav>

            <div class="mt-auto pt-8 border-t border-white/10">
                <a href="/logout" class="flex items-center space-x-4 text-red-400 hover:text-red-300 font-black text-sm uppercase tracking-widest p-4 transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair do Sistema</span>
                </a>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="overlay" onclick="toggleMenu()" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

        <main class="flex-1 p-6 lg:p-12 transition-all">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h1 class="text-4xl font-black tracking-tighter">Dashboard</h1>
                    <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.2em] mt-2">Visão Geral da Gincana</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-primary text-white px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg">
                        <?php echo date('d M, Y'); ?>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-primary text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-white/50 font-black text-xs uppercase tracking-widest mb-2">Pontuação Total</p>
                        <h3 class="text-5xl font-black tracking-tighter"><?php echo number_format($totalPoints, 0, ',', '.'); ?></h3>
                    </div>
                    <i class="fas fa-star absolute -bottom-4 -right-4 text-white/5 text-8xl group-hover:scale-110 transition duration-500"></i>
                </div>

                <div class="bg-secondary text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-white/50 font-black text-xs uppercase tracking-widest mb-2">Total Lançamentos</p>
                        <h3 class="text-5xl font-black tracking-tighter"><?php echo $totalDonations; ?></h3>
                    </div>
                    <i class="fas fa-file-invoice absolute -bottom-4 -right-4 text-white/5 text-8xl group-hover:scale-110 transition duration-500"></i>
                </div>

                <?php 
                $matIcons = [
                    'higiene' => ['icon' => 'fa-soap', 'color' => 'bg-blue-100 text-blue-600', 'label' => 'Higiene'],
                    'vestuario' => ['icon' => 'fa-tshirt', 'color' => 'bg-orange-100 text-orange-600', 'label' => 'Roupas'],
                    'leite' => ['icon' => 'fa-box-open', 'color' => 'bg-green-100 text-green-600', 'label' => 'Leite'],
                    'reciclável' => ['icon' => 'fa-recycle', 'color' => 'bg-emerald-100 text-emerald-600', 'label' => 'Recicláveis'],
                    'lacre' => ['icon' => 'fa-check-circle', 'color' => 'bg-amber-100 text-amber-600', 'label' => 'Lacres']
                ];
                ?>

                <!-- Material Breakdown (Dynamic) -->
                <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-xl flex items-center overflow-x-auto no-scrollbar space-x-6">
                    <?php foreach($matIcons as $type => $info): ?>
                    <div class="flex-shrink-0 flex flex-col items-center">
                        <div class="w-12 h-12 <?php echo $info['color']; ?> rounded-2xl flex items-center justify-center mb-2 shadow-sm">
                            <i class="fas <?php echo $info['icon']; ?> text-lg"></i>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1"><?php echo $info['label']; ?></p>
                        <p class="font-black text-primary"><?php echo isset($stats[$type]) ? number_format($stats[$type]['total_qty'], 0) : '0'; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="bg-white rounded-[3rem] p-10 shadow-xl">
                    <h2 class="text-2xl font-black mb-8">Ranking</h2>
                    <table class="w-full">
                        <?php foreach($teams as $team): ?>
                        <tr>
                            <td class="py-4 font-bold"><?php echo $team['name']; ?></td>
                            <td class="py-4 text-right font-black text-secondary"><?php echo $team['total_points']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="bg-white rounded-[3rem] p-10 shadow-xl">
                    <h2 class="text-2xl font-black mb-8">Histórico</h2>
                    <div class="space-y-4">
                        <?php foreach($donations as $don): ?>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl" id="don-<?php echo $don['id']; ?>">
                            <div>
                                <div class="font-black text-sm"><?php echo $don['team_name']; ?></div>
                                <div class="text-xs text-gray-400"><?php echo $don['points_awarded']; ?> pts</div>
                            </div>
                            <a href="/admin/delete-donation?donation_id=<?php echo $don['id']; ?>" class="text-red-500 hover:text-red-700 transition-colors p-2" onclick="return confirm('Tem certeza que deseja excluir?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    deleteDonation(id);
                });
            });
        });

        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');
            
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        async function deleteDonation(id) {
            if (!confirm('Deseja realmente excluir este registro?')) return;
            
            try {
                const formData = new FormData();
                formData.append('donation_id', id);

                const res = await fetch('/admin/delete-donation', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro: ' + data.error);
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                alert('Erro de conexão.');
            }
        }
    </script>
</body>
</html>
