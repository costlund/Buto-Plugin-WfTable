# Buto-Plugin-WfTable
Widgets to render array data in tables.



Render data in table.
Set data/i18n to avoid translation for tbody. 
Use param row_click to add javascript to tr tag.
Use optional data/field to replace keys as labels.

## Class

Set data/class/table to add more classes along with default table class.

Bootstrap 4 classes.

```
table-sm table-striped table-dark table-bordered table-hover table-borderless
```

## Render many (ajax)

```
type: widget
data:
  plugin: wf/table
  method: render_many
  data:
    id: _my_table_id_
    class:
      table: table-sm table-striped
    field:
      name: Name
      city: City
    datatable:
      ajax: /city/data
      order:
        -
          - 0
          - asc
```

### Ajax data

Check plugin DatatableDatatable_1_10_18 howto render json data. 

## Render many (complete table)

```
type: widget
data:
  plugin: wf/table
  method: render_many
  data:
    i18n: false
    field:
      x: Letter X
      z:
        text: Letter Z
        tr_attribute:
          style: 'background:red'
        th_attribute:
          style: 'background:red'
        td_attribute:
          style: 'background:red'
          id: _my_td_id_
    rs:
      -
        x: 1
        y: 2
        row_click: alert('clicked')
        row_id: row_1
        row_attribute:
          data-id: 1234
        row_settings:
          enabled: true
      -
        x: 11
        y: 22
        row_click: alert('clicked')
        row_id: row_2
    element:
      x:
        -
          type: span
          innerHTML: wf_table:x
    datatable:
      _disabled: Must be set to false to render Datatable.
      disabled: false
      _export: If not json.
      export:
        _disabled: Set to true to not using export.
        disabled: false
        _title: Set a title to override plugin title.
        title: My custom title.
      searching:
        _disabled: true
      _order: If not json.
      order:
        -
          - 0
          - desc
      _json: Optional (check Datatable manual).
      json:
        paging: true
        iDisplayLength : 25
        ordering: true
        info: true
        searching: true
        order:
          -
            - 0
            - desc
        language:
          url: /plugin/datatable/datatable_1_10_18/i18n/Swedish.json
    class:
      table: table-sm table-striped table-hover
    element:
      after:
        -
          type: div
          attribute:
            style: 
              height: 70px
          innerHTML:
```

Use optional element param to render an element.

### Row settings
Using param row_settings to add it as element settings param.

### TR attribute
Each tr tag in table an id attribute is added from rs param row_id. If not provided in rs parameter it will be each key in the array.

### TD attribute
Attribute id is added to td element if not provided.

### Element after
Set param data/element/after to render element after table.

### Known issues
If param data/rs has array keys with slash (/) there will be extra rows added. Therefore one has to replace such content before.
```
wfPlugin::includeonce('wf/table');
$rs = PluginWfTable::handle_array_keys($rs);
```

#### Update cell
```
document.querySelector("#_my_table_id_ #row_1 #_my_td_id_").innerHTML='Zebra';
```

### Datatable
Datatable is loaded if param disabled is not true. One could use json param to modify how it behaves.


## Render one

```
type: widget
data:
  plugin: wf/table
  method: render_one
  data:
    key_is_missing_alert: true
    i18n: false
    field:
      x: X
      y: Y
      z:
        text: Z
        th_attribute:
          style: 'background:red'
        td_attribute:
          style: 'background:red'
    rs:
      x: 1
      y: 2
      z: 3
    class:
      table: table-sm table-striped
```


