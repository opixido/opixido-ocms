<?php

abstract class genform_module
{
    /**
     * @var GenForm
     */
    public $gf;

    /**
     * genform_module constructor.
     * @param $gf GenForm
     */
    public function __construct($gf)
    {
        $this->gf = $gf;
    }

    /**
     * Implémenter un test pour vérifier si ce champ est à prendre en compte avec ce module
     * @param $field
     * @return mixed
     */
    public abstract function checkCondition($field);

    /**
     * Générer le champ en question ...
     * @return mixed
     */
    public abstract function gen($field);

}
