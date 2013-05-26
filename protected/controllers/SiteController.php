<?php

class SiteController extends Controller
{

    /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
			    'actions' => array('selectplayer','newplayer'),
				'users'=>array('@'),
			),
		    array('allow',
		        'actions' => array('index', 'error', 'contact', 'signup', 'logout'),
		        'users'=>array('*'),
		    ),
			array('deny'),
		);
	}


	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

	    if (!Yii::app()->user->isGuest) {
	        $this->redirect(array('site/selectPlayer'));
	    }

		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		$this->render('index', array(
			'model' => $model,
		));
	}

	/**
	 * Select a player to enter game
	 */
	public function actionSelectPlayer($id=null){

	    if ($id) {
	        if ($id == 'current') {
	            $player = Yii::app()->actx->player;
	        } else {
	            $player = Player::model()->findByPk($id);
	            if ($player) {
	                Yii::app()->actx->switchPlayer($player);
	            }
	        }
	        if ($player) {
	            if (!Planet::model()->exists('owner_id=:owner_id', array('owner_id' => $player->id))) {
	                PlanetHelper::createMotherPlanet($player);
	            }
	            $this->redirect(array('/game'));
	        }
	    }

	    $model = new Player('search');
	    $model->account_id = Yii::app()->user->id;

	    $this->render('select_player',array(
	        'dataProvider'=>$model->with('planetCount')->search(),
	        'currentPlayer' => Yii::app()->actx->player,
	    ));
	}

	public function actionNewPlayer(){

	    $model=new Player;

	    if(isset($_POST['ajax']) && $_POST['ajax']==='player-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

	    if(isset($_POST['Player']))
	    {
	        $model->attributes=$_POST['Player'];
	        $model->account_id = Yii::app()->user->id;
	        if($model->save()){
	            $this->redirect(array('site/selectplayer','id'=>$model->id));
	        }
	    }

	    $this->render('new_player',array(
	        'model'=>$model,
	    ));


	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}


	public function actionSignup(){

		$model = new SignupForm();

		// Uncomment the following line if AJAX validation is needed
		if(isset($_POST['ajax']) && $_POST['ajax']==='signup-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['SignupForm']))
		{
			$model->attributes=$_POST['SignupForm'];
			if($model->validate()){
				$account = Account::createNew($model->email, $model->login_name, $model->password);
				Yii::app()->user->login($account);
				$this->redirect(array('/site/index'));
			}
		}

		$this->render('signup',array(
				'model'=>$model,
		));


	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}