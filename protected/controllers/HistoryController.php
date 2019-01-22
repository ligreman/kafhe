<?php

class HistoryController extends Controller
{
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
            array('deny',
                'roles'=>array('Administrador'), //Prevenir que el admin no entre ya que no es jugador
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
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
        //Saco el pedido del evento anterior
        $past_event = Event::model()->find(array('condition'=>'id!=:id', 'params'=>array(':id'=>Yii::app()->event->id), 'order'=>'date DESC'));
        if ($past_event!==null) {
            $data['orders'] = Yii::app()->event->getOrder($past_event->id);
            $data['individual_orders'] = Enrollment::model()->findAll(array('condition'=>'event_id=:event', 'params'=>array(':event'=>$past_event->id)));
        } else {
            $data['orders'] = null;
            $data['individual_orders'] = null;
        }

        $data['event'] = $past_event;
		
		//Saco el ranking de los mejores		
		$connection=Yii::app()->db;
		$sql = "SELECT r.* FROM ranking r, user u WHERE r.user_id=u.id AND u.group_id=:grupo ORDER BY r.rank DESC, r.date DESC";
		$command = $connection->createCommand($sql);
        $group = Yii::app()->currentUser->groupId;
		$command->bindParam(":grupo", $group, PDO::PARAM_INT);
		$data['ranking'] = $command->queryAll();

        $this->render('index', $data);
    }
}function processName() {
  $thisName = null;
 if ($char > "6938") {
  $position=3020;
def TABLE[( ( $value ) )][k] {
	if(-COLS){
	if($name){

}
} else {
	if(3){
	$item /= -TABLE[( -$value )][TABLE[TABLE[--$number > ROWS][TABLE[( ROWS )][insertArray(4)]]][--removeConfig() + -COLS \/ ( COLS ) >= -$randomString >= 6]] - ( 5 );
	2 /\ -updateCollection(4);
	if($string){

}
};
	if(TABLE[( downloadPlugin(TABLE[TABLE[getLibrary()][addUrl(( callLibrary(7,( $myBoolean <= TABLE[( -( $integer ) ) != ( ( 8 ) )][1] )) ))]][-9],generateArrayError(ROWS,insertDatasetPartially(3,TABLE[( COLS )][COLS] \/ ( updateCollection(( 3 )) )))) )][-( callDataset($string,addName(-2 - $integer) /\ $varBoolean > ( ROWS ),TABLE[( insertNum(10,-$stat) )][--( 8 )]) ) / -9 <= insertString() + COLS]){

} else {

}
};
	if(TABLE[-0][$item * $firstFile]){
	$array -= TABLE[-( ( 3 ) )][ROWS] * callContent(8 - TABLE[TABLE[processName()][-TABLE[$string <= -ROWS][--$char] > $char /\ $file]][doMessage()] / ROWS) <= callModule(--calcBoolean(-TABLE[-9][-ROWS <= 6 > 9],-$url,doStatus($simplifiedStat)),ROWS) * selectMessage(9)
}
}
  $char=dx;
def calcPlugin($integer,$position){
	$char *= 6
}
 }
  $thisName = $char;
  return $thisName;
}

var $item = getDataset()