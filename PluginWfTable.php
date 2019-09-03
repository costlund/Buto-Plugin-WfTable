<?php
class PluginWfTable{
  /**
   * 
   */
  function __construct($buto = false) {
    if($buto){
      wfPlugin::includeonce('wf/yml');
      $user = wfUser::getSession();
      if($user->get('plugin/twitter/bootstrap413v/include')){
        wfPlugin::enable('datatable/datatable_1_10_18');
      }else{
        wfPlugin::enable('datatable/datatable_1_10_16');
      }
    }
  }
  /**
   * Render table from rs where each value has one row with th and td.
   * @param Array $rs
   */
  public static function widget_render_one($data){
    /**
     * Element.
     */
    $wf_table = new PluginWfTable(true);
    $element = $wf_table->getElement('render_one');
    /**
     * Data.
     */
    $data = new PluginWfArray($data);
    $rs = $data->get('data/rs');
    if(!$rs){
      $rs = array('' => '');
    }
    $field = $data->get('data/field');
    /**
     * Add field from rs if empty.
     */
    if(!$field){
      $field = array();
      foreach ($rs as $key => $value) {
        $field[$key] = $key;
      }
    }
    /**
     * 
     */
    $tr = array();
    foreach ($field as $key => $value){
      $i = new PluginWfArray($value);
      if(is_array($i->get())){
        $value = $i->get('text');
      }
      if(array_key_exists($key, $rs)){
        $innerHTML = $rs[$key];
      }else{
        $innerHTML = "[key $key is missing]";
      }
      if(is_array($innerHTML)){
        $innerHTML = wfHelp::getYmlDump($innerHTML);
        $innerHTML = "<pre>$innerHTML</pre>";
      }
      $tr[] = wfDocument::createHtmlElement('tr', array(
        wfDocument::createHtmlElement('th', $value, $i->get('th_attribute')),
        wfDocument::createHtmlElement('td', $innerHTML, $i->get('td_attribute'), array('i18n' => $data->get('data/i18n')))
        ), $i->get('tr_attribute'));
    }
    /**
     * Add tr to element.
     */
    $element->setByTag(array('tr' => $tr));
    /**
     * Render.
     */
    wfDocument::renderElement($element->get());
  }
  public static function widget_render_many($data){
    $data = new PluginWfArray($data);
    /**
     * Element.
     */
    $wf_table = new PluginWfTable(true);
    $element = $wf_table->getElement('render_many');
    if($data->get('data/style')){
      $element->setByTag($data->get('data/style'), 'style');
    }
    if(!$data->get('data/id')){
      $data->set('data/id', wfCrypt::getUid());
    }
    /**
     * Data.
     */
    $rs = $data->get('data/rs');
    $field = array();
    if($data->get('data/field')){
      $field = $data->get('data/field');
    }else{
      foreach ($rs as $key => $value) {
        foreach ($value as $key2 => $value2) {
          $field[$key2] = $key2;
        }
        break;
      }
    }
    /**
     * Add data to element.
     */
    $th = array();
    $tr = array();
    if(is_array(($rs))){
      /**
       * Column name.
       */
      foreach ($field as $key => $value) {
        if($key == 'row_click'){
          continue;
        }
        $i = new PluginWfArray($value);
        if(is_array($i->get())){
          $value = $i->get('text');
        }
        $th[] = wfDocument::createHtmlElement('th', $value, $i->get('th_attribute'));
      }
      /**
       * Data.
       */
      foreach ($rs as $key => $value) {
        $item = new PluginWfArray($value);
        $attribute = new PluginWfArray();
        if($item->get('row_click')){
          $attribute->set('onclick', $item->get('row_click'));
        }else{
          $attribute->set('style', 'cursor:auto');
        }
        $td = array();
        foreach ($field as $key2 => $value2) {
          $i2 = new PluginWfArray($value2);
          if($key2 == 'row_click'){
            continue;
          }
          if(!array_key_exists($key2, $value)){
            //continue;
          }
          $td[] = wfDocument::createHtmlElement('td', $item->get($key2), $i2->get('td_attribute'), array('i18n' => $data->get('data/i18n')));
        }
        $tr[] = wfDocument::createHtmlElement('tr', $td, $attribute->get());
      }
    }
    /**
     * Datatable.
     */
    $datatable_disable = true;
    if($data->get('data/datatable/json')){
      $datatable_disable = false;
      $json = $element->get('0/innerHTML/1/data/data/json');
      $json = array_merge($json, $data->get('data/datatable/json'));
      $element->set('0/innerHTML/1/data/data/json', $json);
    }
    if($data->get('data/datatable/disabled') !== null){
      $datatable_disable = $data->get('data/datatable/disabled');
    }
    if($data->get('data/datatable/json')){
      $json = $element->get('0/innerHTML/1/data/data/json');
      $json = array_merge($json, $data->get('data/datatable/json'));
      $element->set('0/innerHTML/1/data/data/json', $json);
    }
    /**
     * If no data we disable datatable to avoid Javascript error.
     */
    if(sizeof($rs)==0){
      $datatable_disable = true;
    }
    /**
     * Set element.
     */
    $element->setByTag(array('thead_tr' => $th, 'tbody' => $tr, 'id' => $data->get('data/id'), 'datatable' => $datatable_disable));
    /**
     * Render.
     */
    wfDocument::renderElement($element->get());
  }
  public function getElement($name){
    return new PluginWfYml(__DIR__.'/element/'.$name.'.yml');
  }
}
