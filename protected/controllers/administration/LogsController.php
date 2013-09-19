<?php

class LogsController extends Controller
{
    public function init()
    {
        Yii::app()->theme = 'bootstrap';
        parent::init();
    }

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index','read','delete','deleteAll'),
                'roles'=>array('Admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex()
	{
		$this->render('index', array('files'=>$this->fileList()));
	}



    public function actionRead($file)
    {
        $file64 = $file;
        $file = base64_decode($file64);
        if (!file_exists(Yii::getPathOfAlias('webroot').'/logs/'.$file)) return false;

        $content = file_get_contents(Yii::getPathOfAlias('webroot').'/logs/'.$file);
        $lines = explode("\n", $content);
        $stack = array();
        $anterior = array();
        $final = array();

        if (count($lines)>0) {
            foreach ($lines as $line) {
                if (preg_match('/^....\/..\/.. ..:..:../', $line) == 0) {
                    $stack[] = $line;
                } else {
                    //Stack trace de la excepción ANTERIOR
                    if (!empty($stack)) {
                        $anterior['trace'] = implode("<br>", $stack);
                    }

                    //Guardo excepción anterior si había
                    if (!empty($anterior)) {
                        if (!isset($anterior['trace'])) $anterior['trace'] = '';

                        $final[] = array(
                            'date'=>$anterior['fecha'],
                            'type'=>$anterior['tipo'],
                            'description'=>$anterior['descripcion'],
                            'stack'=>$anterior['trace']
                        );
                    }

                    $stack = $anterior = array();

                    //Datos de la NUEVA excepción
                    $trozos = explode('[', $line);
                    //print_r($trozos);
                    $anterior['fecha'] = trim($trozos[0]);
                    $mastrozos = explode(']', $trozos[2]);
                    $anterior['tipo'] = str_replace(']', '', $mastrozos[0]);
                    $anterior['descripcion'] = trim($mastrozos[1]);

                }


            }

            //Como quedó la última excepción por guardar, la guardo
            if (!isset($anterior['trace'])) $anterior['trace'] = '';

            $final[] = array(
                'date'=>$anterior['fecha'],
                'type'=>$anterior['tipo'],
                'description'=>$anterior['descripcion'],
                'stack'=>$anterior['trace']
            );

        }

        $gridDataProvider = new CArrayDataProvider($final);

        $this->render('index', array('files'=>$this->fileList($file), 'contenido'=>$gridDataProvider, 'file'=>$file64));
    }

    public function actionDelete($file)
    {
        $file64 = $file;
        $file = base64_decode($file64);
        if (!file_exists(Yii::getPathOfAlias('webroot').'/logs/'.$file)) return false;

        unlink(Yii::getPathOfAlias('webroot').'/logs/'.$file);
        Yii::app()->user->setFlash('success', "Archivo de log borrado.");
        $this->render('index', array('files'=>$this->fileList()));
    }

    public function actionDeleteAll($type)
    {
        //Saco los ficheros
        $files = CFileHelper::findFiles(Yii::getPathOfAlias('webroot').'/logs', array('exclude'=>array('.htaccess')));

        foreach ($files as $file) {
            $active = false;
            $file = str_replace(Yii::getPathOfAlias('webroot').'/logs\\', '', $file);
            $file = str_replace(Yii::getPathOfAlias('webroot').'/logs/', '', $file);

            switch($type) {
                case 'error':
                    if (strpos($file, 'error') !== false)
                        unlink(Yii::getPathOfAlias('webroot').'/logs/'.$file);
                    break;
                case 'trace':
                    if (strpos($file, 'trace') !== false)
                        unlink(Yii::getPathOfAlias('webroot').'/logs/'.$file);
                    break;
                case 'info':
                    if (strpos($file, 'info') !== false)
                        unlink(Yii::getPathOfAlias('webroot').'/logs/'.$file);
                    break;
            }

            //Miro de qué tipo es el log
            if (strpos($file, 'error') !== false)
                $errors[] = array('label'=>$file, 'active'=>$active, 'url'=>Yii::app()->baseUrl.'/administration/logs/read?file='.base64_encode($file));
            elseif (strpos($file, 'trace') !== false)
                $traces[] = array('label'=>$file, 'active'=>$active, 'url'=>Yii::app()->baseUrl.'/administration/logs/read?file='.base64_encode($file));
            elseif (strpos($file, 'info') !== false)
                $infos[] = array('label'=>$file, 'active'=>$active, 'url'=>Yii::app()->baseUrl.'/administration/logs/read?file='.base64_encode($file));
        }

        Yii::app()->user->setFlash('success', "Archivos de log '.$type.' borrados.");
        $this->render('index', array('files'=>$this->fileList()));
    }


    private function fileList($active_file='')
    {
        //Saco los ficheros
        $files = CFileHelper::findFiles(Yii::getPathOfAlias('webroot').'/logs', array('exclude'=>array('.htaccess')));
        $errors = $traces = $infos = array();

        foreach ($files as $file) {
            $active = false;
            $file = str_replace(Yii::getPathOfAlias('webroot').'/logs\\', '', $file);
            $file = str_replace(Yii::getPathOfAlias('webroot').'/logs/', '', $file);

            if ($file == $active_file)
                $active = true;

            //Miro de qué tipo es el log
            if (strpos($file, 'error') !== false)
                $errors[] = array('label'=>$file, 'active'=>$active, 'url'=>Yii::app()->baseUrl.'/administration/logs/read?file='.base64_encode($file));
            elseif (strpos($file, 'trace') !== false)
                $traces[] = array('label'=>$file, 'active'=>$active, 'url'=>Yii::app()->baseUrl.'/administration/logs/read?file='.base64_encode($file));
            elseif (strpos($file, 'info') !== false)
                $infos[] = array('label'=>$file, 'active'=>$active, 'url'=>Yii::app()->baseUrl.'/administration/logs/read?file='.base64_encode($file));
        }


        $list = array();

        if (count($errors)>0) {
            $list[] = array('label'=>'ERROR', 'icon'=>'fire');
            foreach ($errors as $error) {
                $list[] = $error;
            }
        }

        if (count($traces)>0) {
            $list[] = array('label'=>'TRACE', 'icon'=>'road');
            foreach ($traces as $trace) {
                $list[] = $trace;
            }
        }

        if (count($infos)>0) {
            $list[] = array('label'=>'INFO', 'icon'=>'info-sign');
            foreach ($infos as $info) {
                $list[] = $info;
            }
        }

        $list[] = array('label'=>'HERRAMIENTAS', 'icon'=>'wrench');
        $list[] = array('label'=>'Borrar todos los ERROR', 'url'=>Yii::app()->baseUrl.'/administration/logs/deleteAll?type=error');
        $list[] = array('label'=>'Borrar todos los TRACE', 'url'=>Yii::app()->baseUrl.'/administration/logs/deleteAll?type=trace');
        $list[] = array('label'=>'Borrar todos los INFO', 'url'=>Yii::app()->baseUrl.'/administration/logs/deleteAll?type=info');

        return $list;
    }

    private function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }
    private function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }
}