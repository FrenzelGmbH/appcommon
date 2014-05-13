<?php
/**
 * @link http://www.frenzel.net/
 * @author Philipp Frenzel <philipp@frenzel.net> 
 */

namespace frenzelgmbh\appcommon;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class commonAsset extends AssetBundle
{
    public $sourcePath = '@frenzelgmbh/appcommon/assets';
    
    public $css = [];
    
    public $js = [
      'js/css3-mediaqueries.js',
      'js/jquery.form.js'
    ];
    
    public $depends = [
      'yii\web\YiiAsset'
    ];
}
