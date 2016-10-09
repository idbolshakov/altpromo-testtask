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
 * @version 1.0.0
 * @author idbolshakov@gmail.com
 */
class AltpromoTest extends Module {

    /**
     * конструктор класса
     *
     * инициализация модуля
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

        if (parent::install() == false) {

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

	    Db::getInstance()->Execute(

                'DELETE FROM `' . _DB_PREFIX_ . $this->name . '`'
	    );

	    parent::uninstall();
        }
    }
}
?>

