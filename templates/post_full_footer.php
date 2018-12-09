<script type="text/javascript">
$(document).on('click', '.expand', function() {
    if($(this).data('visible') == '1') {
        $('#content_'+$(this).data('id')).slideUp()
        $('#replies_'+$(this).data('id')).slideUp()
        $(this).data('visible', '0')
        $(this).html("<span class='glyphicon glyphicon-plus'/>")
    }
    else {
        $('#content_'+$(this).data('id')).slideDown()
        $('#replies_'+$(this).data('id')).slideDown()
        $(this).data('visible', '1')
        $(this).html("<span class='glyphicon glyphicon-minus'/>")
    }
    
    return false;
})
</script>