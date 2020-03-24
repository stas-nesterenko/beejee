<?php

namespace BJ\Controllers;

use BJ\AbstractController;
use BJ\Auth;
use BJ\Form\Form;
use BJ\Form\TextField;
use BJ\Models\Users;

class UsersController extends AbstractController
{
    public function login()
    {
        if (!empty($_POST)) {
            return $this->processAuthorization();
        }

        $this->setPageTitle('Авторизация');

        if (Auth::getInstance()->ifLogged()) {
            header('location: ' . SITE_URL, null, 301);
            die();
        }

        $form = new Form();
        $this->fillFormWithInputs($form);

        return $this->render('login',  ['validationRules' => $form->getValidationRules()]);
    }

    public function logout()
    {
        Auth::getInstance()->logMeOut();
        header('location: ' . SITE_URL, null, 301);
        die();
    }

    private function fillFormWithInputs(&$form)
    {
        $name = new TextField('login');
        $name->setMinLength(2)
            ->setMaxLength(40)
            ->setRegex('/^[\/\[\]\`\*\}\{\~\$\%\^\*\|\(\)\№\+\=\#\&\'\"\?\!\@\_\,\.\:\;\-\—\\\ a-zA-Z0-9а-яА-Я]*$/u');

        $form->addField($name);

        $name = new TextField('password');
        $name->setMinLength(2)
            ->setMaxLength(20)
            ->setRegex('/^[\/\[\]\`\*\}\{\~\$\%\^\*\|\(\)\№\+\=\#\&\'\"\?\!\@\_\,\.\:\;\-\—\\\ a-zA-Z0-9а-яА-Я]*$/u');

        $form->addField($name);
    }

    private function processAuthorization()
    {
        $form = new Form();
        $this->fillFormWithInputs($form);

        if ($form->valid()) {
            $Users = new Users();

            if ($user = $Users->db->where('login', '=', $_POST['login'])->get()) {
                if (password_verify($_POST['password'], $user[0]->password)) {
                    Auth::getInstance()->logMeIn($user[0]->id);
                    $form->setLocation(SITE_URL);
                } else {
                    $form->setError('password', 'введен неверный пароль');

                }
            } else {
                $form->setError('login', 'пользователь с таким Email не найден');
            }
        }

        header('Content-Type: application/json');
        return $form->getResponse();
    }
}
