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

## Render many


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
      disabled: false
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
          url: /plugin/datatable/datatable_1_10_16/i18n/Swedish.json
    class:
      table: table-sm table-striped table-hover
```

Use optional element param to render an element.

### TR attribute
Each tr tag in table an id attribute is added from rs param row_id. If not provided in rs parameter it will be each key in the array.

### TD attribute
Attribute id is added to td element if not provided.

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


