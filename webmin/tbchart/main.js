$(function () {

    var dataTable;

    var tableHeight = function () {
        var $tr = $('.dataTables_scrollHeadInner thead tr');
        return $(window).height() - 4 - ($tr.length ? $tr.height() : 0);
    };

    // Change height to match window
    var onResize = function () {
        var oSettings = dataTable.fnSettings();
        oSettings.oScroll.sY = tableHeight(); 
        dataTable.fnDraw();
    };

    var assignScrollHandlers = function () {
        var $table = $('#DataTables_Table_0_wrapper'),
            $scrollBody = $('.dataTables_scrollBody'),
            $scrollHeader = $('.dataTables_scrollHead'),
            $scrollColumn = $('.DTFC_LeftBodyWrapper');

        $('.DTFC_LeftBodyWrapper td, .dataTables_scrollHeadInner th').on('click', function (e) {

            var $target = $(e.target).closest('td,th'),
                axis = $target.is('td') ? 'x' : 'y',
                $parent = $target.parent(), // the row
                index,
                $tds,
                $scroll = null;

            if (axis === 'x') {
                // index of the row within the tbody
                index = $parent.parent().find('tr').index($parent);
                // All tds in that row
                $tds = $scrollBody.find('tr:nth-child(' + (index + 1) + ')').find('td');
            } else {
                // index of the th within the header row
                index = $parent.find('th').index($target);
                // All tds in this column
                $tds = $scrollBody.find('td:nth-child(' + (index + 1) + ')');
            }

            for (var i = 0, m = $tds.length; i < m; i++) {
                if ($tds.eq(i).find('span').length) {
                    $scroll = $tds.eq(i);
                    break;
                }
            }

            if ($scroll) {
                $scrollBody.scrollTo( $scroll, { duration:500, axis: axis});
            }
        });
    };

    var onFirstDraw = _.once(function () {
        $('.loading').hide();
        $('.table-container').addClass('show-table');
        onResize();
        assignScrollHandlers();
    });

    dataTable = $('.table-chart').dataTable({
        sDom: 'frtiS',
        sScrollY: tableHeight(),
        sScrollX: '100%',
        bAutoWidth: false,
        bScrollCollapse: true,
        bPaginate: false,
        bFilter: false,
        bInfo: false,
        bSort: false,
        bDeferRender: true,
        oScroller: {
            rowHeight: 29
        }
    });

    new FixedColumns(dataTable, {
        iLeftWidth: 85,
		iLeftColumns: 2,
        fnDrawCallback: onFirstDraw
    });

    dataTable = $('.reservation-list').dataTable({
        sDom: 'frtiS',
        sScrollY: tableHeight(),
        sScrollX: '100%',
        bAutoWidth: false,
        bScrollCollapse: true,
        bPaginate: false,
        bFilter: false,
        bInfo: false,
        bSort: false,
        bDeferRender: true,
        oScroller: {
            rowHeight: 29
        }
    });

    new FixedColumns(dataTable, {
        iLeftWidth: 200,
		iLeftColumns: 4,
        fnDrawCallback: onFirstDraw
    });


    $(window).resize(onResize);
});