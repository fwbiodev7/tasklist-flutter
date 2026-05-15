<?php
class Donation {
    public static function create($teamId, $material, $qty, $points) {
        $db   = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO donations (team_id, material_type, quantity, points_awarded) VALUES (:tid, :m, :q, :p)");
        $stmt->bindValue(':tid', $teamId, PDO::PARAM_INT);
        $stmt->bindValue(':m',   $material, PDO::PARAM_STR);
        $stmt->bindValue(':q',   $qty);
        $stmt->bindValue(':p',   $points, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function delete($id) {
        $db = Database::getInstance();

        // Busca dados da doação antes de deletar
        $stmt = $db->prepare("SELECT team_id, points_awarded FROM donations WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $don = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$don) {
            return false;
        }

        $teamId = (int) $don['team_id'];
        $points = (int) $don['points_awarded'];

        // Desconta os pontos do time (mínimo 0)
        $update = $db->prepare("
            UPDATE teams
            SET total_points = GREATEST(0, total_points - :pts)
            WHERE id = :tid
        ");
        $update->bindValue(':pts', $points, PDO::PARAM_INT);
        $update->bindValue(':tid', $teamId,  PDO::PARAM_INT);
        $update->execute();

        // Deleta a doação
        $delete = $db->prepare("DELETE FROM donations WHERE id = :id");
        $delete->bindValue(':id', $id, PDO::PARAM_INT);
        return $delete->execute();
    }

    public static function getRecent($limit = 20) {
        $db   = Database::getInstance();
        $stmt = $db->prepare("
            SELECT d.*, t.name AS team_name
            FROM donations d
            JOIN teams t ON d.team_id = t.id
            ORDER BY d.created_at DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $teamId, $material, $qty, $points) {
        $db   = Database::getInstance();
        $stmt = $db->prepare("
            UPDATE donations
            SET team_id = :tid, material_type = :m, quantity = :q, points_awarded = :p
            WHERE id = :id
        ");
        $stmt->bindValue(':tid', $teamId,   PDO::PARAM_INT);
        $stmt->bindValue(':m',   $material,  PDO::PARAM_STR);
        $stmt->bindValue(':q',   $qty);
        $stmt->bindValue(':p',   $points,    PDO::PARAM_INT);
        $stmt->bindValue(':id',  $id,        PDO::PARAM_INT);
        return $stmt->execute();
    }
}