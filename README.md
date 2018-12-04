# Buto-Plugin-WfTable
Widgets to render array data in tables.



Render data in table.

```
type: widget
data:
  plugin: wf/table
  method: render_many
  data:
    rs:
      -
        x: 1
        y: 2
        z: 3
      -
        x: 11
        y: 22
        z: 33
    field:
      x: Letter X
      z: Zzz
    datatable:
      disabled: false
```



