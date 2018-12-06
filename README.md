# Buto-Plugin-WfTable
Widgets to render array data in tables.



Render data in table.
Set data/i18n to avoid translation for tbody.

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



