// Set body text to small
$('body').addClass('text-sm')

//Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
$.widget.bridge('uibutton', $.ui.button)

$('.application-date-picker').datetimepicker({
    format: 'L'
});

// Get selected rows
function getSelectedIds(gridSelector) {
    let keys = $(gridSelector).yiiGridView('getSelectedRows');
    let ids = [];
    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');

        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    return [...new Set(ids)]
}
