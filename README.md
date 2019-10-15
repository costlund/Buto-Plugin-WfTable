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
    rs:
      -
        x: 1
        y: 2
        row_click: alert('clicked')
      -
        x: 11
        y: 22
        row_click: alert('clicked')
    element:
      x:
        -
          type: span
          innerHTML: wf_table:x
    datatable:
      disabled: false
    class:
      table: table-sm table-striped table-hover
```

Use optional element param to render an element.

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


