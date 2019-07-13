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
                    alert(result);
                }
            }, 100)

        })
    });
})