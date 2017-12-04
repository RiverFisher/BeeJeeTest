<?php

namespace App\Controllers;

use App\Models\User;
use Core\Connection;
use \Core\View;
use PDOException;

/**
 * User controller
 */
class SecurityController extends \Core\Controller
{
    public function loginAction()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $sql = "SELECT * FROM platform_user WHERE username = '" . $_POST['username'] . "'";
                foreach ($db->query($sql) as $row) {}

                if (password_verify($_POST['password'], $row['password'])) {
                    $_SESSION['authenticated'] = true;
                    $_SESSION['app_user'] = new User(
                        $row['id'],
                        $row['username'],
                        $row['email'],
                        $row['password'],
                        explode(';', $row['roles']),
                        $row['first_name'],
                        $row['last_name'],
                        $row['brief_info'],
                        new \DateTime($row['date_of_creation']),
                        new \DateTime($row['date_of_change'])
                    );

                    header('Location: user');
                } else {
                    header('Location: login');
                }
//                $this->redirect('/user');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }

        View::renderTemplate('Security/login.html.twig');
    }

    public function logoutAction()
    {
        $_SESSION['authenticated'] = false;
        $_SESSION['app_user'] = new User(
            null,
            null,
            null,
            null,
            ['ROLE_GUEST'],
            null,
            null,
            null,
            new \DateTime('NOW'),
            new \DateTime('NOW')
        );

        header("Location: login");
    }
}
