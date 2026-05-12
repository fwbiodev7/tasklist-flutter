<?php
class AdminController {
    
    public function dashboard() {
        checkLogin();
        $teams = Team::getAll();
        $donations = Donation::getRecent(50);
        
        $db = Database::getInstance();
        
        // Stats
        $totalDonations = $db->querySingle("SELECT COUNT(*) FROM donations");
        $totalPoints = $db->querySingle("SELECT SUM(points_awarded) FROM donations") ?? 0;
        
        // Breakdown by Material
        $res = $db->query("SELECT material_type, SUM(quantity) as total_qty, SUM(points_awarded) as total_pts FROM donations GROUP BY material_type");
        $stats = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $stats[$row['material_type']] = $row;
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
            }
            header("Location: /admin/dashboard");
            exit;
        }
    }

    public function deleteDonation() {
        $id = isset($_REQUEST['donation_id']) ? intval($_REQUEST['donation_id']) : 0;
        $success = ($id > 0 && Donation::delete($id));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        
        header("Location: /admin/dashboard");
        exit;
    }

    // placeholder for edit/update
    public function editDonation($id) {
        checkLogin();
        // To be implemented
    }
}
?>
