

# Controlador (MVC) #

The first thing you’ll encounter within a Controller class is a variable called **$layout**:

```
  public $layout='//layouts/column2';
```

As explained in the post on Views, this variable dictates which of the two built-in layouts—one column or two column—the Controller uses. You can change this value to change the layout for the entire Controller, or you can change **$this->layout** within any of the methods.

```
  public $defaultAction='admin';
```

As just stated, Controllers are the actions one takes, listing information, showing particular records, handling form submissions, and so forth. For each action there is a corresponding method in the Controller class: **actionIndex()**, **actionView()**, **actionCreate()**, etc. The above line dictates which method is called if not otherwise specified. So with that line, the URL www.example.com/index.php/employee calls the **actionAdmin()** method whereaswww.example.com/index.php/employee/create calls **actionCreate()**. The default value, if you don’t use that line, is to call **actionIndex()**.

Your Controllers should also have several non-action methods, including **accessRules()**. This method is a key part of the security picture, dictating who can do what. For the “what” options, you have your actions: list, show, create, update, and delete. Your “who” depends upon the situation, but to start there’s at least logged-in and not logged-in users, represented by `*` (anyone) and @ (logged-in users), accordingly. Depending upon the login system in place, you may also have levels of users, like admins. So the accessRules() method uses all this information and returns an array of values. The values are also arrays, indicating permissions (allow or deny), actions, and users:

```
  public function accessRules(){
    return array(
        array('allow',  // allow all users to perform 'index' and 'view' actions
            'actions'=>array('index','view'),
            'users'=>array('*'),
        ),
     array('allow', // allow authenticated user to perform 'create' and 'update' actions
            'actions'=>array('create','update'),
            'users'=>array('@'),
        ),
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions'=>array('admin','delete'),
            'users'=>array('admin'),
        ),
        array('deny',  // deny all users
            'users'=>array('*'),
        ),
    );
  }
```

Puedes incluir expresiones (que devuelvan true o false):
```
  array('allow',
    'actions'=>array('publish'),
    'users'=>array('@'),
    'expression'=>'isset($user->role) && ($user->role==="editor")'
  ),
```

Two things about this expression. First, you still need to use the users element, and you’ll probably want to still restrict this to logged-in users (most likely). Second, the expression itself should be some PHP code, quoted, that when evaluated gives a Boolean result. If the code in the expression will be true, then permission will be allowed; false, denied.

Un ejemplo completo de autenticación con roles: http://www.larryullman.com/2010/01/07/custom-authentication-using-the-yii-framework/