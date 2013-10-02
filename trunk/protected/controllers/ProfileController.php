<?php

class ProfileController extends Controller
{	
	// Uncomment the following methods and override them if needed
	
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl',
		);
	}
        
    public function accessRules()
    {
        return array(	  
            array('allow',
                'actions'=>array('index'),
                'roles'=>array('Usuario'),  

            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

        
    public function actionIndex()
	{
        $data = array();
        
       //Saco los datos del usuario de BBDD
       $user= Yii::app()->currentUser->model;
       $model = new ProfileForm();       

        //Si viene del formulario....
        if(isset($_POST['ProfileForm']))
        {
            // collects user input data
            $model->attributes=$_POST['ProfileForm'];

            // validates user input and redirect to previous page if validated
            if($model->validate())
            {
                //Actualizo
                $user->alias = $model->alias;
                $user->email = $model->email;
                $user->password = crypt($model->password, self::blowfishSalt());
                Yii::app()->user->setFlash('normal', 'Has actualizado tu perfil correctamente');

                if (!$user->save())
                    throw new CHttpException(400, 'Error al actualizar el perfil de usuario.');
            }
        }					
        //Si el usuario simplemente accede a la página...
        else 
        {
            //Toy entrando simplemente
            $model->alias = $user->alias;
            $model->email = $user->email;
        }

        $data['model'] = $model;

        // displays the login form
        $this->render('index', $data);
	}
   
   
      /**
     * Generate a random salt in the crypt(3) standard Blowfish format.
     *
     * @param int $cost Cost parameter from 4 to 31.
     *
     * @throws Exception on invalid cost parameter.
     * @return string A Blowfish hash salt for use in PHP's crypt()
     */
    private static function blowfishSalt($cost = 13)
    {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
        throw new Exception("cost parameter must be between 4 and 31");
        }

        $rand = array();

        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }

        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . sprintf('%02d', $cost) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));

        return $salt;
    }
}
