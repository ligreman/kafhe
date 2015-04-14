<?php

/** Utilidades
 */
class UtilsSingleton extends CApplicationComponent
{
	/** Devuelve la fecha actual formateada
     * @param string $fecha Si se indica una fecha, se convierte a GMT+1
     * @param string $format Formato de salida de la fecha
     * @return bool|string
     */
    public function getCurrentDate($fecha='now', $format='Y-m-d H:i:s') {
        $date = new DateTime($fecha, new DateTimeZone('Europe/Madrid'));
        return $date->format($format);
    }

    /** Devuelve un objeto DateTime actual
     * @param string $fecha Si se indica una fecha, se convierte a GMT+1
     * @return DateTime
     */
    public function getCurrentDateTime($fecha='now') {
        $date = new DateTime($fecha, new DateTimeZone('Europe/Madrid'));
        return $date;
    }

    /** Devuelte el time actual
     * @param string $fecha Si se indica, se convierte a GMT+1
     * @return int
     */
    public function getCurrentTime($fecha='now') {
        $date = new DateTime($fecha, new DateTimeZone('Europe/Madrid'));
        return strtotime($date->format('Y-m-d H:i:d'));
    }

    /** Devuelve un array con las diferencias
     * @param $oldDate DateTime
     * @param $newDate DateTime
     * @return array
     */
    public function getDateTimeDiff($oldDate, $newDate) {
        $return = array();

        $Y1 = $oldDate->format('y');
        $Y2 = $newDate->format('y');
        $return['years'] = abs($Y2 - $Y1);

        $m1 = $oldDate->format('m');
        $m2 = $newDate->format('m');
        $return['months'] = abs($m2 - $m1);

        $d1 = $oldDate->format('d');
        $d2 = $newDate->format('d');
        $return['days'] = abs($d2 - $d1);

        $H1 = $oldDate->format('h');
        $H2 = $newDate->format('h');
        $return['hours'] = abs($H2 - $H1);

        $i1 = $oldDate->format('i');
        $i2 = $newDate->format('i');
        $return['minutes'] = abs($i2 - $i1);

        $s1 = $oldDate->format('s');
        $s2 = $newDate->format('s');
        $return['seconds'] = abs($s2 - $s1);

        return $return;
    }

    /** Convierte la fecha del uso horario en que esté a GMT+1
     * @param $fecha Texto con el timestamp de la fecha
     */
    /*public function convertDate($fecha, $returnDateTime=false) {
        $actual = date_create($fecha);
        date_timezone_set($actual, timezone_open('Europe/Madrid'));
        $date = date_format($actual, 'Y-m-d H:i:s');

        if ($returnDateTime)
            return $actual;
        else
            return $date;
    }*/

