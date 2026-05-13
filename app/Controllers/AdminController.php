<?php

require_once __DIR__ . '/../../core/helpers.php';
require_once __DIR__ . '/../Models/Team.php';
require_once __DIR__ . '/../Models/Donation.php';

class AdminController {

    public function dashboard() {

        checkLogin();

        $teams = Team::getAll();
        $donations = Donation::getRecent();

        $db = Database::getInstance();

        $totalPointsQuery = $db->query("
            SELECT SUM(total_points) as total
            FROM teams
        ");

        $totalPointsResult = $totalPointsQuery->fetchArray(SQLITE3_ASSOC);

        $totalPoints = $totalPointsResult['total'] ?? 0;

        $totalDonationsQuery = $db->query("
            SELECT COUNT(*) as total
            FROM donations
        ");

        $totalDonationsResult = $totalDonationsQuery->fetchArray(SQLITE3_ASSOC);

        $totalDonations = $totalDonationsResult['total'] ?? 0;

        $statsQuery = $db->query("
            SELECT
                material_type,
                SUM(quantity) as total_qty
            FROM donations
            GROUP BY material_type
        ");

        $stats = [];

        while ($row = $statsQuery->fetchArray(SQLITE3_ASSOC)) {
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

        $teamId = intval($_POST['team_id']);
        $materialType = trim($_POST['material_type']);
        $quantity = floatval($_POST['quantity']);
        $points = intval($_POST['points_awarded']);

        $db = Database::getInstance();

        $stmt = $db->prepare("
            INSERT INTO donations (
                team_id,
                material_type,
                quantity,
                points_awarded
            )
            VALUES (
                :team_id,
                :material_type,
                :quantity,
                :points_awarded
            )
        ");

        $stmt->bindValue(':team_id', $teamId, SQLITE3_INTEGER);
        $stmt->bindValue(':material_type', $materialType, SQLITE3_TEXT);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_FLOAT);
        $stmt->bindValue(':points_awarded', $points, SQLITE3_INTEGER);

        $stmt->execute();

        $update = $db->prepare("
            UPDATE teams
            SET total_points = total_points + :points
            WHERE id = :id
        ");

        $update->bindValue(':points', $points, SQLITE3_INTEGER);
        $update->bindValue(':id', $teamId, SQLITE3_INTEGER);

        $update->execute();

        header('Location: /admin/dashboard?success=1');
        exit;
    }

    public function deleteDonation() {

        checkLogin();

        header('Content-Type: application/json');

        try {

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

                echo json_encode([
                    'success' => false,
                    'error' => 'Método inválido'
                ]);

                exit;
            }

            $id = isset($_POST['id'])
                ? intval($_POST['id'])
                : 0;

            if ($id <= 0) {

                echo json_encode([
                    'success' => false,
                    'error' => 'ID inválido'
                ]);

                exit;
            }

            $db = Database::getInstance();

            $getDonation = $db->prepare("
                SELECT *
                FROM donations
                WHERE id = :id
            ");

            $getDonation->bindValue(':id', $id, SQLITE3_INTEGER);

            $donationResult = $getDonation->execute();

            $donation = $donationResult->fetchArray(SQLITE3_ASSOC);

            if (!$donation) {

                echo json_encode([
                    'success' => false,
                    'error' => 'Doação não encontrada'
                ]);

                exit;
            }

            $teamId = intval($donation['team_id']);
            $points = intval($donation['points_awarded']);

            $updateTeam = $db->prepare("
                UPDATE teams
                SET total_points = CASE
                    WHEN total_points - :points < 0 THEN 0
                    ELSE total_points - :points
                END
                WHERE id = :team_id
            ");

            $updateTeam->bindValue(':points', $points, SQLITE3_INTEGER);
            $updateTeam->bindValue(':team_id', $teamId, SQLITE3_INTEGER);

            $updateTeam->execute();

            $delete = $db->prepare("
                DELETE FROM donations
                WHERE id = :id
            ");

            $delete->bindValue(':id', $id, SQLITE3_INTEGER);

            $result = $delete->execute();

            echo json_encode([
                'success' => $result ? true : false,
                'deleted_id' => $id
            ]);

        } catch(Exception $e) {

            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }

    public function deleteTeam() {

        checkLogin();

        header('Content-Type: application/json');

        try {

            $id = isset($_POST['id'])
                ? intval($_POST['id'])
                : 0;

            if ($id <= 0) {

                echo json_encode([
                    'success' => false
                ]);

                exit;
            }

            $db = Database::getInstance();

            $deleteDonations = $db->prepare("
                DELETE FROM donations
                WHERE team_id = :id
            ");

            $deleteDonations->bindValue(':id', $id, SQLITE3_INTEGER);

            $deleteDonations->execute();

            $deleteTeam = $db->prepare("
                DELETE FROM teams
                WHERE id = :id
            ");

            $deleteTeam->bindValue(':id', $id, SQLITE3_INTEGER);

            $result = $deleteTeam->execute();

            echo json_encode([
                'success' => $result ? true : false
            ]);

        } catch(Exception $e) {

            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }

    public function resetTeamPoints() {

        checkLogin();

        header('Content-Type: application/json');

        try {

            $id = isset($_POST['id'])
                ? intval($_POST['id'])
                : 0;

            if ($id <= 0) {

                echo json_encode([
                    'success' => false
                ]);

                exit;
            }

            $db = Database::getInstance();

            $stmt = $db->prepare("
                UPDATE teams
                SET total_points = 0
                WHERE id = :id
            ");

            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

            $result = $stmt->execute();

            echo json_encode([
                'success' => $result ? true : false
            ]);

        } catch(Exception $e) {

            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }
}