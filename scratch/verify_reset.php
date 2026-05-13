<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../app/Models/Team.php';
require_once __DIR__ . '/../app/Models/Donation.php';

$db = Database::getInstance();

// 1. Create a dummy team
$db->exec("INSERT INTO teams (name, country, total_points) VALUES ('Test Reset', 'BR', 500)");
$teamId = $db->lastInsertRowID();
echo "Created team $teamId with 500 points\n";

// 2. Create some donations
$db->exec("INSERT INTO donations (team_id, material_type, quantity, points_awarded) VALUES ($teamId, 'leite', 10, 100)");
$db->exec("INSERT INTO donations (team_id, material_type, quantity, points_awarded) VALUES ($teamId, 'leite', 10, 100)");
echo "Created 2 donations for team $teamId\n";

// 3. Reset points
echo "Resetting points for team $teamId...\n";
Team::resetPoints($teamId);

// 4. Check results
$points = $db->querySingle("SELECT total_points FROM teams WHERE id = $teamId");
$donationsCount = $db->querySingle("SELECT COUNT(*) FROM donations WHERE team_id = $teamId");

echo "Team points after reset: $points (Expected: 0)\n";
echo "Donations count after reset: $donationsCount (Expected: 0)\n";

// 5. Cleanup
$db->exec("DELETE FROM teams WHERE id = $teamId");

if ($points == 0 && $donationsCount == 0) {
    echo "VERIFICATION SUCCESSFUL\n";
} else {
    echo "VERIFICATION FAILED\n";
}
