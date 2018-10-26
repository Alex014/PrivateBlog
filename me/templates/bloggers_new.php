<form method="post" id="form" style="padding-top: 60px;">

  <div class="form-group">
      <label for="days">Days <small>(if the record will expire, you won't be able to edit or delete it)</small></label>
      <input class="form-control" name="days" id="days" validate="required|number" value="1">
  </div>

  <div class="form-group">
      <label for="username">Username <small>(username of blogger)</small></label>
    <input class="form-control" name="username" id="username" validate="required">
  </div>

  <div class="form-group">
        <label for="userkey">Key</label>
        <div id="containerSelect"></div>
        <input class="form-control" name="userkey" id="userkey">
  </div>
    
  <div class="form-group">
      <label for="sig">Signature <small>((optional) the bloggers signature, result of signmessage "emercoinaddress" "username")</small></label>
    <input class="form-control" name="sig" id="sig">
  </div>
    
  <div class="form-group">
    <label for="content">Description <small>(optional)</small></label>
    <textarea class="form-control" name="content" id="content" style="width: 100%; height: 200px;"></textarea>
  </div>

    <input class="form-control" name="action" value="new" type="hidden">
        
  <button type="submit" class="btn btn-default">Submit</button>
</form>

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
</script>