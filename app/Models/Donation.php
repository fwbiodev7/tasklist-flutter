<?php
class Donation {
    public static function create($teamId, $material, $qty, $points) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO donations (team_id, material_type, quantity, points_awarded) VALUES (:tid, :m, :q, :p)");
        $stmt->bindValue(':tid', $teamId);
        $stmt->bindValue(':m', $material);
        $stmt->bindValue(':q', $qty);
        $stmt->bindValue(':p', $points);
        return $stmt->execute();
    }

    public static function delete($id) {

    $db = Database::getInstance();

    $stmt = $db->prepare("
        SELECT team_id, points_awarded
        FROM donations
        WHERE id = :id
    ");

    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    $res = $stmt->execute();

    if (!$res) {
        return false;
    }

    $don = $res->fetchArray(SQLITE3_ASSOC);

    if (!$don) {
        return false;
    }

    $teamId = intval($don['team_id']);
    $points = intval($don['points_awarded']);

    $update = $db->prepare("
        UPDATE teams
        SET total_points = CASE
            WHEN total_points - :pts < 0 THEN 0
            ELSE total_points - :pts
        END
        WHERE id = :tid
    ");

    $update->bindValue(':pts', $points, SQLITE3_INTEGER);
    $update->bindValue(':tid', $teamId, SQLITE3_INTEGER);

    $update->execute();

    $delete = $db->prepare("
        DELETE FROM donations
        WHERE id = :id
    ");

    $delete->bindValue(':id', $id, SQLITE3_INTEGER);

    $result = $delete->execute();

    return $result !== false;
}

    public static function getRecent($limit = 20) {
        $db = Database::getInstance();
        $res = $db->query("SELECT d.*, t.name as team_name FROM donations d JOIN teams t ON d.team_id = t.id ORDER BY d.created_at DESC LIMIT $limit");
        $donations = [];
        if ($res) {
            while ($row = $res->fetchArray(SQLITE3_ASSOC)) { $donations[] = $row; }
        }
        return $donations;
    }

    public static function update($id, $teamId, $material, $qty, $points) {
        // ... (Mantenha igual estava, pois não afeta o deletar)
    }
}