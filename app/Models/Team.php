<?php
class Team {
    public static function getAll() {
        $db = Database::getInstance();
        $res = $db->query("SELECT * FROM teams ORDER BY total_points DESC");
        $teams = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) { $teams[] = $row; }
        return $teams;
    }

    public static function updatePoints($teamId, $points) {
        $db = Database::getInstance();
        $db->exec("UPDATE teams SET total_points = MAX(0, total_points + ($points)) WHERE id = $teamId");
    }

    public static function resetPoints($id) {
        $db = Database::getInstance();
        $db->exec("DELETE FROM donations WHERE team_id = $id");
        return $db->exec("UPDATE teams SET total_points = 0 WHERE id = $id");
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $db->exec("DELETE FROM donations WHERE team_id = $id");
        return $db->exec("DELETE FROM teams WHERE id = $id");
    }
}
