# Cambios Yii v1.1.13 #

## framework\web\js\source\jquery.yiiactiveform.js ##

```
Línea 23

 Original:
  `if (o[0].tagName.toLowerCase() === 'span') {`

 Modificado:
  `if (o[0].tagName.toLowerCase() === 'span' || o[0].tagName.toLowerCase() === 'ul') {`
```


## framework\web\helpers\CJavaScript.php ##

```
Línea 90

 Original:
  `if(($n=count($value))>0 && array_keys($value)!==range(0,$n-1))`

 Modificado:
  `$n=count($value);
   try {
     $range = @range(0, $n-1);
   } catch (Exception $e) {
     $range = range(0.0, $n-1);
   }

   if(($n)>0 && array_keys($value)!==$range)`
```