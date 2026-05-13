<?php
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../app/Models/Team.php';
require_once __DIR__ . '/../app/Models/Donation.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = trim($requestUri, '/');

$adminController = new AdminController();

file_put_contents('debug.txt', "FINAL URL: [" . $url . "]\n", FILE_APPEND);

switch ($url) {
    case 'home':
    case '':
        $ranking = Team::getAll();
        require_once __DIR__ . '/../app/Views/home.php';
        break;
    
    case 'api/ranking':
        $ranking = Team::getAll();
        header('Content-Type: application/json');
        echo json_encode($ranking);
        break;

    case 'login':
        require_once __DIR__ . '/../app/Views/admin/login.php';
        break;

    case 'admin/auth':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM admins WHERE username = :u");
            $stmt->bindValue(':u', $user);
            $res = $stmt->execute();
            $row = $res->fetchArray(SQLITE3_ASSOC);
            if ($row && password_verify($pass, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                header("Location: /admin/dashboard");
            } else {
                header("Location: /login?error=1");
            }
        }
        break;

    case 'admin/dashboard':
        $adminController->dashboard();
        break;

    case 'admin/add-donation':
        $adminController->addDonation();
        break;

    case 'admin/save-donation':
        $adminController->saveDonation();
        break;

    case 'admin/delete-donation':
        $adminController->deleteDonation();
        break;

    case 'admin/delete-team':
        $adminController->deleteTeam();
        break;

    case 'logout':
        session_destroy();
        header("Location: /");
        break;

    default:
        $ranking = Team::getAll();
        require_once __DIR__ . '/../app/Views/home.php';
        break;
}
?>
