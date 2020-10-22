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
        $this->get("/", "renderIndex");
        $this->get("/index", "renderIndex");

        $this->get("/login", "renderLogin");
        $this->post("/login", "login");

        $this->get("/signup", "renderSignup");
        $this->post('/signup', 'signup');

    }

    public function renderIndex() {
        return $this->render("index", [
           'title'=>'Index'
        ]);
    }

    public function renderLogin() {
        return $this->render("login", [
            'title' => 'Login'
        ]);
    }

    public function login() {
        $form = $this->buildForm();
        $form->validate('password', Rule::notEmpty("Password empty"));

        $form->validate('email', Rule::notEmpty("Email empty"));
        $form->validateWhenFieldHasNoError('email', Rule::email("Email no good"));

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
            return $this->redirect('/login');
        }
    }

    public function renderSignup()
    {
        return $this->render("signup", [
            'title' => 'Signup'
        ]);
    }

    public function signup()
    {
        $form = $this->buildForm();

        $this->validateSignupForm($form);

        if(!$form->verify()) {
            Flash::error($form->getErrorMessages());
            return $this->redirect('/signup');
        }

        $this->signupUser($form);

        return $this->redirect('/login');
    }

    private function signupUser($form): int
    {
        $email = $form->getValue('email');
        $username = $form->getValue('username');
        $password = $form->getValue('password');

        return (new UserBroker())->insert($email, $password, $username);
    }

    private function validateSignupForm($form)
    {
        $form->validate('username', Rule::notEmpty('username empty'));
        $form->validateWhenFieldHasNoError('username', Rule::alpha('Wtf username', false));

        $form->validate('password', Rule::notEmpty('empty password dumdum'));
        $form->validateWhenFieldHasNoError('password', Rule::passwordCompliant('Bad password'));

        if ($form->getValue('password') != $form->getValue('passwordConfirmation')) {
            $form->addError('passwordConfirmation', 'passwords do not match');
        }
    }
}
