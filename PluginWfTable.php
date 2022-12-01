<?php
class PluginWfTable{
  /**
   * 
   */
  function __construct($buto = false) {
    if($buto){
      wfPlugin::includeonce('wf/yml');
      wfPlugin::enable('datatable/datatable_1_10_18');
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
      $i18n = $data->get('data/i18n');
      $i = new PluginWfArray($value);
      if(is_array($i->get())){
        $value = $i->get('text');
        if($i->is_set('i18n')){
          $i18n = $i->get('i18n');
        }
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
        $innerHTML = "<a class='small' data-toggle='collapse' href='#collapse$key'>Data</a><pre id='collapse$key' class='collapse'>".wfHelp::getYmlDump($innerHTML)."</pre>";
      }
      $tr[] = wfDocument::createHtmlElement('tr', array(
        wfDocument::createHtmlElement('th', $value, $i->get('th_attribute')),
        wfDocument::createHtmlElement('td', $innerHTML, $i->get('td_attribute'), array('i18n' => $i18n))
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
  public static function handle_array_keys($rs){
    $temp = array();
    foreach($rs as $k => $v){
      $temp[str_replace('/', '#', $k)] = $v;
    }
    return $temp;
  }
  public static function widget_render_many($data){
    $data = new PluginWfArray($data);
    /**
     * 
     */
    if(!$data->get('data/row/cursor')){
      /**
       * Should be set to pointer if click able row.
       */
      $data->set('data/row/cursor', 'default');
    }
    /**
     * 
     */
    $id_is_set = false;
    $data->set('data/class/table', 'table '.$data->get('data/class/table'));
    /**
     * Element.
     */
    $wf_table = new PluginWfTable(true);
    $element = $wf_table->getElement('render_many');
    /**
     * Settings
     */
    $element->setByTag(array('settings' => $data->get('data/table/settings')), 'table');
    /**
     * Ajax
     */
    if($data->get('data/datatable/ajax')){
      /**
       */
      $ajax = $data->get('data/datatable/ajax');
      foreach(wfRequest::getAll() as $k => $v){
        $ajax = str_replace('['.$k.']', $v, $ajax);
      }
      $data->set('data/datatable/ajax', $ajax);
      unset($ajax);
      /**
       */
      $element->setByTag(array('ajax' => $data->get('data/datatable/ajax')));
    }else{
      $element->setByTag(array('ajax' => ''));
    }
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
    }else{
      $element->setByTag(array('table' => 'width:100%'), 'style');
    }
    if(!$data->get('data/id')){
      $data->set('data/id', wfCrypt::getUid());
    }else{
      $id_is_set = true;
    }
    /**
     * Data.
     */
    $rs = $data->get('data/rs');
    /**
     * 
     */
    if(is_array(($rs))){
      $rs = PluginWfTable::arrange_array_keys($rs);
    }
    /**
     * Replace # to / in field keys.
     */
    if($data->get('data/field')){
      $temp = array();
      foreach($data->get('data/field') as $k => $v){
        $temp[str_replace('#', '/', $k)] = $v;
      }
      $data->set("data/field", $temp);
    }
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
     * columnDefs
     */
    if($data->get('data/datatable/ajax') && !$data->get('data/datatable/json/columnDefs')){
      $temp = array();
      $i = 0;
      foreach($field as $k => $v){
        /**
         * visible
         */
        $visible = true;
        if(is_array($v)){
          if(isset($v['visible'])){
            $visible = $v['visible'];
          }
        }
        /**
         * 
         */
        $temp[] = array('targets' => $i, 'data' => $k, 'visible' => $visible);
        $i++;
      }
      $data->set('data/datatable/json/columnDefs', $temp);
      unset($temp);
      unset($i);
    }
    /**
     * Add data to element.
     */
    $th = array();
    $tr = array();
    /**
     * Column name.
     */
    $column_settings = array('i18n' => $data->get('data/i18n_columns'));
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
      $th[] = wfDocument::createHtmlElement('th', $value, $i->get('th_attribute'), $column_settings);
    }
    /**
     * Data.
     */
    if(is_array(($rs))){
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
    if($data->get('data/datatable/json')){
      if(!$id_is_set){
        $data->set('data/datatable/json/stateSave', false);
      }
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
    if(is_array($rs) && sizeof($rs)==0){
      $datatable_disable = true;
    }
    if($data->get('data/datatable/ajax')){
      $datatable_disable = false;
    }
    /**
     * Set element.
     */
    $element->setByTag(array('thead_tr' => $th, 'tbody' => $tr, 'id' => $data->get('data/id'), 'datatable' => $datatable_disable));
    /**
     * Cursor pointer.
     */
    $element->setByTag(array('cursor' => "$(document).ready(function () { $('#".$data->get('data/id')." tbody').css('cursor', '".$data->get('data/row/cursor')."'); } )"), 'script');
    /**
     * Render.
     */
    wfDocument::renderElement($element->get());
  }
  public function getElement($name){
    return new PluginWfYml(__DIR__.'/element/'.$name.'.yml');
  }
}
