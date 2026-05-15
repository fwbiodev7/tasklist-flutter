<?php
class Team {
    public static function getAll() {
        $db   = Database::getInstance();
        $stmt = $db->query("SELECT * FROM teams ORDER BY total_points DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updatePoints($teamId, $points) {
        $db   = Database::getInstance();
        $stmt = $db->prepare("
            UPDATE teams
            SET total_points = GREATEST(0, total_points + :pts)
            WHERE id = :id
        ");
        $stmt->bindValue(':pts', $points, PDO::PARAM_INT);
        $stmt->bindValue(':id',  $teamId,  PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function resetPoints($id) {
        $db = Database::getInstance();

        $d = $db->prepare("DELETE FROM donations WHERE team_id = :id");
        $d->bindValue(':id', $id, PDO::PARAM_INT);
        $d->execute();

        $u = $db->prepare("UPDATE teams SET total_points = 0 WHERE id = :id");
        $u->bindValue(':id', $id, PDO::PARAM_INT);
        return $u->execute();
    }

    public static function delete($id) {
        $db = Database::getInstance();

        $d = $db->prepare("DELETE FROM donations WHERE team_id = :id");
        $d->bindValue(':id', $id, PDO::PARAM_INT);
        $d->execute();

        $t = $db->prepare("DELETE FROM teams WHERE id = :id");
        $t->bindValue(':id', $id, PDO::PARAM_INT);
        return $t->execute();
    }
}
