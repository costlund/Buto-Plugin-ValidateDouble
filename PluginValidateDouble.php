<?php
// Update this from other Mac.
class PluginValidateDouble{
  private $i18n = null;
  function __construct() {
    wfPlugin::includeonce('i18n/translate_v1');
    $this->i18n = new PluginI18nTranslate_v1();
    $this->i18n->setPath('/plugin/validate/double/i18n');
  }
  public function validate_double($field, $form, $data = array()){
    if(wfArray::get($form, "items/$field/is_valid") && strlen(wfArray::get($form, "items/$field/post_value"))){ // Only if valid and has data.
      wfPlugin::includeonce('wf/array');
      $data = new PluginWfArray($data);
      if (!$this->is_double(wfArray::get($form, "items/$field/post_value"), $data)) {
        $form = wfArray::set($form, "items/$field/is_valid", false);
        $form = wfArray::set($form, "items/$field/errors/", $this->i18n->translateFromTheme('?label is not a double!', array('?label' => wfArray::get($form, "items/$field/label"))));
      }elseif(!$this->check_decimals(wfArray::get($form, "items/$field/post_value"), $data)){
        $form = wfArray::set($form, "items/$field/is_valid", false);
        $form = wfArray::set($form, "items/$field/errors/", $this->i18n->translateFromTheme('?label has more than ?decimals decimals!', array('?label' => wfArray::get($form, "items/$field/label"), '?decimals' => $data->get('decimals'))));
      }elseif(strlen($data->get('min')) && (double)wfArray::get($form, "items/$field/post_value") < $data->get('min')){
        $form = wfArray::set($form, "items/$field/is_valid", false);
        $form = wfArray::set($form, "items/$field/errors/", $this->i18n->translateFromTheme('?label can not be less then ?min!', array('?label' => wfArray::get($form, "items/$field/label"), '?min' => $data->get('min'))));
      }elseif(strlen($data->get('max')) && (double)wfArray::get($form, "items/$field/post_value") > $data->get('max')){
        $form = wfArray::set($form, "items/$field/is_valid", false);
        $form = wfArray::set($form, "items/$field/errors/", $this->i18n->translateFromTheme('?label can not be greather then ?max!', array('?label' => wfArray::get($form, "items/$field/label"), '?max' => $data->get('max'))));
      }
    }
    return $form;   
  }
  private function is_double($num, $data = array()){
    $num = str_replace(',', '.', $num);
    if($num == '0'){
      return true;
    }
    if(intval($num)){
      /**
       * We consider an integer is also a valid double.
       * 23 / 23,12 / 23.12 / 23xxx comes here.
       */
      if(is_numeric($num)){
        return true;
      }else{
        /**
         * 23xxx comes here.
         */
        return false;
      }
    }else{
      if(is_numeric($num)){
        return true;
      }else{
        /**
         * 23xxx comes here.
         */
        return false;
	  }
    }
  }
  private function check_decimals($num, $data){
    $num = str_replace(',', '.', $num);
    if(strstr($num, '.')){
      /**
       * We deal with a decimal value.
       * Counting decimals.
       */
      $x = preg_split('/_dot_/', str_replace('.', '_dot_', $num));
      if(strlen($x[1]) > $data->get('decimals')){
          return false;
      }else{
        return true;
      }
    }else{
      return true;
    }
  }
}
