<?php

namespace frenzelgmbh\appcommon\widgets;

use Yii;
use yii\widgets\Block;

/**
 * A widget to switch languages within the application
 */
class wgtLanguage extends Block
{

  public $language = 'en_US';

  public $existinglanguages = ['en_US'=>'en_US','de_DE'=>'de_DE','hu_HU'=>'hu_HU','fr_FR'=>'fr_FR'];

  public function init()
  {       
    $this->language = Yii::$app->language;
    parent::init();
  }

  public function run()
  {    
    $this->renderContent();
    echo ob_get_clean();
  }

  protected function renderContent()
  {
    echo $this->render('@frenzelgmbh/appcommon/widgets/views/_wgtLanguage',array('language' => $this->language,'existinglanguages'=>$this->existinglanguages));
  }

}
