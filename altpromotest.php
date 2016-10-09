<?php 

if ( !defined('_PS_VERSION_') ) {
    
    exit;
}

/**
 * AltpromoTest
 *
 * Модуль тестового задания
 * на вакансию начинающий php программист
 *
 * Модуль отвечает за сохранение в бэк-офисе 
 * двух числовых значений "от" и "до", 
 * а во фронт-офисе выводит в левую колонку
 * количество товаров в базе со стоимостью в диапазоне
 * сохраненных значений
 *
 * @version 1.0.0
 * @author idbolshakov@gmail.com
 */
class AltpromoTest extends Module {

    /**
     * __construct
     *
     */
    public function __construct() {

        $this->name          = 'altpromotest';
    	$this->tab           = 'altpromo';
    	$this->version       = '1.0.0';
    	$this->author        = 'idbolshakov@gmail.com';
    	$this->need_instance = 0;
    
    	parent::__construct();

    	$this->displayName = $this->l('Altpromo test');
    	$this->description = $this->l('Altpromo hire test task');
    }

    /**
     * install
     *
     */
    public function install() {

        if (Shop::isFeatureActive()) {

            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (
            !parent::install() || 
            !$this->registerHook('leftColumn') ||
            !$this->registerHook('header') 
        ) {

	        return false;
	    }

	    return true;
    }

    /**
     * uninstall
     *
     */
    public function uninstall() {

        if ( !parent::uninstall() ) {

            return false;
        }

        return true;
    }
}
?>

