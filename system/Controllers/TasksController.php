<?php

namespace BJ\Controllers;

use BJ\AbstractController;
use BJ\Auth;
use BJ\Form\Form;
use BJ\Form\TextAreaField;
use BJ\Form\TextField;
use BJ\Models\Task;
use Exception;

class TasksController extends AbstractController
{
    public function index()
    {
        if (!empty($_POST)) {
            return $this->getTasksList();
        }

        $this->setPageTitle('Список Задач');

        $form = new Form();
        $this->fillFormWithInputs($form);

        $columns = [
            [
                "data" =>  'name',
                'title' => 'Имя пользователя',
                'orderable' => true
            ],
            [
                'data' => 'email',
                'title' => 'Email',
                'orderable' => true
            ],
            [
                'data' => 'body',
                'title' => 'Текст задачи',
                'orderable' => false
            ],
            [
                'data' => 'active',
                'title' => 'Статус',
                'orderable' => true
            ],
        ];

        if (Auth::getInstance()->ifLogged()) {
            $columns[] = [
                'data' => 'edited',
                'title' => 'Отредактировано администратором',
                'orderable' => true
            ];
            $columns[] = [
                'data' => 'action',
                'title' => '',
                'orderable' => false
            ];
        }

        return $this->render('tasks',  [
            'validationRules' => $form->getValidationRules(),
            'columns' => $columns
        ]);
    }

    public function add()
    {
        $form = new Form();
        $this->fillFormWithInputs($form);

        if ($form->valid()) {
            $Task = new Task();

            $Task->fillFromArray([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'body' => htmlspecialchars($_POST['body'])
            ]);
            $Task->save();

            $form->setSuccessMessage('Спасибо, ваша задача была опубликована!');
        }

        header('Content-Type: application/json');
        return $form->getResponse();
    }

    public function edit()
    {
        $Task = new Task();

        if (!isset($_GET['id']) || !$task = $Task->db->find($_GET['id'])) {
            throw new Exception('Страница не найдена', 404);
        }

        if (!empty($_POST)) {
            return $this->editTask($task);
        }

        $this->setPageTitle('Редактирование задачи');

        $form = new Form();
        $this->fillFormWithInputs($form);


        return $this->render('tasks_edit',  ['validationRules' => $form->getValidationRules(), 'task' => $task]);
    }

    private function editTask($task)
    {
        $form = new Form();
        $this->fillFormWithInputs($form);

        if ($form->valid()) {
            $Task = new Task();

            $Task->fillFromArray([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'body' => htmlspecialchars($_POST['body']),
                'edited' => $task->body != htmlspecialchars($_POST['body']) ? 1 : $task->edited,
                'active' => $_POST['active'] == 1 ? 1 : 0
            ]);
            $Task->update($task->id);

            $form->setLocation(SITE_URL);
        }

        header('Content-Type: application/json');
        return $form->getResponse();
    }

    private function fillFormWithInputs(&$form)
    {
        $name = new TextField('name');
        $name->setMinLength(2)
            ->setMaxLength(40)
            ->setRegex('/^[ a-zA-Z0-9а-яА-Я]*$/u');

        $form->addField($name);

        $name = new TextField('email');
        $name->setMinLength(6)
            ->setMaxLength(40)
            ->setRegex('/^$|^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD');

        $form->addField($name);

        $name = new TextAreaField('body');
        $name->setMinLength(10);

        $form->addField($name);
    }

    private function getTasksList()
    {
        $Task = new Task();

        if (isset($_POST['order'][0]['column'])) {
            switch ($_POST['order'][0]['column']) {
                case 0:
                    $Task->db->orderBy('name', $_POST['order'][0]['dir']);
                    break;
                case 1:
                    $Task->db->orderBy('email', $_POST['order'][0]['dir']);
                    break;
                case 3:
                    $Task->db->orderBy('active', $_POST['order'][0]['dir']);
                    break;
                case 4:
                    $Task->db->orderBy('edited', $_POST['order'][0]['dir']);
                    break;
            }
        }

        if (isset($_POST['start']) && isset($_POST['length'])) {
            $Task->db->limit($_POST['length'])->offset($_POST['start']);
        }

        $tasks =  $Task->db->get();
        if (!empty($tasks)) {
            foreach ($tasks as &$task) {
                $task->active = $task->active ? 'выполнено' : 'не выполнено';
                $task->edited = $task->edited ? 'да' : 'нет';

                if (Auth::getInstance()->ifLogged()) {
                    $task->action = "<div class=\"btn-group\"><a href='/tasks/edit?id={$task->id}' class=\"btn btn-info\"><i class=\"fa fa-edit\"></i></a></div>";
                }
            }
            unset($task);
        }

        header('Content-Type: application/json');
        return json_encode([
            'recordsTotal' => $Task->db->count(),
            'recordsFiltered' => $Task->db->count(),
            'data' => $tasks
        ]);
    }
}
