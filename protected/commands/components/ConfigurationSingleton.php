<?php

/** ConfigurationSingleton para la configuración de Kafhe
 */
class ConfigurationSingleton extends CApplicationComponent
{
	private $_model = null;

	public function setModel()
    {
        $this->_model = Configuration::model()->findAll();
    }

    //Esta función la coge automáticamente
    public function getModel()
    {
        if (!$this->_model)
        {            
            $this->setModel();
        }

        return $this->_model;
    }

    /** Obtiene un parámetro de configuración almacenado en BBDD
     * @param $param parámetro a obtener
     * @return El valor del parámetro o false
     */
    public function getParam($param)
    {
		foreach ($this->model as $conf) {
			if ($conf->param == $param)
				return $conf->value;
		}
		
        return false;
    }
   
}