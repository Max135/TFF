<?php namespace Controllers;

use Models\Brokers\UserBroker;
use Zephyrus\Application\Flash;
use Zephyrus\Application\Rule;
use Zephyrus\Application\Session;

class LoginController extends \Zephyrus\Application\Controller
{

    /**
     * @inheritDoc
     */
    public function initializeRoutes()
    {
        $this->get("/", "login");
        $this->get("/index", "index");
        $this->get("/login", "login");
        $this->post("/login", "loginUser");
        $this->get("/signup", "signup");
    }

    public function index() {
        return $this->render("index", [
           'title'=>'Index'
        ]);
    }

    public function login() {
        return $this->render("login", [
            'title' => 'Login'
        ]);
    }

    public function loginUser() {
        $form = $this->buildForm();
        $form->validate('password', Rule::notEmpty("password empty"));

        $form->validate('email', Rule::notEmpty("email empty"));
        $form->validateWhenFieldHasNoError('email', Rule::email("email no good"));

        if(!$form->verify()) {
            Flash::error($form->getErrorMessages());
            return $this->redirect('/');
        }

        $userBroker = new UserBroker();
        $email = $form->getValue('email');
        $password = $form->getValue('password');
        if($userBroker->validCredentials($email, $password)) {
            Session::getInstance()->set('id', $userBroker->findId($email));
            return $this->redirect('/map');
        } else {
            Flash::error('invalid credentials');
            return $this->redirect('/');
        }
    }

    public function signup() {
        return $this->render("signup", [
            'title' => 'Signup'
        ]);
    }
}
