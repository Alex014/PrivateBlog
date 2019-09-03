<script type='text/javascript'>
filter = <?= json_encode($_SESSION['filter'])?>;
if(filter == null) filter = {};

do_search()

function do_search() {
    $.post('/me/posts_html.php', filter, function(html) {
        $('#container-main').html(html)
        apply_controls()
    })
}

function apply_controls() {
        if(filter.sort == 'ta')
            $('#order_title').addClass('glyphicon').removeClass('glyphicon-sort-by-alphabet-alt').addClass('glyphicon-sort-by-alphabet');
        else if(filter.sort == 'td')
            $('#order_title').addClass('glyphicon').removeClass('glyphicon-sort-by-alphabet').addClass('glyphicon-sort-by-alphabet-alt');
        else
            $('#order_title').removeClass('glyphicon').removeClass('glyphicon-sort-by-alphabet').removeClass('glyphicon-sort-by-alphabet-alt');

        if(filter.sort == 'xa')
            $('#order_expires_in').addClass('glyphicon').removeClass('glyphicon-sort-by-alphabet-alt').addClass('glyphicon-sort-by-alphabet');
        else if(filter.sort == 'xd')
            $('#order_expires_in').addClass('glyphicon').removeClass('glyphicon-sort-by-alphabet').addClass('glyphicon-sort-by-alphabet-alt');
        else
            $('#order_expires_in').removeClass('glyphicon').removeClass('glyphicon-sort-by-alphabet').removeClass('glyphicon-sort-by-alphabet-alt');

        if(filter.expired != null && parseInt(filter.expired) == 1)
            $('#expired').prop('checked',true)
        else
            $('#expired').prop('checked',false)

        if(filter.search != null)
            $('#search').val(filter.search)
}


$(document).on('click', '#order_title', function() {
    if($(this).hasClass('glyphicon-sort-by-alphabet')) {
        filter.sort = 'td'
    }
    else {
        filter.sort = 'ta'
    }
    
    do_search()
})

$(document).on('click', '#order_expires_in', function() {
    if($(this).hasClass('glyphicon-sort-by-alphabet')) {
        filter.sort = 'xd'
    }
    else {
        filter.sort = 'xa'
    }
    
    do_search()
})

$(document).on('click', '#do-search', function() {
    filter.search = $('#search').val()
    
    if($('#expired').prop('checked'))
        filter.expired = 1
    else
        filter.expired = 0
    
    do_search()
})
</script>