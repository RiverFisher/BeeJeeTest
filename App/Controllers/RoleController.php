<?php

namespace App\Controllers;

use App\Models\User;
use Core\Connection;
use \Core\View;
use PDOException;

/**
 * Role controller
 */
class RoleController extends \Core\Controller
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

                $sql = "SELECT COUNT(*) FROM role";
                foreach ($db->query($sql) as $row) {}
                $countOfPages = (int) ($row['count'] / $limitPerPage) + ((int) $row['count'] % $limitPerPage > 0 ? 1 : 0);

                $sql = "SELECT * FROM role";
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

                View::renderTemplate('Role/index.html.twig', [
                    'pagination' => $pagination
                ]);
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        } else {
            $this->redirect('/role?page=1');
        }
    }

    /**
     * Create new instance of task
     */
    public function newAction() {
        if (isset($_POST['name'])) {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $sql = "SELECT MAX(id) FROM role";
                foreach ($db->query($sql) as $row) {}

                $stm = $db->prepare("INSERT INTO role (id, name) VALUES (:id, :name)");
                $stm->execute([':id' => (int) $row['max'] + 1, ':name' => $_POST['name']]);

                $this->redirect('/role');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }

        View::renderTemplate('Role/new.html.twig');
    }

    /**
     * Edit instance of task with passed identifier
     */
    public function editAction() {
        if ($_POST) {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $sql = "UPDATE role SET name = '" . $_POST['name'] . "' WHERE id = " . $this->route_params['id'];
                $db->exec($sql);
                $this->redirect('/role');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        } else {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $sql = "SELECT * FROM role WHERE id = " . $this->route_params['id'];
                foreach ($db->query($sql) as $row) {}

                View::renderTemplate('Role/edit.html.twig', [
                    'role' => $row
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

            $sql = "DELETE FROM role WHERE id = " . $this->route_params['id'];
            $db->exec($sql);
            $this->redirect('/role');
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
}
