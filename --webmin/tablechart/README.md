# Fixed header and column datatables

Uses [jQuery datatables](https://datatables.net/) to make a responsive datatable with a fixed header and a fixed column.

## [Demo](http://lukekarrys.github.io/fixed-header-column-table/demo.html)

This is mainly to show an example that was a bit difficult to handle a few key areas:
- Vertical resizing
- Not making columns too small
- Scrolling within the table

### Features
- Regular markup
- Styled with Bootstrap 2.3.2
- Scrolls to first non-empty cell when you click header or left column
- Resizes height and width on browser resize

### Caveats
- Can take a bit to render large tables (by default table is hidden until it is full rendered)
- Resizing is slow (but it gets there)