

# Themes #

To activate a theme, set the theme property of the Web application to be the name of the desired theme. This can be done either in the application configuration or during runtime in controller actions.

For example, in the configuration file:

```
  return array(
    'theme'=>'basic',
    ......
  );
```


## Crear un theme ##

Contents under a theme directory should be organized in the same way as those under the application base path. For example, all view files must be located under views, layout view files under views/layouts, and system view files under views/system. For example, if we want to replace the create view of PostController with a view in the classic theme, we should save the new view file as WebRoot/themes/classic/views/post/create.php.

Inside a theme view, we often need to link other theme resource files. For example, we may want to show an image file under the theme's images directory. Using the baseUrl property of the currently active theme, we can generate the URL for the image as follows:

```
  Yii::app()->theme->baseUrl . '/images/FileName.gif'
```


También es posible aplicar themes a widgets: http://www.yiiframework.com/doc/guide/1.1/en/topics.theming