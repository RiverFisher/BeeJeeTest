<?php

namespace App\Controllers;

use App\Models\Task;
use Core\Connection;
use \Core\View;
use PDOException;

/**
 * Task controller
 */
class TaskController extends \Core\Controller
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

                $sql = "SELECT COUNT(*) FROM task";
                foreach ($db->query($sql) as $row) {}
                $countOfPages = (int) ($row['count'] / $limitPerPage) + ((int) $row['count'] % $limitPerPage > 0 ? 1 : 0);

                $sql = "SELECT * FROM task";
                $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'id';
                $sortingOrder = isset($_GET['sortingOrder']) ? $_GET['sortingOrder'] : 'ASC';
                $sql .= ' ORDER BY ' . $sortBy . ' ' . $sortingOrder;
                $sql .= ' LIMIT ' . $limitPerPage;
                $sql .= isset($_GET['page']) ? " OFFSET " . (((int) $_GET['page'] - 1) * $limitPerPage) : " OFFSET 0";

                $pagination = [
                    'currentPage' => isset($_GET['page']) ? (int) $_GET['page'] : 1,
                    'countOfPages' => $countOfPages,
                    'sortBy' => $sortBy,
                    'sortingOrder' => $sortingOrder,
                    'result' => $db->query($sql)
                ];

                View::renderTemplate('Task/index.html.twig', [
                    'pagination' => $pagination
                ]);
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        } else {
            $this->redirect('/task?page=1&sortBy=id&sortingOrder=ASC');
        }
    }

    /**
     * Create new instance of task
     */
    public function newAction() {
        if (isset($_POST['title']) && isset($_POST['description'])) {
            try {
                $currentDate = new \DateTime('NOW');

                $database = new Connection();
                $db = $database->openConnection();

                $sql = "SELECT MAX(id) FROM task";
                foreach ($db->query($sql) as $row) {}

                $stm = $db->prepare(
                    "INSERT INTO task (
                        id,
                        title,
                        description,
                        author_id,
                        author_username,
                        author_email,
                        status,
                        date_of_creation,
                        date_of_change
                    ) VALUES (
                        :id,
                        :title,
                        :description,
                        :author_id,
                        :author_username,
                        :author_email,
                        :status,
                        :date_of_creation,
                        :date_of_change
                    )"
                );
                $stm->execute([
                    ':id' => (int) $row['max'] + 1,
                    ':title' => $_POST['title'],
                    ':description' => $_POST['description'],
                    ':author_id' => $_SESSION['app_user']->getId(),
                    ':author_username' => $_POST['username'],
                    ':author_email' => $_POST['email'],
                    ':status' => Task::NEW_TASK,
                    ':date_of_creation' => $currentDate->format('Y-m-d H:i:s'),
                    ':date_of_change' => $currentDate->format('Y-m-d H:i:s')]
                );

                $this->redirect('/task');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }
        try {
            $database = new Connection();
            $db = $database->openConnection();

            foreach ($db->query("SELECT MAX(id) FROM task") as $row) {}

            View::renderTemplate('Task/new.html.twig', [
                'lastTaskId' => $row['max']
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

                $sql = "UPDATE task SET title = '" . $_POST['title'] .
                    "', description = '" . $_POST['description'] .
                    "', author_username = '" . $_POST['username'] .
                    "', author_email = '" . $_POST['email'] .
                    "', date_of_change = '" . (new \DateTime('NOW'))->format('Y-m-d H:i:s') .
                    "' WHERE id = " . $this->route_params['id'];
                $db->exec($sql);
                $this->redirect('/task');
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        } else {
            try {
                $database = new Connection();
                $db = $database->openConnection();

                $sql = "SELECT * FROM task WHERE id = " . $this->route_params['id'];
                foreach ($db->query($sql) as $row) {}

                View::renderTemplate('Task/edit.html.twig', [
                    'task' => $row
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

            $sql = "DELETE FROM task WHERE id = " . $this->route_params['id'];
            $db->exec($sql);
            $this->redirect('/task');
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    /**
     * Complete task with passed identifier
     */
    public function completeAction() {
        try {
            $database = new Connection();
            $db = $database->openConnection();

            $sql = "UPDATE task SET status = 'Completed', date_of_change = '" .
                (new \DateTime('NOW'))->format('Y-m-d H:i:s') .
                "' WHERE id = " . $this->route_params['id'];
            $db->exec($sql);
            $this->redirect('/task');
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
}
