$(document).ready(function() {
    $('#sync').click(function() {
        if(confirm('Sync with blockchain now ?')) {
            $.get('/sync.php', function(result) {
                if(result.trim() == 'OK') {
                    window.location.reload();
                }
                else {
                    alert(result);
                }
            })
        }
    })
})