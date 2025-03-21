# Buto-Plugin-WfTable
Widgets to render array data in tables.



Render data in table.
Set data/i18n to avoid translation for tbody. 
Set data/i18n_columns to avoid translation for columns. 
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
      onclick: console.log
      order:
        -
          - 0
          - asc
```
Hide a column.
Consider Local Storage when edit this.
```
      name:
        text: Name
        visible: false
```


### Ajax data

Check plugin DatatableDatatable_1_10_18 howto render json data. 

### Ajax url
One could replacew request params like this exemple.
```
      ajax: /city/data/year/[year]
```

### onclick
If this is set row data will be passed into function.
```
      onclick: console.log
```

## Render many (complete table)

```
type: widget
data:
  plugin: wf/table
  method: render_many
  data:
    i18n: false
    i18n_columns: true
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
        processing: true
        paging: true
        iDisplayLength : 25
        ordering: true
        info: true
        searching: true
        order:
          -
            - 0
            - desc
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
Simplified data.
```
type: widget
data:
  plugin: wf/table
  method: render_many
  data:
    i18n: false
    field:
      x: X
      y: Y
    rs:
      -
        x: 1
        y: 2
      -
        x: 11
        y: 22
        row_click: alert('clicked')
        row_id: row_2
    datatable:
      disabled: false
      export:
        disabled: false
        title: My custom title.
      searching:
      order:
        -
          - 0
          - desc
```

Add settings example.
```
    table:
      settings:
        globals:
          -
            path_to_key: 'settings/plugin/i18n/translate_v1/settings/path'
            value: '/plugin/_/_/i18n'
```

Use optional element param to render an element.

### Table style
Add style to table.
```
    style: 'font-size:smaller;'
```

### Row settings
Using param row_settings to add it as element settings param.

### Row cursor
Default row cursor is "default". Change to pointer if table should be clickable.
```
    row:
      cursor: pointer
```

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
If field keys has / there will be ussues. One has to replace them to #.
```
type: widget
data:
  plugin: wf/table
  method: render_many
  data:
    field:
      information#date: Date
      track#code: Bana
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
        i18n: true
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


