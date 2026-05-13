<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel | Copa Sustentável</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E392A',
                        secondary: '#FF6B2C'
                    }
                }
            }
        };
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans text-primary">

    <div class="lg:hidden fixed top-6 right-6 z-[60]">
        <button onclick="toggleMenu()" class="w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-primary">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="flex min-h-screen">

        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-primary text-white p-8 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out flex flex-col">

            <div class="flex items-center space-x-3 mb-12">
                <i class="fas fa-medal text-2xl text-secondary"></i>
                <span class="font-black text-xl uppercase tracking-tighter">
                    Painel ADM
                </span>
            </div>

            <nav class="space-y-4 flex-1">

                <a href="/admin/dashboard"
                   class="flex items-center space-x-4 bg-white/10 p-5 rounded-3xl font-black text-sm uppercase tracking-widest">
                    <i class="fas fa-chart-line text-secondary"></i>
                    <span>Dashboard</span>
                </a>

                <a href="/admin/add-donation"
                   class="flex items-center space-x-4 hover:bg-white/5 p-5 rounded-3xl font-black text-sm uppercase tracking-widest text-white/60 hover:text-white transition-all">
                    <i class="fas fa-plus-circle"></i>
                    <span>Novo Lançamento</span>
                </a>

            </nav>

            <div class="mt-auto pt-8 border-t border-white/10">

                <a href="/logout"
                   class="flex items-center space-x-4 text-red-400 hover:text-red-300 font-black text-sm uppercase tracking-widest p-4">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                </a>

            </div>

        </aside>

        <div id="overlay"
             onclick="toggleMenu()"
             class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

        <main class="flex-1 p-6 lg:p-12">

            <div class="mb-12">
                <h1 class="text-4xl font-black tracking-tighter">
                    Dashboard
                </h1>

                <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.2em] mt-2">
                    Gincana Solidária
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

                <div class="bg-primary text-white p-8 rounded-[2.5rem] shadow-2xl">
                    <p class="text-white/50 font-black text-xs uppercase tracking-widest mb-2">
                        Pontos Totais
                    </p>

                    <h3 class="text-5xl font-black tracking-tighter">
                        <?php echo number_format($totalPoints, 0, ',', '.'); ?>
                    </h3>
                </div>

                <div class="bg-secondary text-white p-8 rounded-[2.5rem] shadow-2xl">
                    <p class="text-white/50 font-black text-xs uppercase tracking-widest mb-2">
                        Lançamentos
                    </p>

                    <h3 class="text-5xl font-black tracking-tighter">
                        <?php echo $totalDonations; ?>
                    </h3>
                </div>

                <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-xl flex items-center overflow-x-auto no-scrollbar space-x-6">

                    <?php
                    $matIcons = [
                        'higiene' => 'fa-soap',
                        'vestuario' => 'fa-tshirt',
                        'leite' => 'fa-box-open',
                        'reciclável' => 'fa-recycle',
                        'lacre' => 'fa-check-circle'
                    ];
                    ?>

                    <?php foreach($matIcons as $type => $icon): ?>

                        <div class="flex-shrink-0 flex flex-col items-center">

                            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center mb-1 text-primary">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>

                            <p class="text-[9px] font-black text-gray-400 uppercase">
                                <?php echo $type; ?>
                            </p>

                            <p class="font-black">
                                <?php echo isset($stats[$type])
                                    ? number_format($stats[$type]['total_qty'], 0)
                                    : '0'; ?>
                            </p>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                <div class="bg-white rounded-[3rem] p-10 shadow-xl">

                    <h2 class="text-2xl font-black mb-8">
                        Ranking Geral
                    </h2>

                    <table class="w-full text-sm">

                        <?php foreach($teams as $team): ?>

                            <tr class="border-b border-gray-50 last:border-0">

                                <td class="py-4 font-bold">
                                    <?php echo $team['name']; ?>
                                </td>

                                <td class="py-4 text-right font-black text-secondary">
                                    <?php echo $team['total_points']; ?> pts
                                </td>

                                <td class="py-4 text-right flex space-x-2 justify-end">

                                    <button
                                        type="button"
                                        onclick="handleAction('resetTeamPoints', <?php echo (int)$team['id']; ?>, 'Zerar pontos?'); return false;"
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg"
                                    >
                                        <i class="fas fa-undo"></i>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="handleAction('deleteTeam', <?php echo (int)$team['id']; ?>, 'Excluir turma?'); return false;"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </table>

                </div>

                <div class="bg-white rounded-[3rem] p-10 shadow-xl">

                    <h2 class="text-2xl font-black mb-8">
                        Últimos Lançamentos
                    </h2>

                    <div class="space-y-4 max-h-[600px] overflow-y-auto no-scrollbar">

                        <?php foreach($donations as $don): ?>

                            <div class="flex justify-between items-center p-5 bg-gray-50 rounded-2xl border border-transparent hover:border-gray-200 transition-all">

                                <div>

                                    <div class="font-black text-sm">
                                        <?php echo $don['team_name']; ?>
                                    </div>

                                    <div class="text-xs text-gray-400 uppercase font-bold tracking-widest mt-1">
                                        <?php echo $don['points_awarded']; ?>
                                        PTS •
                                        <?php echo $don['material_type']; ?>
                                    </div>

                                </div>

                                <button
                                    type="button"
                                    onclick="handleAction('deleteDonation', <?php echo (int)$don['id']; ?>, 'Excluir lançamento?'); return false;"
                                    class="text-red-500 hover:bg-red-50 p-3 rounded-xl transition-colors"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </main>

    </div>

    <script>

        function toggleMenu() {

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        async function handleAction(action, id, message) {

            if (!confirm(message)) {
                return false;
            }

            const routes = {
                deleteDonation: '/admin/delete-donation',
                deleteTeam: '/admin/delete-team',
                resetTeamPoints: '/admin/reset-team-points'
            };

            try {

                const response = await fetch(routes[action], {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'id=' + encodeURIComponent(id)
                });

                const text = await response.text();

                console.log(text);

                let data;

                try {
                    data = JSON.parse(text);
                } catch(e) {
                    console.error(text);
                    alert('Resposta inválida do servidor');
                    return false;
                }

                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro ao executar ação');
                    console.log(data);
                }

            } catch(err) {

                console.error(err);
                alert('Erro JavaScript');

            }

            return false;
        }

    </script>

</body>
</html>