function showDialog(win, link) {
    $(win).dialog({
        bgiframe: true,
        resizable: false,
        height: 160,
        width: 500,
        modal: true,
        position: 'center',
        overlay: {
            backgroundColor: '#000',
            opacity: 0.5
        },
        buttons: {
            'Bevestigen': function() {
                window.location = link;
                $(this).dialog('close');
            },
            'Annuleren': function() {
                $(this).dialog('destroy');
            }
        }
    });
}

function showDelete(title, link) {
    var url = '<a href="' + link + '" class="btn btn-danger"><i class="ti-trash"></i> Verwijderen</a>'
    $('#url').append(url);
}