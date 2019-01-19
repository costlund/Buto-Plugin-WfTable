<?php
class PluginWfTable{
  /**
   * 
   */
  function __construct($buto = false) {
    if($buto){
      wfPlugin::includeonce('wf/yml');
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
    $field = $data->get('data/field');
    /**
     * Add data to element.
     */
    $tr = array();
    if(!$rs){
      $rs = array('' => '');
    }
    foreach ($rs as $key => $value){
      $item = new PluginWfArray(array('label' => $key));
      if($field && isset($field[$key])){
        $item->set('label', $field[$key]);
      }elseif($field && !isset($field[$key])){
        continue;
      }
      if(is_array($value)){
        $value = wfHelp::getYmlDump($value);
        $value = str_replace("\n", "<br>", $value);
      }
      $tr[] = wfDocument::createHtmlElement('tr', array(
        wfDocument::createHtmlElement('th', $item->get('label')),
        wfDocument::createHtmlElement('td', $value, array(), array('i18n' => $data->get('data/i18n')))
        ));
    }
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
        $th[] = wfDocument::createHtmlElement('th', $value);
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
          if($key2 == 'row_click'){
            continue;
          }
          if(!array_key_exists($key2, $value)){
            continue;
          }
          $td[] = wfDocument::createHtmlElement('td', $value[$key2], array(), array('i18n' => $data->get('data/i18n')));
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
    $element->setByTag(array('thead_tr' => $th, 'tbody' => $tr, 'id' => wfCrypt::getUid(), 'datatable' => $datatable_disable));
    /**
     * Render.
     */
    wfDocument::renderElement($element->get());
  }
  public function getElement($name){
    return new PluginWfYml(__DIR__.'/element/'.$name.'.yml');
  }
}
