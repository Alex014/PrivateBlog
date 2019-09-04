$(document).ready(function() {
    $('.__sync').click(function() {
        var self = this;
        $(self).text('Working ...');
        $.get('/sync.php', function(result) {
            $(self).text('DONE !');
            setTimeout(function() {
                if(result.trim() == 'OK') {
                    window.location.reload();
                }
                else {
                    text = 'Internal error (maybe blockchain is not synced) \n\n'
                    text += result
                    alert(text);
                }
            }, 100)

        }).fail(function() {
            alert('Internal error (maybe blockchain is not synced)');
        })
    });
})