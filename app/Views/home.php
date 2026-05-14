<?php
$projectName = "COPA SOLIDÁRIA E SUSTENTÁVEL";
$subTitle = "Gincana Cultural, Social e Ambiental";
$edition = "Ensino Médio - 2026";

$materials = [
    [
        'category' => 'Higiene Pessoal',
        'items' => 'Pasta de dente, sabonete, escova de dente e papel higiênico.',
        'points' => '02 pontos por produto',
        'icon' => 'fas fa-soap',
        'color' => 'bg-blue-100 text-blue-600'
    ],
    [
        'category' => 'Vestuário',
        'items' => 'Roupas em bom estado (masc, fem ou infantil) e calçados.',
        'points' => '05 pontos por peça',
        'icon' => 'fas fa-tshirt',
        'color' => 'bg-orange-100 text-orange-600'
    ],
    [
        'category' => 'Leite de Caixinha',
        'items' => 'Caixas de leite vazias e limpas.',
        'points' => '10 pontos por litro',
        'icon' => 'fas fa-box-open',
        'color' => 'bg-green-100 text-green-600'
    ],
    [
        'category' => 'Recicláveis',
        'items' => 'Garrafas PET e latas de alumínio.',
        'points' => '05 pontos a cada 3 unidades',
        'icon' => 'fas fa-recycle',
        'color' => 'bg-emerald-100 text-emerald-600'
    ],
    [
        'category' => 'Lacres de Alumínio',
        'items' => 'Cada garrafa PET de 2 litros cheia de lacres.',
        'points' => '30 pontos por garrafa',
        'icon' => 'fas fa-check-circle',
        'color' => 'bg-amber-100 text-amber-600'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $projectName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap');
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; color: #1E392A; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E392A',
                        secondary: '#FF6B2C',
                        light: '#F1F5F2'
                    },
                    fontFamily: {
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="overflow-x-hidden">

    <!-- Admin Gear -->
    <div class="fixed top-8 right-8 z-[100]">
        <a href="/login" class="w-14 h-14 bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all duration-500 group border border-white/20">
            <i class="fas fa-user-shield text-xl group-hover:scale-110 transition"></i>
        </a>
    </div>

    <!-- Hero -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-primary">
        <div class="absolute inset-0 z-0 opacity-20">
            <div class="absolute top-0 left-0 w-96 h-96 bg-secondary rounded-full filter blur-[120px] -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-green-500 rounded-full filter blur-[120px] translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="relative z-10 text-center px-6 max-w-5xl mx-auto" data-aos="zoom-out">
            <span class="inline-block px-6 py-2 bg-white/10 backdrop-blur-sm rounded-full text-secondary font-black text-xs tracking-[0.3em] uppercase mb-8 border border-white/10">Abertura Oficial: 11 de Julho</span>
            <h1 class="text-6xl md:text-8xl lg:text-9xl font-display font-black text-white mb-6 leading-tight tracking-tighter">
                <?php echo $projectName; ?>
            </h1>
            <p class="text-xl md:text-2xl text-white/60 font-light mb-12 max-w-2xl mx-auto italic">
                <?php echo $subTitle; ?> • <?php echo $edition; ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="#ranking" class="px-12 py-5 bg-secondary text-white font-black rounded-2xl shadow-2xl hover:scale-105 transition-all duration-500 uppercase tracking-widest text-sm">Ver Placar ao Vivo</a>
                <a href="#materiais" class="px-12 py-5 bg-white/5 backdrop-blur-sm text-white border border-white/20 font-black rounded-2xl hover:bg-white/10 transition-all duration-500 uppercase tracking-widest text-sm">Regras de Pontuação</a>
            </div>
        </div>
    </section>

    <!-- Ranking -->
    <section id="ranking" class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-display font-black text-4xl mb-4">Placar da Gincana</h2>
                <div class="w-20 h-1.5 bg-secondary mx-auto rounded-full"></div>
                <p class="text-xs font-bold text-gray-400 mt-4 animate-pulse uppercase tracking-widest"><i class="fas fa-circle text-red-500 mr-2"></i> Atualizado em Tempo Real</p>
            </div>

            <div id="ranking-container">
                <?php if (empty($ranking)): ?>
                    <div class="bg-gray-50 p-20 rounded-[3rem] text-center italic text-gray-400" data-aos="zoom-in">Aguardando as primeiras doações...</div>
                <?php else: ?>
                    <div id="podium-grid" class="grid md:grid-cols-3 gap-8 mb-12">
                        <?php 
                        $icons = ['fa-crown text-amber-500', 'fa-medal text-slate-400', 'fa-award text-orange-400'];
                        for($i=0; $i<min(3, count($ranking)); $i++): ?>
                        <div class="p-10 rounded-[2.5rem] border-2 bg-light text-center relative group hover:bg-white transition duration-500" data-aos="fade-up" data-aos-delay="<?php echo $i*100; ?>">
                            <i class="fas <?php echo $icons[$i]; ?> text-5xl mb-6"></i>
                            <h3 class="text-2xl font-black text-primary"><?php echo $ranking[$i]['name']; ?></h3>
                            <p class="text-gray-400 font-bold mb-4 uppercase text-xs tracking-widest"><?php echo $ranking[$i]['country']; ?></p>
                            <div class="text-4xl font-black text-secondary"><?php echo $ranking[$i]['total_points']; ?> <span class="text-xs">PTS</span></div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="bg-light rounded-[3rem] p-10 shadow-inner overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-200">
                                    <th class="pb-6">Posição</th>
                                    <th class="pb-6">Nação / Turma</th>
                                    <th class="pb-6 text-right">Pontos</th>
                                </tr>
                            </thead>
                            <tbody id="ranking-body" class="divide-y divide-gray-200">
                                <?php foreach($ranking as $idx => $row): ?>
                                <tr class="border-b border-gray-100 last:border-0 transition-all duration-500">
                                    <td class="py-6 font-black text-xl">#<?php echo $idx+1; ?></td>
                                    <td class="py-6 font-bold text-primary"><?php echo $row['name']; ?> <span class="text-gray-400 text-sm font-medium ml-2">(<?php echo $row['country']; ?>)</span></td>
                                    <td class="py-6 text-right font-black text-secondary text-xl"><?php echo $row['total_points']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Materiais -->
    <section id="materiais" class="py-24 px-6 bg-light">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-display font-black text-4xl mb-4">Critérios de Pontuação</h2>
                <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($materials as $m): ?>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm hover:shadow-xl transition duration-500" data-aos="fade-up">
                    <div class="w-14 h-14 <?php echo $m['color']; ?> rounded-2xl flex items-center justify-center mb-6"><i class="<?php echo $m['icon']; ?> text-xl"></i></div>
                    <h4 class="font-black text-xl mb-2"><?php echo $m['category']; ?></h4>
                    <p class="text-gray-400 text-sm mb-6"><?php echo $m['items']; ?></p>
                    <span class="bg-primary text-white px-4 py-1 rounded-full text-xs font-black"><?php echo $m['points']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="bg-primary text-white py-12 px-6 text-center">
        <div class="max-w-7xl mx-auto">
            <p class="font-black text-xl mb-4 tracking-tighter">COPA SUSTENTÁVEL</p>
            <p class="text-white/40 text-xs font-bold uppercase tracking-[0.2em]">&copy; 2026 Interclasse Solidário e Sustentável.
                Desenvolvido por <a href="https://github.com/fwbiodev7" target="_blank" class="text-white">Fabio</a> e <a href="https://github.com/ujan029" target="_blank" class="text-white">João Pedro Matias</a>. 
            </p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({duration:1000, once:true});

        async function updateRanking() {
            try {
                const response = await fetch('/api/ranking');
                const data = await response.json();
                
                if (data.length === 0) return;

                const podiumGrid = document.getElementById('podium-grid');
                if (podiumGrid) {
                    const icons = ['fa-crown text-amber-500', 'fa-medal text-slate-400', 'fa-award text-orange-400'];
                    let podiumHtml = '';
                    for (let i = 0; i < Math.min(3, data.length); i++) {
                        podiumHtml += `
                            <div class="p-10 rounded-[2.5rem] border-2 bg-light text-center relative group hover:bg-white transition duration-500">
                                <i class="fas ${icons[i]} text-5xl mb-6"></i>
                                <h3 class="text-2xl font-black text-primary">${data[i].name}</h3>
                                <p class="text-gray-400 font-bold mb-4 uppercase text-xs tracking-widest">${data[i].country}</p>
                                <div class="text-4xl font-black text-secondary">${data[i].total_points} <span class="text-xs">PTS</span></div>
                            </div>
                        `;
                    }
                    podiumGrid.innerHTML = podiumHtml;
                }

                const tableBody = document.getElementById('ranking-body');
                if (tableBody) {
                    let tableHtml = '';
                    data.forEach((row, idx) => {
                        tableHtml += `
                            <tr class="border-b border-gray-100 last:border-0 transition-all duration-500">
                                <td class="py-6 font-black text-xl">#${idx+1}</td>
                                <td class="py-6 font-bold text-primary">${row.name} <span class="text-gray-400 text-sm font-medium ml-2">(${row.country})</span></td>
                                <td class="py-6 text-right font-black text-secondary text-xl">${row.total_points}</td>
                            </tr>
                        `;
                    });
                    tableBody.innerHTML = tableHtml;
                }
            } catch (error) {
                console.error("Update failed", error);
            }
        }
        setInterval(updateRanking, 5000);
    </script>
</body>
</html>
