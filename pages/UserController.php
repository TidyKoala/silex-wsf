<?php

use Blog\Controller;

Class UserController extends Controller
{
	/**
	 * [getLogin description]
	 * @return [type] [description]
	 */
	public function getLogin () 
	{
		$data = array();

		return $this->app['twig']->render('user/login.twig', $this->data);

	}

	/**
	 * [postLogin description]
	 * @return [type] [description]
	 */
	public function postLogin() 
	{

		$email = $this->app['request']->get('email');
		$password = $this->app['request']->get('password');

		$sql = "SELECT * FROM users WHERE email = :email";
		$arguments = array(
			':email' => $email
		);

		$statement = $this->app['sql']->prepareExec($sql, $arguments);
		$user = $statement->fetch();

		//Test if user exist
		if ($user === false) {
			$this->data['errors'] = 'Login or password incorrect';
		}

		//test if password is correct
		if (sha1($password.$user['salt']) !== $user['password'])
		{
			$this->data['errors'] = 'Login or password incorrect';
		}

		if ($this->data['errors'])
			return $this->getLogin();

		$user = array(
			'id' => $user['id'],
			'name' => $user['name'],
			'email' => $user['email'],
			'status' => $user['status']
		);

		$this->app['session']->set('user', $user);

		return $this->app->redirect(
			$this->app['url_generator']->generate('home')
		);
	}

	/**
	 * [getRegister description]
	 * @return [type] [description]
	 */
	public function getRegister () 
	{
		$data = array();

		return $this->app['twig']->render('user/register.twig', $data);

	}

	/**
	 * [postRegister description]
	 * @return [type] [description]
	 */
	public function postRegister () 
	{
		$email = $this->app['request']->get('email');
		$password = $this->app['request']->get('password');
		$password_confirmation = $this->app['request']->get('password_confirmation');
		$name = $this->app['request']->get('name');

		//Verification du password
		if ($password !== $password_confirmation) {
			$data['errors'][] = 'Password doesn\'t match';
		}

		//Verification de l'email
		$sql = "SELECT email FROM users WHERE email = :email";
		$arguments = array(
			':email' => $email
		);
		$emailVerif = $this->app['sql']->prepareExec($sql, $arguments);

		if ($emailVerif->fetch() !== false) {
			$this->data['errors'][] = 'This email already exist';
		}

		//Si erreurs reafficher le formulaire
		if (!empty($this->data['errors'])) {
			return $this->getRegister();
		}

		//insertion das la base de donnees
		$salt = uniqid();
		$password = sha1($password.$salt);

		$sql = "INSERT INTO users (
					email ,
					password ,
					name ,
					salt
				)
				VALUES (
					:email, :password, :name, :salt
				)";

		$arguments = array(
				':email' => $email,
				':password' => $password,
				':name' => $name,
				':salt' => $salt
		);


		$this->app['sql']->prepareExec($sql, $arguments);

		return $this->app['twig']->render('user/register-success.twig', $this->data);
	}


}