<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../app/Models/Team.php';
require_once __DIR__ . '/../app/Models/Donation.php';

$db = Database::getInstance();

// 1. Create a dummy team
$db->exec("INSERT INTO teams (name, country, total_points) VALUES ('Test Team', 'Test Country', 100)");
$teamId = $db->lastInsertRowID();
echo "Created team $teamId with 100 points\n";

// 2. Create a donation
$db->exec("INSERT INTO donations (team_id, material_type, quantity, points_awarded) VALUES ($teamId, 'reciclável', 10, 50)");
$donationId = $db->lastInsertRowID();
echo "Created donation $donationId with 50 points\n";

// 3. Delete the donation
echo "Deleting donation $donationId...\n";
$res = Donation::delete($donationId);
echo "Donation::delete result: " . ($res ? 'SUCCESS' : 'FAILURE') . "\n";

// 4. Check team points
$res = $db->querySingle("SELECT total_points FROM teams WHERE id = $teamId");
echo "Team points after deletion: $res (Expected: 50)\n";

// 5. Cleanup
$db->exec("DELETE FROM teams WHERE id = $teamId");
$db->exec("DELETE FROM donations WHERE id = $donationId");