	/** Regenera el ranking de jugadores de todos los grupos.
     * @return bool
     */
    public function generateRanking() {
        //Cojo los grupos
        $mostrar = 5; //Cantidad usuarios a mostrar en el ranking
        $groups = Group::model()->findAll();

        foreach ($groups as $group) {
            //Primero saco el ranking actual de ese grupo
            $ranking = Ranking::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$group->id), 'order'=>'rank DESC'));

            //Saco los usuarios del grupo ordenados por rango
            $users = User::model()->findAll(array('condition'=>'group_id=:grupo', 'params'=>array(':grupo'=>$group->id), 'order'=>'rank DESC'));

            if ($ranking == null) {
                //echo "Genero un ranking nuevo.\n";
                foreach ($users as $user) {
                    if(count($ranking)>=$mostrar) break; //termino si ya tengo la cantidad a mostrar

                    $newR = new Ranking;
                    $newR->user_id = $user->id;
                    $newR->group_id = $user->group_id;
                    $newR->rank = $user->rank;
                    $newR->date = $this->getCurrentDate('now', 'Y-m-d');

                    $ranking[] = $newR;
                }
            } else {
                //echo "Reorganizo el ranking (".count($ranking)."). Hay ".count($users)." usuarios.\n";
                foreach ($users as $user) {
                    $reorganizarme = false;
                    $estoy_en_ranking = false;
                    $colocado = false;

                    $newR = new Ranking;
                    $newR->user_id = $user->id;
                    $newR->group_id = $user->group_id;
                    $newR->rank = $user->rank;
                    $newR->date = $this->getCurrentDate('now', 'Y-m-d');

                    //Miro a ver si estoy en el ranking y con qué rango
                    for ($i=0; $i<count($ranking); $i++) {
                        //¿Soy yo?
                        if($ranking[$i]->user_id == $user->id) {
                            $estoy_en_ranking = true; //Estoy en el ranking
                            $colocado = true;

                            if($ranking[$i]->rank > $user->rank) {
                                //No hago nada
                            } elseif($ranking[$i]->rank > $user->rank) {
                                $ranking[$i]->date = $this->getCurrentDate('now', 'Y-m-d'); //Actualizo la fecha simplemente
                            } else {
                                //Tengo que reoganizarme y colocarme en el ranking de nuevo
                                $reorganizarme = true;
                            }
                        }
                    }

                    //¿Me tengo que reorganizar o no estaba en el ranking?
                    if ($reorganizarme || !$estoy_en_ranking) {
                        //Busco mi sitio en el ranking y me meto
                        for ($i=0; $i<count($ranking); $i++) {
                            if ($user->rank >= $ranking[$i]->rank) {
                                //echo "   Meto por encima al usuario.\n";
                                //Coloco al usuario encima de esta posición del ranking
                                array_splice($ranking, $i, 0, array($newR));
                                $colocado = true;

                                break; //Termino de mirar posiciones del ranking para este usuario
                            }
                        }


                    }

                    //Si no estoy colocado en ninguna parte es que me voy pal final del ranking
                    if (!$colocado)
                        array_push($ranking, $newR);

                    //echo "Reorganizado el usuario ".$user->id.". El ranking tiene ".count($ranking).".\n";
                }

            }

            //Por último, guardo el ranking de este grupo (los 10 primeros sólo)
            $connection = Yii::app()->db;
            //echo "Eliminamos duplicados y preparamos SQL. En el ranking hay ".count($ranking)." entradas.\n";

            $values = $ya_ha_salido = array();
            $cuenta = 0;
            for ($i=0; $i<count($ranking); $i++) {
                if ($cuenta>=$mostrar) break; //Paro si llego al tope

                //echo "Buscando posicion ".$i." del ranking. La cuenta va por ".$cuenta." y he de llegar a ".$mostrar.".\n";

                if (in_array($ranking[$i]->user_id, $ya_ha_salido)) continue;
                else $ya_ha_salido[] = $ranking[$i]->user_id; //Evito repetidos, solo 1 vez en toda la lista cada user

                $values[] = "(".$ranking[$i]->user_id.", ".$ranking[$i]->group_id.", ".$ranking[$i]->rank.", '".$ranking[$i]->date."')";

                $cuenta++;
                //echo "Ya hay ".count($values)." usuarios en el ranking.\n";
            }

            //echo "Borro el ranking anterior.\n";
            $sql = "DELETE FROM ranking WHERE group_id=".$group->id;
            $command=$connection->createCommand($sql);
            $command->execute();

            $sql="INSERT INTO ranking (user_id, group_id, rank, date) VALUES ".implode(',',$values);
            //echo "Consulta SQL: ".$sql.";\n";
            $command=$connection->createCommand($sql);
            $command->execute();
        }

        return true;
    }

    /** Escribe en el CSV
     * @param $message Lo que escribir
     */
    public function logCSV($message) {
        $date = $this->getCurrentDate('now', 'Y-m-d');
        $name = $date."-fame.csv";
        $ruta = Yii::getPathOfAlias('webroot').'/../logs/csv/';

        file_put_contents($ruta.$name, $message."\n", FILE_APPEND);
    }
}


