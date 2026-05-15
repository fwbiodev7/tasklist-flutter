// Configuração do Firebase — Copa Sustentável
// Projeto: copa-sustentavel-site

const firebaseConfig = {
  apiKey:            "AIzaSyA1cIHBbogWr_Nr63zB1o4JyDvi-jrE5NM",
  authDomain:        "copa-sustentavel-site.firebaseapp.com",
  projectId:         "copa-sustentavel-site",
  storageBucket:     "copa-sustentavel-site.firebasestorage.app",
  messagingSenderId: "483202084571",
  appId:             "1:483202084571:web:d2b7fe6968a9799dcca6fe"
};

// Inicializa Firebase e Firestore (usando compat SDK via CDN)
firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();
