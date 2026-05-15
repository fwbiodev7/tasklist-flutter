<?php

require_once __DIR__ . '/../../core/helpers.php';
require_once __DIR__ . '/../Models/Team.php';
require_once __DIR__ . '/../Models/Donation.php';

class AdminController {

    public function dashboard() {

        checkLogin();

        $teams     = Team::getAll();
        $donations = Donation::getRecent();

        $db = Database::getInstance();

        $totalPointsQuery  = $db->query("SELECT SUM(total_points) as total FROM teams");
        $totalPointsResult = $totalPointsQuery->fetch(PDO::FETCH_ASSOC);
        $totalPoints       = $totalPointsResult['total'] ?? 0;

        $totalDonationsQuery  = $db->query("SELECT COUNT(*) as total FROM donations");
        $totalDonationsResult = $totalDonationsQuery->fetch(PDO::FETCH_ASSOC);
        $totalDonations       = $totalDonationsResult['total'] ?? 0;

        $statsQuery = $db->query("
            SELECT material_type, SUM(quantity) as total_qty
            FROM donations
            GROUP BY material_type
        ");

        $stats = [];
        while ($row = $statsQuery->fetch(PDO::FETCH_ASSOC)) {
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/add-donation');
            exit;
        }

        $teamId       = intval($_POST['team_id']);
        $materialType = trim($_POST['material_type']);
        $quantity     = floatval($_POST['quantity']);
        $points       = intval($_POST['points_awarded']);

        $db = Database::getInstance();

        $stmt = $db->prepare("
            INSERT INTO donations (team_id, material_type, quantity, points_awarded)
            VALUES (:team_id, :material_type, :quantity, :points_awarded)
        ");
        $stmt->bindValue(':team_id',       $teamId,       PDO::PARAM_INT);
        $stmt->bindValue(':material_type', $materialType, PDO::PARAM_STR);
        $stmt->bindValue(':quantity',      $quantity);
        $stmt->bindValue(':points_awarded',$points,       PDO::PARAM_INT);
        $stmt->execute();

        $update = $db->prepare("
            UPDATE teams
            SET total_points = total_points + :points
            WHERE id = :id
        ");
        $update->bindValue(':points', $points, PDO::PARAM_INT);
        $update->bindValue(':id',     $teamId, PDO::PARAM_INT);
        $update->execute();

        header('Location: /admin/dashboard?success=1');
        exit;
    }

    public function deleteDonation() {

        checkLogin();

        header('Content-Type: application/json');

        try {

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'error' => 'Método inválido']);
                exit;
            }

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            if ($id <= 0) {
                echo json_encode(['success' => false, 'error' => 'ID inválido']);
                exit;
            }

            $db = Database::getInstance();

            $getDonation = $db->prepare("SELECT * FROM donations WHERE id = :id");
            $getDonation->bindValue(':id', $id, PDO::PARAM_INT);
            $getDonation->execute();
            $donation = $getDonation->fetch(PDO::FETCH_ASSOC);

            if (!$donation) {
                echo json_encode(['success' => false, 'error' => 'Doação não encontrada']);
                exit;
            }

            $teamId = (int) $donation['team_id'];
            $points = (int) $donation['points_awarded'];

            $updateTeam = $db->prepare("
                UPDATE teams
                SET total_points = GREATEST(0, total_points - :points)
                WHERE id = :team_id
            ");
            $updateTeam->bindValue(':points',  $points, PDO::PARAM_INT);
            $updateTeam->bindValue(':team_id', $teamId, PDO::PARAM_INT);
            $updateTeam->execute();

            $delete = $db->prepare("DELETE FROM donations WHERE id = :id");
            $delete->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $delete->execute();

            echo json_encode(['success' => $result, 'deleted_id' => $id]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function deleteTeam() {

        checkLogin();

        header('Content-Type: application/json');

        try {

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            if ($id <= 0) {
                echo json_encode(['success' => false]);
                exit;
            }

            $db = Database::getInstance();

            $deleteDonations = $db->prepare("DELETE FROM donations WHERE team_id = :id");
            $deleteDonations->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteDonations->execute();

            $deleteTeam = $db->prepare("DELETE FROM teams WHERE id = :id");
            $deleteTeam->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $deleteTeam->execute();

            echo json_encode(['success' => $result]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function resetTeamPoints() {

        checkLogin();

        header('Content-Type: application/json');

        try {

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            if ($id <= 0) {
                echo json_encode(['success' => false]);
                exit;
            }

            $db = Database::getInstance();

            $stmt = $db->prepare("UPDATE teams SET total_points = 0 WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            echo json_encode(['success' => $result]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }
}