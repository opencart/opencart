$('#button-search').bind('click', function() {
    url = 'index.php?route=cms/topic&language={{ language }}';

    var search = $('#input-search').val();

    if (search) {
        url += '&search=' + encodeURIComponent(search);
    }

    var topic_id = $('#input-topic').prop('value');

    if (topic_id > 0) {
        url += '&topic_id=' + topic_id;
    }

    location = url;
});

$('#input-search').bind('keydown', function(e) {
    if (e.keyCode == 13) {
        $('#button-search').trigger('click');
    }
});