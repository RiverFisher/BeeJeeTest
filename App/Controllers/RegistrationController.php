<?php

namespace App\Controllers;

use App\Models\User;
use Core\Connection;
use \Core\View;
use PDOException;

/**
 * Class RegistrationController
 *
 * @package App\Controllers
 */
class RegistrationController extends \Core\Controller
{
    /**
     * Register new user account
     */
    public function registerAction() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            try {
                $currentDate = new \DateTime('NOW');

                $database = new Connection();
                $db = $database->openConnection();

                $sql = "SELECT MAX(id) FROM platform_user";
                foreach ($db->query($sql) as $row) {}

                $stm = $db->prepare(
                    "INSERT INTO platform_user (
                        id,
                        username,
                        email,
                        password,
                        roles,
                        first_name,
                        last_name,
                        brief_info,
                        date_of_creation,
                        date_of_change
                    ) VALUES (
                        :id,
                        :username,
                        :email,
                        :password,
                        :roles,
                        :first_name,
                        :last_name,
                        :brief_info,
                        :date_of_creation,
                        :date_of_change
                    )"
                );
                $stm->execute([
                    ':id' => (int) $row['max'] + 1,
                    ':username' => $_POST['username'],
                    ':email' => $_POST['email'],
                    ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                    ':roles' => implode(';', ['ROLE_USER']),
                    ':first_name' => $_POST['firstName'],
                    ':last_name' => $_POST['lastName'],
                    ':brief_info' => $_POST['briefInfo'],
                    ':date_of_creation' => $currentDate->format('Y-m-d H:i:s'),
                    ':date_of_change' => $currentDate->format('Y-m-d H:i:s')]
                );

                $this->redirect('/login');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }

        View::renderTemplate('Registration/register.html.twig');
    }
}
