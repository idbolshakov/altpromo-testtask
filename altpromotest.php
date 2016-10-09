<?php 

if ( !defined('_PS_VERSION_') ) {
    
    exit;
}

/**
 * AltpromoTest
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

	$this->displayName = $this->1('Altpromo test');
	$this->description = $this->1('Altpromo hire test task');
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

                'DELETE FROM `' . _DB_PREFIX_ . $this->name . '`';
	    );

	    parent::uninstall();
        }
    }
}
?>

