<?php

class CronCommand extends CConsoleCommand {
	public $global_param = true;
	
	//Acción por defecto: index
    public function actionIndex ($param1, $param2='default', array $param3)
	{
        // here we are doing what we need to do
		echo "ok";
		return 0;
    }
	
	public function actionNo ()
	{
		echo "No";
		return 0;
	}
	
	
	public function actionRegenerarTueste ()	
	{
		echo "Regenero el tueste";
		return 0;
	}
}
