# Buto-Plugin-WfTable
Widgets to render array data in tables.



Render data in table.
Set data/i18n to avoid translation for tbody. 
Use param row_click to add javascript to tr tag.
Use optional data/field to replace keys as labels.


## Render many

```
type: widget
data:
  plugin: wf/table
  method: render_many
  data:
    i18n: false
    rs:
      -
        x: 1
        y: 2
        row_click: alert('clicked')
      -
        x: 11
        y: 22
        row_click: alert('clicked')
    field:
      x: Letter X
      z: Zzz
    datatable:
      disabled: false
```

## Render one

```
type: widget
data:
  plugin: wf/table
  method: render_one
  data:
    i18n: false
    rs:
      x: 1
      y: 2
      z: 3
    field:
      x: X
```


