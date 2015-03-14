

# Vista (MVC) #

## Introducción ##

Whenever you see Yii::app(), that refers to the Web application as a whole. You can access information about the user viewing a page, about the current or previously-viewed page, etc., there. Yii::app()->request specifically references the current page being accessed (or requested). The ->baseUrl part refers to the root URL for the application, like http://www.example.com. You should use Yii::app()->request->baseUrl for references to external files—CSS, JavaScript, images, and so forth—as the relative path to them can become muddled with the changed Yii URLs (like www.example.com/index.php/site/login).

```
  <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
```

Yii::app()->name is the name of the Web application, as established in the config/main.php file. You may or may not want to use it in your Views, but that’s where the value comes from.

Para renderizar la vista (mandarla a un view) se llama desde el Controlador a:

```
  $this->render('nombre_vista', array(
    'content'=>$contenido
  ));
```

Y luego en la propia vista se puede mostrar esa variable:

```
  <?php echo $var; ?>
```

Se puede hacer un renderizado parcial de una vista, sobretodo útil para cargar trozos de una web por AJAX:

```
  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
```



## Layouts ##
Layout is a special view that is used to decorate views. It usually contains parts of a user interface that are common among several views. For example, a layout may contain a header and a footer, and embed the view in between, like this:

```
  ......header here......
  <?php echo $content; ?>
  ......footer here......
```

where $content stores the rendering result of the view.

Layout is implicitly applied when calling render(). By default, the view script protected/views/layouts/main.php is used as the layout. This can be customized by changing either CWebApplication::layout or CController::layout. To render a view without applying any layout, call renderPartial() instead.