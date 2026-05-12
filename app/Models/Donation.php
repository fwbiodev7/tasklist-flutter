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
        
        $stmt = $db->prepare("SELECT team_id, points_awarded FROM donations WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $res = $stmt->execute();
        $don = $res->fetchArray(SQLITE3_ASSOC);
        
        if ($don) {
            $tid = intval($don['team_id']);
            $pts = intval($don['points_awarded']);
            
            try {
                $db->exec("BEGIN TRANSACTION");
                $db->exec("UPDATE teams SET total_points = total_points - $pts WHERE id = $tid");
                $db->exec("DELETE FROM donations WHERE id = $id");
                $db->exec("UPDATE teams SET total_points = 0 WHERE id = $tid AND total_points < 0");
                $db->exec("COMMIT");
                return true;
            } catch (Exception $e) {
                $db->exec("ROLLBACK");
                return false;
            }
        }
        return false;
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
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT team_id, points_awarded FROM donations WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $res = $stmt->execute();
        $old = $res->fetchArray(SQLITE3_ASSOC);
        if (!$old) return false;

        $oldTid = $old['team_id'];
        $oldPts = $old['points_awarded'];
        
        $db->exec("UPDATE teams SET total_points = total_points - $oldPts WHERE id = $oldTid");
        
        $stmt = $db->prepare("UPDATE donations SET team_id = :tid, material_type = :m, quantity = :q, points_awarded = :p WHERE id = :id");
        $stmt->bindValue(':tid', $teamId);
        $stmt->bindValue(':m', $material);
        $stmt->bindValue(':q', $qty);
        $stmt->bindValue(':p', $points);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $db->exec("UPDATE teams SET total_points = total_points + $points WHERE id = $teamId");
        $db->exec("UPDATE teams SET total_points = 0 WHERE total_points < 0");
        
        return true;
    }
}
?>
