// app.js - Banco de dados local usando LocalStorage para GitHub Pages

const DB_KEY = 'copa_sustentavel_data_v2';

// Dados iniciais (apenas se o banco estiver vazio)
const defaultData = {
    teams: [
        { "id": 1, "name": "2º LOG", "country": "Brasil", "total_points": 0 },
        { "id": 2, "name": "2º ELE", "country": "México", "total_points": 0 },
        { "id": 3, "name": "1º LOG", "country": "Estados Unidos", "total_points": 0 },
        { "id": 4, "name": "3º SIST", "country": "Nova Zelândia", "total_points": 0 },
        { "id": 5, "name": "1º ELE", "country": "Marrocos", "total_points": 0 },
        { "id": 6, "name": "2º PROP", "country": "França", "total_points": 0 },
        { "id": 7, "name": "1º SIST", "country": "Portugal", "total_points": 0 },
        { "id": 8, "name": "2º SIST", "country": "Alemanha", "total_points": 0 },
        { "id": 9, "name": "3º PROP", "country": "Inglaterra", "total_points": 0 },
        { "id": 10, "name": "1º INF", "country": "Espanha", "total_points": 0 },
        { "id": 11, "name": "3º LOG", "country": "Catar", "total_points": 0 },
        { "id": 12, "name": "3º ELE", "country": "Coreia do Sul", "total_points": 0 }
    ],
    donations: [],
    material_rules: {
        'higiene': { points: 2, per: 1 },
        'vestuario': { points: 5, per: 1 },
        'leite': { points: 10, per: 1 },
        'reciclável': { points: 5, per: 3 },
        'lacre': { points: 30, per: 1 }
    }
};

// Inicializa o banco de dados
function initDB() {
    if (!localStorage.getItem(DB_KEY)) {
        localStorage.setItem(DB_KEY, JSON.stringify(defaultData));
    }
}

// Retorna todos os dados
function getDB() {
    initDB();
    return JSON.parse(localStorage.getItem(DB_KEY));
}

// Salva os dados
function saveDB(data) {
    localStorage.setItem(DB_KEY, JSON.stringify(data));
}

// Recalcula os pontos totais de todas as turmas baseado nas doações
function recalculatePoints() {
    const data = getDB();
    
    // Zera pontos de todos
    data.teams.forEach(t => t.total_points = 0);
    
    // Soma os pontos das doações
    data.donations.forEach(don => {
        const team = data.teams.find(t => t.id === don.team_id);
        if (team) {
            team.total_points += don.points_awarded;
        }
    });
    
    // Ordena o ranking
    data.teams.sort((a, b) => b.total_points - a.total_points);
    
    saveDB(data);
}

// Adiciona uma doação
function addDonation(teamId, materialType, quantity) {
    const data = getDB();
    const rule = data.material_rules[materialType];
    
    if (!rule) return false;
    
    // Calcula pontos (Ex: a cada 3 recicláveis = 5 pts, então (qty / 3) * 5)
    let points = 0;
    if (materialType === 'reciclável') {
        points = Math.floor(quantity / 3) * 5;
    } else {
        points = quantity * rule.points;
    }
    
    const team = data.teams.find(t => t.id === parseInt(teamId));
    if (!team) return false;

    const newDonation = {
        id: Date.now(), // ID único baseado no timestamp
        team_id: team.id,
        team_name: team.name,
        material_type: materialType,
        quantity: quantity,
        points_awarded: points,
        created_at: new Date().toISOString()
    };
    
    data.donations.unshift(newDonation); // Adiciona no início
    saveDB(data);
    recalculatePoints();
    return true;
}

// Deleta uma doação
function deleteDonation(id) {
    const data = getDB();
    data.donations = data.donations.filter(d => d.id !== id);
    saveDB(data);
    recalculatePoints();
}

// Deleta uma turma e suas doações
function deleteTeam(id) {
    const data = getDB();
    data.teams = data.teams.filter(t => t.id !== id);
    data.donations = data.donations.filter(d => d.team_id !== id);
    saveDB(data);
    recalculatePoints();
}

// Zera os pontos de uma turma (deleta suas doações)
function resetTeamPoints(id) {
    const data = getDB();
    data.donations = data.donations.filter(d => d.team_id !== id);
    saveDB(data);
    recalculatePoints();
}

// Estatísticas para o Dashboard
function getStats() {
    const data = getDB();
    let totalPoints = 0;
    let totalDonations = data.donations.length;
    let materialStats = {
        'higiene': 0, 'vestuario': 0, 'leite': 0, 'reciclável': 0, 'lacre': 0
    };
    
    data.donations.forEach(don => {
        totalPoints += don.points_awarded;
        if (materialStats[don.material_type] !== undefined) {
            materialStats[don.material_type] += parseInt(don.quantity);
        }
    });
    
    return {
        totalPoints,
        totalDonations,
        materialStats
    };
}
