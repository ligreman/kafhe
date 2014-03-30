<?php

class FameGumbudosController extends Controller
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
        if (!file_exists(Yii::getPathOfAlias('webroot').'/logs/csv/'.$file)) return false;

        $content = file_get_contents(Yii::getPathOfAlias('webroot').'/logs/csv/'.$file);
        $lines = explode("\n", $content);
        $acciones = array();

        $totalGumbudos = $maxFama = $minFama = $avgFama = $sumaFama = $dataGumbudo = array(
            'Asaltante'=>0,
            'Guardián'=>0,
            'Artificiero'=>0,
            'Asedio'=>0,
            'Nigromante'=>0,
            'Pestilente'=>0
        );

        $actGumbudos = array();

        if (count($lines)>0) {
            foreach ($lines as $line) {
                list($id, $name, $fame, $time) = explode(',', $line);

                $acciones[] = array(
                    'id'=>$id,
                    'name'=>$name,
                    'fame'=>$fame,
                    'timestamp'=>$time
                );
            }
            //unset($lines);

            //Para cada acción, voy generando la información que quiero
            foreach ($acciones as $accion) {
                $totalGumbudos[$accion['name']]++; //sumo un gumbudo
                $sumaFama[$accion['name']] += $accion['fame']; //sumo la fama

                // Máximos y mínimos
                if ($minFama[$accion['name']] > $accion['fame']) $minFama[$accion['name']] = $accion['fame'];
                if ($maxFama[$accion['name']] < $accion['fame']) $maxFama[$accion['name']] = $accion['fame'];

                //Acciones por separado
                $actGumbudos[$accion['name']][] = array(
                    'id'=>$accion['id'],
                    'name'=>$accion['name'],
                    'fame'=>$accion['fame'],
                    'timestamp'=>$accion['timestamp']
                );
            }

            //Media
            foreach ($avgFama as $key=>$value) {
                if ($totalGumbudos[$key] > 0) {
                    $avgFama[$key] = round($sumaFama[$key]/$totalGumbudos[$key], 2);
                }
            }
        }

        $datos = Skill::model()->findAll(array('condition'=>'category=:cat', 'params'=>array(':cat'=>'corral')));
        foreach ($datos as $dato) {
            switch($dato->keyword) {
                case 'gumbudoAsaltante': $dataGumbudo['Asaltante'] = array('duration'=>$dato->gumbudo_action_duration, 'rate'=>$dato->gumbudo_action_rate);
                    break;
                case 'gumbudoGuardian': $dataGumbudo['Guardián'] = array('duration'=>$dato->gumbudo_action_duration, 'rate'=>$dato->gumbudo_action_rate);
                    break;
                case 'gumbudoArtificiero': $dataGumbudo['Artificiero'] = array('duration'=>$dato->gumbudo_action_duration, 'rate'=>$dato->gumbudo_action_rate);
                    break;
                case 'gumbudoAsedio': $dataGumbudo['Asedio'] = array('duration'=>$dato->gumbudo_action_duration, 'rate'=>$dato->gumbudo_action_rate);
                    break;
                case 'gumbudoNigromante': $dataGumbudo['Nigromante'] = array('duration'=>$dato->gumbudo_action_duration, 'rate'=>$dato->gumbudo_action_rate);
                    break;
                case 'gumbudoPestilente': $dataGumbudo['Pestilente'] = array('duration'=>$dato->gumbudo_action_duration, 'rate'=>$dato->gumbudo_action_rate);
                    break;
            }
        }
        unset($datos);

        //print_r($totalGumbudos);
        //print_r($minFama);
        //print_r($maxFama);
        //print_r($avgFama);
        //$gridDataProvider = new CArrayDataProvider($final);

        $this->render('index', array('files'=>$this->fileList($file), 'file'=>$file64, 'minFame'=>$minFama, 'maxFame'=>$maxFama, 'totalGumbudos'=>$totalGumbudos, 'avgFame'=>$avgFama, 'sumFame'=>$sumaFama, 'acciones'=>$actGumbudos, 'lines'=>$lines, 'dataGumbudo'=>$dataGumbudo));
    }

    private function fileList($active_file='')
    {
        //Saco los ficheros
        $files = CFileHelper::findFiles(Yii::getPathOfAlias('webroot').'/logs/csv', array('exclude'=>array('.htaccess')));
        $csvs = array();

        foreach ($files as $file) {
            $active = false;
            $file = str_replace(Yii::getPathOfAlias('webroot').'/logs/csv\\', '', $file);
            $file = str_replace(Yii::getPathOfAlias('webroot').'/logs/csv/', '', $file);

            if ($file == $active_file)
                $active = true;

            $csvs[] = array('label'=>$file, 'active'=>$active, 'url'=>Yii::app()->baseUrl.'/administration/fameGumbudos/read?file='.base64_encode($file));
        }


        $list = array();

        if (count($csvs)>0) {
            $list[] = array('label'=>'ESTADÍSTICAS FAMA', 'icon'=>'signal');
            foreach ($csvs as $csv) {
                $list[] = $csv;
            }
        }

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