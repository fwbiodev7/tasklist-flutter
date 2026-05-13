<?php
class AdminController {
    
    // Essa é a função que estava faltando e causando o erro fatal
    public function dashboard() {
        checkLogin();
        $teams = Team::getAll();
        $donations = Donation::getRecent(50);
        
        $db = Database::getInstance();
        
        // Estatísticas
        $totalDonations = $db->querySingle("SELECT COUNT(*) FROM donations");
        $totalPoints = $db->querySingle("SELECT SUM(points_awarded) FROM donations") ?? 0;
        
        $res = $db->query("SELECT material_type, SUM(quantity) as total_qty, SUM(points_awarded) as total_pts FROM donations GROUP BY material_type");
        $stats = [];
        if ($res) {
            while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
                $stats[$row['material_type']] = $row;
            }
        }
        
        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function addDonation() {
        checkLogin();
        $teams = Team::getAll();
        require_once __DIR__ . '/../Views/admin/add_donation.php';
    }

    public function saveDonation() {
        checkLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tid = $_POST['team_id'];
            $mat = $_POST['material_type'];
            $qty = $_POST['quantity'];
            $pts = calculatePoints($mat, $qty);
            
            if (Donation::create($tid, $mat, $qty, $pts)) {
                Team::updatePoints($tid, $pts);
                header("Location: /admin/dashboard?success=1");
            } else {
                header("Location: /admin/dashboard?error=1");
            }
            exit;
        }
    }

    // Função de deletar - Agora configurada para aceitar o ID via POST ou URL
    public function deleteDonation($id = null) {
        checkLogin();
        
        if (!$id) {
            $id = isset($_POST['donation_id']) ? intval($_POST['donation_id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
        }

        file_put_contents('debug.txt', "DELETE DONATION ATTEMPT: ID=$id, POST=" . json_encode($_POST) . "\n", FILE_APPEND);

        $success = false;
        if ($id > 0) {
            $success = Donation::delete($id);
        }

        file_put_contents('debug.txt', "DELETE DONATION RESULT: " . ($success ? 'SUCCESS' : 'FAILURE') . "\n", FILE_APPEND);

        // Se for uma chamada do JavaScript (AJAX)
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }

        header("Location: /admin/dashboard");
        exit;
    }

    public function deleteTeam($id = null) {
        checkLogin();
        if (!$id) $id = isset($_POST['team_id']) ? intval($_POST['team_id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
        
        file_put_contents('debug.txt', "DELETE TEAM ATTEMPT: ID=$id\n", FILE_APPEND);
        $success = Team::delete($id);
        file_put_contents('debug.txt', "DELETE TEAM RESULT: " . ($success ? 'SUCCESS' : 'FAILURE') . "\n", FILE_APPEND);

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        header("Location: /admin/dashboard");
        exit;
    }

    public function resetTeamPoints($id = null) {
        checkLogin();
        if (!$id) $id = isset($_POST['team_id']) ? intval($_POST['team_id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
        
        file_put_contents('debug.txt', "RESET POINTS ATTEMPT: ID=$id\n", FILE_APPEND);
        $success = Team::resetPoints($id);
        file_put_contents('debug.txt', "RESET POINTS RESULT: " . ($success ? 'SUCCESS' : 'FAILURE') . "\n", FILE_APPEND);

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        header("Location: /admin/dashboard");
        exit;
    }
}