<?php

namespace App\Controllers;

use App\Models\User;
use Core\Connection;
use \Core\View;
use PDOException;

/**
 * User controller
 */
class UserController extends \Core\Controller
{
    /**
     * Show tasks
     *
     * @return void
     */
    public function indexAction()
    {
        if (isset($_GET['page'])) {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $limitPerPage = 8;

                $sql = "SELECT COUNT(*) FROM platform_user";
                foreach ($db->query($sql) as $row) {}
                $countOfPages = (int) ($row['count'] / $limitPerPage) + ((int) $row['count'] % $limitPerPage > 0 ? 1 : 0);

                $sql = "SELECT * FROM platform_user";
//                $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'id';
//                $sortingOrder = isset($_GET['sortingOrder']) ? $_GET['sortingOrder'] : 'ASC';
//                $sql .= ' ORDER BY ' . $sortBy . ' ' . $sortingOrder;
                $sql .= ' LIMIT ' . $limitPerPage;
                $sql .= isset($_GET['page']) ? " OFFSET " . (((int) $_GET['page'] - 1) * $limitPerPage) : " OFFSET 0";

                $pagination = [
                    'currentPage' => isset($_GET['page']) ? (int) $_GET['page'] : 1,
                    'countOfPages' => $countOfPages,
//                    'sortBy' => $sortBy,
//                    'sortingOrder' => $sortingOrder,
                    'result' => $db->query($sql)
                ];

                View::renderTemplate('User/index.html.twig', [
                    'pagination' => $pagination
                ]);
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        } else {
            $this->redirect('/user?page=1');
        }
    }

    /**
     * Create new instance of task
     */
    public function newAction() {
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
                    ':roles' => implode(';', $_POST['roles']),
                    ':first_name' => $_POST['firstName'],
                    ':last_name' => $_POST['lastName'],
                    ':brief_info' => $_POST['briefInfo'],
                    ':date_of_creation' => $currentDate->format('Y-m-d H:i:s'),
                    ':date_of_change' => $currentDate->format('Y-m-d H:i:s')]
                );

                $this->redirect('/user');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }

        try {
            View::renderTemplate('User/new.html.twig', [
                'roles' => $db->query("SELECT * FROM role")
            ]);
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    /**
     * Edit instance of task with passed identifier
     */
    public function editAction() {
        if ($_POST) {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $sql = "UPDATE platform_user SET
                    username = '" . $_POST['username'] .
                    "', email = '" . $_POST['email'] .
                    "', password = '" . password_hash($_POST['password'], PASSWORD_BCRYPT) .
                    "', roles = '" . implode(';', $_POST['roles']) .
                    "', first_name = '" . $_POST['firstName'] .
                    "', last_name = '" . $_POST['lastName'] .
                    "', brief_info = '" . $_POST['briefInfo'] .
                    "', date_of_change = '" . (new \DateTime('NOW'))->format('Y-m-d H:i:s') .
                    "' WHERE id = " . $this->route_params['id'];
                $db->exec($sql);
                $this->redirect('/user');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        } else {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $sql = "SELECT * FROM platform_user WHERE id = " . $this->route_params['id'];
                foreach ($db->query($sql) as $row) {}

                View::renderTemplate('User/edit.html.twig', [
                    'user' => $row,
                    'roles' => $_SESSION['app_user']->hasRole('ROLE_ADMIN') ?
                        $db->query("SELECT * FROM role") :
                        $db->query("SELECT * FROM role WHERE name != 'ROLE_ADMIN'")
                ]);
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }
    }

    /**
     * Delete instance of task with passed identifier
     */
    public function deleteAction() {
        try {
            $database = new Connection();
            $db = $database->openConnection();

            $sql = "DELETE FROM platform_user WHERE id = " . $this->route_params['id'];
            $db->exec($sql);
            $this->redirect('/user');
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
}
