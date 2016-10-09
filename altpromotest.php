<?php 

// security stuff
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
     * Метод отвечает за установку модуля
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
     * метод отвечает за удаление модуля
     */
    public function uninstall() {

        if ( !parent::uninstall() ) {

            return false;
        }

        return true;
    }

    /**
     * hookDisplayLeftColumn
     *
     * hook left column
     *
     * получаем данные для шаблонизатора и показываем 
     * шаблон Модуля во фронт-офисе
     */
    public function hookDisplayLeftColumn($params) {

        $this->context->smarty->assign($this->getModuleConfiguration());

        return $this->display(__FILE__, 'altpromotest.tpl');
    }

    private function getModuleConfiguration() {

        $low  = Configuration::get('altpromotest_low');
        $high = Configuration::get('altpromotest_high');

        $sql = "
            SELECT COUNT(*) 
            FROM "._DB_PREFIX_."product
            WHERE   price >= '$low' AND price <= '$high'";

        $count = Db::getInstance()->getValue($sql);

        return array(
            
            'altpromotest_low'   => $low,
            'altpromotest_high'  => $high,
            'altpromotest_count' => $count
        );
    }

    /**
     * hookDisplayLeftColumn
     *
     * hook right column
     */
    public function hookDisplayRightColumn($params) {

        return $this->hookDisplayLeftColumn($params);

    }

    /**
     * hookDisplayHeader
     *
     * hook header
     */
    public function hookDisplayHeader($params) {

        $this
            ->context
            ->controller
            ->addCSS($this->_path.'css/altpromotest.css', 'all');
    }

    /**
     * getContent
     *
     * вызывается, когда страница 
     * настройки модуля загружена из бэк-офиса
     */
    public function getContent() {

        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {

            // low value
            $low_value = strval(Tools::getValue('altpromotest_low'));

            if (!$low_value
                || empty($low_value)
                || !Validate::isGenericName($low_value)
                || !is_numeric($low_value)) {

                $output .= $this->displayError(
                    $this->l('Неверный формат значения "от"')
                );

            } else {

                Configuration::updateValue('altpromotest_low', $low_value);

                $output .= $this->displayConfirmation(
                    $this->l('Значение "от" успешно установлено')
                );
            }

            // high value
            $high_value = strval(Tools::getValue('altpromotest_high'));

            if (!$high_value
                || empty($high_value)
                || !Validate::isGenericName($high_value)
                || !is_numeric($high_value)) {

                $output .= $this->displayError(
                    $this->l('Неверный формат значения "до"')
                );

            } else {

                Configuration::updateValue('altpromotest_high', $high_value);

                $output .= $this->displayConfirmation(
                    $this->l('Значение "до" успешно установлено')
                );
            }
        }
        
        return $output.$this->displayForm();
    }

    /**
     * displayForm
     *
     * показываем форму конфигурации модуля
     * в бэк-офисе
     */
    public function displayForm() {

        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = array(

            'legend' => array(

                'title' => $this->l('Настройки модуля'),
            ),

            'input' => array(

                array(

                    'type'     => 'text',
                    'label'    => $this->l('от'),
                    'name'     => 'altpromotest_low',
                    'size'     => 10,
                    'required' => true
                ),

                array(

                    'type'     => 'text',
                    'label'    => $this->l('до'),
                    'name'     => 'altpromotest_high',
                    'size'     => 10,
                    'required' => true
                )
            ),

            'submit' => array(

                'title' => $this->l('Применить'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        $helper->module          = $this;
        $helper->name_controller = $this->name;
        $helper->token           = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex    = 
            AdminCOntroller::$currentIndex.'&configure='.$this->name;

        $helper->submit_action   = 'submit'.$this->name;


        $helper->fields_value['altpromotest_low'] 
            = Configuration::get('altpromotest_low');

        $helper->fields_value['altpromotest_high'] 
            = Configuration::get('altpromotest_high');


        return $helper->generateForm($fields_form);
    }
}
?>

