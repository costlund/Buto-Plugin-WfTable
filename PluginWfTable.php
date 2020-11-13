<?php
class PluginWfTable{
  /**
   * 
   */
  private $bootstrap_version = '4';
  function __construct($buto = false) {
    if($buto){
      wfPlugin::includeonce('wf/yml');
      $user = wfUser::getSession();
      if(!$user->get('plugin/twitter/bootstrap413v/include')){
        $this->bootstrap_version = '3';
      }
      if($this->bootstrap_version == '4'){
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
     * Merge data.
     */
    $data['data'] = array_merge(array('key_is_missing_alert' => true), $data['data']);
    /**
     * Modify data.
     */
    $data = new PluginWfArray($data);
    $data->set('data/class/table', 'table '.$data->get('data/class/table'));
    $element->setByTag($data->get('data/class'), 'class');
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
        if($data->get('data/key_is_missing_alert')){
          $innerHTML = "[key $key is missing]";
        }else{
          $innerHTML = '';
        }
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
  public static function arrange_array_keys($rs){
    /**
     * If first array key is zero we has to create new ones to make tr attribute id to work when using keys.
     */
    if(isset($rs[0]) && array_keys($rs)[0]==0){
      $temp = array();
      foreach($rs as $k => $v){
        $temp[wfCrypt::getUid()] = $v;
      }
      $rs = $temp;
      unset($temp);
    }
    return $rs;
  }
  public static function widget_render_many($data){
    $data = new PluginWfArray($data);
    $data->set('data/class/table', 'table '.$data->get('data/class/table'));
    /**
     * Element.
     */
    $wf_table = new PluginWfTable(true);
    $element = $wf_table->getElement('render_many');
    /**
     * Element after
     */
    $element->setByTag(array('after' => $data->get('data/element/after')), 'element');
    /**
     * Export
     */
    if($data->get('data/datatable/export/disabled')){
      $element->setUnset('0/innerHTML/2/innerHTML/0/data/data/json/dom');
      $element->setUnset('0/innerHTML/2/innerHTML/0/data/data/json/buttons');
      $element->setUnset('0/innerHTML/3/innerHTML/0/data/data/json/dom');
      $element->setUnset('0/innerHTML/3/innerHTML/0/data/data/json/buttons');
    }else{
      if($data->get('data/datatable/export/title')){
        $element->setByTag(array('title' => $data->get('data/datatable/export/title')), 'export');
      }else{
        $element->setByTag(array('title' => 'Data from '.wfSettings::getHttpAddress(true).' '.date('Y-m-d H:i:s').'.'), 'export');
      }
    }
    /**
     * searching
     */
    if($data->get('data/datatable/searching/disabled')){
      $element->set('0/innerHTML/2/innerHTML/0/data/data/json/searching', false);
      $element->set('0/innerHTML/3/innerHTML/0/data/data/json/searching', false);
    }
    /**
     * Order
     */
    if($data->get('data/datatable/order')){
      $element->set('0/innerHTML/2/innerHTML/0/data/data/json/order', $data->get('data/datatable/order'));
      $element->set('0/innerHTML/3/innerHTML/0/data/data/json/order', $data->get('data/datatable/order'));
    }
    /**
     * 
     */
    $element->setByTag($data->get('data/class'), 'class');
    if($data->get('data/style')){
      $element->setByTag(array('table' => $data->get('data/style')), 'style');
    }
    if(!$data->get('data/id')){
      $data->set('data/id', wfCrypt::getUid());
    }
    /**
     * Data.
     */
    $rs = $data->get('data/rs');
    /**
     * 
     */
    $rs = PluginWfTable::arrange_array_keys($rs);
    /**
     * 
     */
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
        if($key == 'row_attribute' || $key == 'row_settings'){
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
        /**
         * Row click
         */
        if($item->get('row_click')){
          $attribute->set('onclick', $item->get('row_click'));
        }else{
          $attribute->set('style', 'cursor:auto');
        }
        /**
         * Row attribute
         */
        if($item->get('row_attribute')){
          foreach($item->get('row_attribute') as $k2 => $v2){
            $attribute->set($k2, $v2);
          }
        }
        /**
         * Row id
         */
        if($item->get('row_id')){
          $attribute->set('id', $item->get('row_id'));
        }else{
          $attribute->set('id', 'row_'.$key);
        }
        $td = array();
        foreach ($field as $key2 => $value2) {
          $i2 = new PluginWfArray($value2);
          /**
           * Attribute
           */
          $td_attribute = new PluginWfArray($i2->get('td_attribute'));
          if(!$td_attribute->get('id')){
            $td_attribute->set('id', $key2);
          }
          /**
           * 
           */
          if($key2 == 'row_click'){
            continue;
          }
          if($key2 == 'row_attribute' || $key == 'row_settings'){
            continue;
          }
          if(!array_key_exists($key2, $value)){
            //continue;
          }
          /**
           * Handle if array.
           */
          if(is_array($item->get($key2))){
            $item->set($key2, $item->get($key2));
          }
          /**
           * Element
           */
          if($data->get("data/element/$key2")){
            $data_element = new PluginWfArray($data->get("data/element/$key2"));
            $data_element->setByTag($item->get(), 'wf_table');
            $item->set($key2, $data_element->get());
          }
          /**
           * 
           */
          $td[] = wfDocument::createHtmlElement('td', $item->get($key2), $td_attribute->get(), array('i18n' => $data->get('data/i18n')));
        }
        /**
         * 
         */
        $tr[] = wfDocument::createHtmlElement('tr', $td, $attribute->get(), $item->get('row_settings'));
      }
    }
    /**
     * Datatable.
     */
    $datatable_disable = true;
    $id_hook = 'datatable_1_10_18';
    $user = wfUser::getSession();
    if(!$user->get('plugin/twitter/bootstrap413v/include')){
      $id_hook = 'datatable_1_10_16';
    }
    if($data->get('data/datatable/json')){
      $datatable_disable = false;
      $json = $element->getById($id_hook)->get('data/data/json');
      $json = array_merge($json, $data->get('data/datatable/json'));
      $element->setById($id_hook, 'data/data/json', $json);
    }
    if($data->get('data/datatable/disabled') !== null){
      $datatable_disable = $data->get('data/datatable/disabled');
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
