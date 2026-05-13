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
        checkLogin();
        $id = isset($_REQUEST['donation_id']) ? intval($_REQUEST['donation_id']) : 0;
        file_put_contents('debug.txt', "Attempting to delete donation ID: $id\n", FILE_APPEND);
        if ($id > 0) {
            $res = Donation::delete($id);
            file_put_contents('debug.txt', "Donation::delete result: " . ($res ? 'SUCCESS' : 'FAILURE') . "\n", FILE_APPEND);
        }
        header("Location: /admin/dashboard");
        exit;
    }

    public function deleteTeam() {
        checkLogin();
        $id = isset($_REQUEST['team_id']) ? intval($_REQUEST['team_id']) : 0;
        file_put_contents('debug.txt', "Attempting to delete team ID: $id\n", FILE_APPEND);
        if ($id > 0) {
            $res = Team::delete($id);
            file_put_contents('debug.txt', "Team::delete result: " . ($res ? 'SUCCESS' : 'FAILURE') . "\n", FILE_APPEND);
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
