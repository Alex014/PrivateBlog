<div class='row'>
<div class='col-md-12'>
    
<div class='panel panel-default' style="margin-top: 60px;">
    <div class='panel-body'>

<form method="post" id="form">

  <div class="form-group">
      <label for="days">Days <small><?=$blogger['days']?> + days</small></label>
      <input class="form-control" name="days" id="days" validate="required|number" value="1">
  </div>

  <div class="form-group">
      <label for="Created">Created (GMT)</label>
      <input class="form-control" id="Created" value="<?=$blogger['created']?> ( Expires: <?=$blogger['expires']?> )" readonly="">
  </div>

  <div class="form-group">
        <label for="username">Username <small>(username of blogger)</small></label>
        <input class="form-control" name="username" id="username" validate="required" value="<?=$blogger['username']?>">
  </div>

  <div class="form-group">
        <label for="userkey">Key</label>
        <div id="containerSelect"></div>
        <input class="form-control" name="userkey" id="userkey" value="<?=$blogger['key']?>">
  </div>
    
  <div class="form-group">
        <label for="sig">Signature <small>((optional) the bloggers signature, result of signmessage "emercoinaddress" "username")</small></label>
        <input class="form-control" name="sig" id="sig" value="<?=$blogger['sig']?>">
  </div>
    
  <div class="form-group">
    <label for="content">Description <small>(optional)</small></label>
    <textarea class="form-control" name="content" id="content" style="width: 100%; height: 200px;"  value=""><?=$blogger['content']?></textarea>
  </div>

    <input class="form-control" name="action" id="action" value="edit" type="hidden">
        
</form>
    </div>
</div>

</div>
</div>

<?if($post['expired']):?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  You can't update or delete this blogger<br>
  The record is expired for <b><?=$blogger['_days']?></b> days
  You have to make the new record
</div>
<?else:?>
    <button type="submit" class="btn btn-info" id="do_edit">UPDATE</button>
    <button type="submit" class="btn btn-danger" id="do_delete">DELETE</button>
<?endif;?>

<script type="text/javascript">
$.getJSON("/me/bloggers.php", {action: 'keys'}, function(data) {

    var html = "<select id='keys'>";
    for(var i in data) {
        html += "<optgroup label='"+i+"'>";
        for(var j in data[i]) {
            html += "<option value='"+data[i][j]+"'>"+data[i][j]+"</option>";
        }
        html += "</optgroup>";
    }
    html += "</select>";
    $('#containerSelect').html(html)
    
    var userkey = $('#userkey').val();
    //console.log(userkey)
    if(userkey != '') {
        var selected = '';
        
        for(var i in data) {
            for(var j in data[i]) {
                if(data[i][j] == userkey)
                    selected = data[i][j];
            }
        }
        //console.log(selected)
        if(selected != '') $('#keys').val(selected);
    }
    
    $('#keys').trigger('change');
})

$(document).on('change', '#keys', function() {
    $('#userkey').val(this.value)
})

$(document).on('change', '#keys', function() {
    var username = $('#username').val();
    var userkey = $('#userkey').val();
    if(username != '' && userkey != '') {
        //console.log(postname, userkey)
        $.post("/me/bloggers.php", {action: 'signblogger', userkey: userkey, username: username}, function(sig) {
            //console.log(sig)
            $('#sig').val(sig)
        })
    }
})

$(document).on('change', '#username', function() {
    $('#keys').trigger('change');
})

$(document).ready(function() {
     $('#form').bt_validate();
})

$(document).on('click', '#do_edit', function() {
    $('#action').val('edit')
    $('#form').submit();
})

$(document).on('click', '#do_delete', function() {
    if(confirm('Confirm delete')) {
        $('#action').val('delete')
        $('#form').submit();
    }
})
</script>