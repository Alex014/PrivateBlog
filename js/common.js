$(document).ready(function() {
    $('.__sync').click(function() {
        var self = this;
        $(self).text('Working ...');
        
        $.ajax({
            type: 'GET',
            url: '/sync.php',
            dataType: 'text',
            timeout: 3600,
        }).done(function(result) {
            $(self).text('DONE !');
            setTimeout(function() {
                if(result.trim() == 'refused') {
                    alert('Connection refused !\nDid you run the Emercoin wallet ?');
                    $(self).text('SYNC');
                }
                else if(result.trim() == 'OK') {
                    window.location.reload();
                }
                else {
                    text = 'Internal error (maybe blockchain is not synced) \n\n'
                    text += result
                    alert(text);
                }
            }, 100)
        })
        .fail(function() {
            alert('Timeout error');
        })
        
        /*
        $.get('/sync.php', function(result) {
            $(self).text('DONE !');
            setTimeout(function() {
                if(result.trim() == 'refused') {
                    alert('Connection refused !\nDid you run the Emercoin wallet ?');
                    $(self).text('SYNC');
                }
                else if(result.trim() == 'OK') {
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
        })*/
    });
})