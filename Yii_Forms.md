

# Formularios #

The following steps are typically needed when dealing with forms in Yii:

  1. Create a model class representing the data fields to be collected;
  1. Create a controller action with code that responds to form submission.
  1. Create a form in the view script file associated with the controller action.

## Creando el modelo ##

Below we create a LoginForm model class used to collect user input on a login page. Because the login information is only used to authenticate the user and does not need to be saved, we create LoginForm as a form model.

```
  class LoginForm extends CFormModel
  {
    public $username;
    public $password;
    public $rememberMe=false;
  }
```

Three attributes are declared in LoginForm: $username, $password and $rememberMe. They are used to keep the user-entered username and password, and the option whether the user wants to remember his login. Because $rememberMe has a default value false, the corresponding option when initially displayed in the login form will be unchecked.

### Reglas de validación de los datos del formulario ###

We specify the validation rules in the rules() method which should return an array of rule configurations.

```
  class LoginForm extends CFormModel  {
    public $username;
    public $password;
    public $rememberMe=false;
 
    private $_identity;
 
    public function rules()
    {
        return array(
            array('username, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }
 
    public function authenticate($attribute,$params)
    {
        $this->_identity=new UserIdentity($this->username,$this->password);
        if(!$this->_identity->authenticate())
            $this->addError('password','Incorrect username or password.');
    }
  }
```

The above code specifies that username and password are both required, password should be authenticated, and rememberMe should be a boolean.

List of scenarios (on and except parameters) could be specified in two different forms which means the same:

```
  // arbitary array with scenario names
  'on'=>array('update', 'create'),
  // string with scenario names separated with commas (spaces are ignored)
  'except'=>'ignore, this, scenarios, at-all',
```

There are three ways to specify Validator in a validation rule. First, Validator can be the name of a method in the model class, like authenticate in the above example. The validator method must be of the following signature:

```
  /**
   * @param string $attribute the name of the attribute to be validated
   * @param array $params options specified in the validation rule
   */
  public function ValidatorName($attribute,$params) { ... }
```

[Other way: Validator can be a predefined alias to a validator class.](Yii_Modelo#Las_reglas:_rules().md)

### Campos seguros y "repopular formularios" ###

After a model instance is created, we often need to populate its attributes with the data submitted by end-users. This can be done conveniently using the following massive assignment:

```
  $model=new LoginForm;
  if(isset($_POST['LoginForm']))
    $model->attributes=$_POST['LoginForm'];
```

The last statement is called massive assignment which assigns every entry in $_POST['LoginForm'] to the corresponding model attribute. It is equivalent to the following assignments:_

```
  foreach($_POST['LoginForm'] as $name=>$value) {
    if($name is a safe attribute)
        $model->$name=$value;
  }
```

It is crucial to determine which attributes are safe. An attribute is considered safe if it appears in a validation rule that is applicable in the given scenario.

To declare an attribute to be safe, even though we do not really have any specific rule for it:

```
  array('content', 'safe')
```

### Validando los datos ###
```
  // creates a User model in register scenario. It is equivalent to:
  // $model=new User;
  // $model->scenario='register';
  $model=new User('register');
 
  // populates the input values into the model
  $model->attributes=$_POST['User'];
 
  // performs the validation
  if($model->validate())   // if the inputs are valid
    ...
  else
    ...
```

The applicable scenarios that a rule is associated can be specified via the on option in the rule. If the on option is not set, it means the rule will be used for all scenarios. For example,

```
  public function rules() {
    return array(
        array('username, password', 'required'),
        array('password_repeat', 'required', 'on'=>'register'),
        array('password', 'compare', 'on'=>'register'),
    );
  }
```

The first rule will be applied in all scenarios, while the next two rules will only be applied in the register scenario.

Los errores se pueden obtener con CModel::getErrors();


## Creando la acción asociada al formulario ##

We place this logic inside a controller action.

```
  public function actionLogin() {
    $model=new LoginForm;
    if(isset($_POST['LoginForm']))
    {
        // collects user input data
        $model->attributes=$_POST['LoginForm'];
        // validates user input and redirect to previous page if validated
        if($model->validate())
            $this->redirect(Yii::app()->user->returnUrl);
    }
    // displays the login form
    $this->render('login',array('model'=>$model));
  }
```

In the above, we first create a LoginForm model instance; if the request is a POST request (meaning the login form is submitted), we populate $model with the submitted data $_POST['LoginForm']; we then validate the input and if successful, redirect the user browser to the page that previously needed authentication. If the validation fails, or if the action is initially accessed, we render the login view whose content is to be described in the next subsection._

` $model->attributes=$_POST['LoginForm']; `

The above code is equivalent to:

```
  $model->username=$_POST['LoginForm']['username'];
  $model->password=$_POST['LoginForm']['password'];
  $model->rememberMe=$_POST['LoginForm']['rememberMe'];
```


## Creando la vista asociada al formulario ##

We can use CHtml to create the login form. But a widget called CActiveForm is provided to facilitate form creation. The widget is capable of supporting seamless and consistent validation on both client and server sides.

```
  <div class="form">
  <?php $form=$this->beginWidget('CActiveForm'); ?>
 
    <?php echo $form->errorSummary($model); ?>
 
    <div class="row">
        <?php echo $form->label($model,'username'); ?>
        <?php echo $form->textField($model,'username') ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'password'); ?>
        <?php echo $form->passwordField($model,'password') ?>
    </div>
 
    <div class="row rememberMe">
        <?php echo $form->checkBox($model,'rememberMe'); ?>
        <?php echo $form->label($model,'rememberMe'); ?>
    </div>
 
    <div class="row submit">
        <?php echo CHtml::submitButton('Login'); ?>
    </div>
 
  <?php $this->endWidget(); ?>
  </div><!-- form -->
```

TIP: The following code would generate a text input field which can trigger form submission if its value is changed by users.

` CHtml::textField($name,$value,array('submit'=>'')); `