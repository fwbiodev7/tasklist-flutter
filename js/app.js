// app.js — Copa Sustentável | Firebase Firestore

// =====================================================
// TIMES INICIAIS (populados automaticamente no 1º acesso)
// =====================================================
const TEAMS_INITIAL = [
    { id: "team_01", name: "2º LOG",  country: "Brasil"         },
    { id: "team_02", name: "2º ELE",  country: "México"         },
    { id: "team_03", name: "1º LOG",  country: "Estados Unidos" },
    { id: "team_04", name: "3º SIST", country: "Nova Zelândia"  },
    { id: "team_05", name: "1º ELE",  country: "Marrocos"       },
    { id: "team_06", name: "2º PROP", country: "França"         },
    { id: "team_07", name: "1º SIST", country: "Portugal"       },
    { id: "team_08", name: "2º SIST", country: "Alemanha"       },
    { id: "team_09", name: "3º PROP", country: "Inglaterra"     },
    { id: "team_10", name: "1º INF",  country: "Espanha"        },
    { id: "team_11", name: "3º LOG",  country: "Catar"          },
    { id: "team_12", name: "3º ELE",  country: "Coreia do Sul"  },
];

const MATERIAL_RULES = {
    'higiene':    { points: 2  },
    'vestuario':  { points: 5  },
    'leite':      { points: 10 },
    'reciclavel': { points: 5, per: 3 },
    'lacre':      { points: 30 },
};

// =====================================================
// INICIALIZAÇÃO — sincroniza times que estiverem faltando
// =====================================================
async function initDB() {
    const snap = await db.collection('teams').get();
    const existingIds = snap.docs.map(doc => doc.id);
    
    const batch = db.batch();
    let added = 0;
    
    TEAMS_INITIAL.forEach(t => {
        if (!existingIds.includes(t.id)) {
            const ref = db.collection('teams').doc(t.id);
            batch.set(ref, {
                name:         t.name,
                country:      t.country,
                total_points: 0,
                created_at:   firebase.firestore.FieldValue.serverTimestamp()
            });
            added++;
        }
    });
    
    if (added > 0) {
        await batch.commit();
        console.log(`✅ ${added} times adicionados ao Firestore!`);
    }
}

// =====================================================
// LEITURA COMPLETA
// =====================================================
async function getDB() {
    await initDB();
    const [teamsSnap, donationsSnap] = await Promise.all([
        db.collection('teams').orderBy('total_points', 'desc').get(),
        db.collection('donations').orderBy('created_at', 'desc').get(),
    ]);

    const teams     = teamsSnap.docs.map(d => ({ id: d.id, ...d.data() }));
    const donations = donationsSnap.docs.map(d => ({ id: d.id, ...d.data() }));
    return { teams, donations };
}

// Listener em tempo real para o ranking
function listenToRanking(callback) {
    return db.collection('teams')
        .orderBy('total_points', 'desc')
        .onSnapshot(snap => {
            const teams = snap.docs.map(d => ({ id: d.id, ...d.data() }));
            callback(teams);
        });
}

// =====================================================
// CÁLCULO DE PONTOS
// =====================================================
function calcPoints(materialType, quantity) {
    quantity = parseFloat(quantity);
    if (materialType === 'reciclavel') return Math.floor(quantity / 3) * 5;
    const rule = MATERIAL_RULES[materialType];
    return rule ? quantity * rule.points : 0;
}

// =====================================================
// ADICIONAR DOAÇÃO
// =====================================================
async function addDonation(teamId, materialType, quantity) {
    quantity = parseFloat(quantity);
    const points = calcPoints(materialType, quantity);
    if (points <= 0) return false;

    const teamDoc = await db.collection('teams').doc(teamId).get();
    if (!teamDoc.exists) return false;

    await db.collection('donations').add({
        team_id:       teamId,
        team_name:     teamDoc.data().name,
        material_type: materialType,
        quantity:      quantity,
        points_awarded: points,
        created_at:    firebase.firestore.FieldValue.serverTimestamp(),
    });

    await db.collection('teams').doc(teamId).update({
        total_points: firebase.firestore.FieldValue.increment(points),
    });

    return true;
}

// =====================================================
// DELETAR DOAÇÃO
// =====================================================
async function deleteDonation(donationId) {
    const donRef = db.collection('donations').doc(donationId);
    const donDoc = await donRef.get();
    if (!donDoc.exists) return;

    const don = donDoc.data();
    const teamRef = db.collection('teams').doc(don.team_id);
    const teamDoc = await teamRef.get();
    const current = teamDoc.exists ? (teamDoc.data().total_points || 0) : 0;

    const batch = db.batch();
    batch.update(teamRef, { total_points: Math.max(0, current - (don.points_awarded || 0)) });
    batch.delete(donRef);
    await batch.commit();
}

// =====================================================
// DELETAR TURMA
// =====================================================
async function deleteTeam(teamId) {
    const donSnap = await db.collection('donations').where('team_id', '==', teamId).get();
    const batch = db.batch();
    donSnap.docs.forEach(d => batch.delete(d.ref));
    batch.delete(db.collection('teams').doc(teamId));
    await batch.commit();
}

// =====================================================
// ZERAR PONTOS DA TURMA
// =====================================================
async function resetTeamPoints(teamId) {
    const donSnap = await db.collection('donations').where('team_id', '==', teamId).get();
    const batch = db.batch();
    donSnap.docs.forEach(d => batch.delete(d.ref));
    batch.update(db.collection('teams').doc(teamId), { total_points: 0 });
    await batch.commit();
}

// =====================================================
// ESTATÍSTICAS PARA O DASHBOARD
// =====================================================
async function getStats() {
    const snap = await db.collection('donations').get();
    let totalPoints = 0;
    let totalDonations = snap.size;
    let materialStats = { 'higiene': 0, 'vestuario': 0, 'leite': 0, 'reciclavel': 0, 'lacre': 0 };

    snap.docs.forEach(d => {
        const don = d.data();
        totalPoints += don.points_awarded || 0;
        if (materialStats[don.material_type] !== undefined) {
            materialStats[don.material_type] += parseFloat(don.quantity) || 0;
        }
    });

    return { totalPoints, totalDonations, materialStats };
}
