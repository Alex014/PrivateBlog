<form method="post" id="form" style="padding-top: 60px;">

  <div class="form-group">
      <label for="days">Days <small>(if the record will expire, you won't be able to edit or delete it)</small></label>
      <input class="form-control" name="days" id="days" validate="required|number" value="1">
  </div>
    
  <div class="form-group">
    <label for="name">Post name</label>
    <input class="form-control" name="name" id="name" validate="required">
  </div>
    
    
  <div class="form-group">
    <label for="title">Title <small>(title of a post (optional))</small></label>
    <input class="form-control" name="title" id="title">
  </div>
  <div class="form-group">
      <label for="lang">Language <small>(the ISO_639-1 code of post language (optional, default en))</small></label>
    <input class="form-control" name="lang" id="lang">
  </div>
  <div class="form-group">
        <label for="username">Username <small>(username of blogger (optional))</small></label>
        <div id="containerSelect"></div>
        <input class="form-control" name="username" id="username">
  </div>
  <div class="form-group">
      <label for="sig">Signature <small>((optional used with Username) The result of signmessage "emercoinaddress" "username:postname" command, where emercoinaddress is @key from user's record and postname is this post name from blog:postname. This signature gets verified by verifymessage "emercoinaddress" "@sig" "username:postname" command)</small></label>
    <input class="form-control" name="sig" id="sig">
  </div>
  <div class="form-group">
      <label for="keywords">Keywords <small>("drugs,sex,rockandroll" (optional))</small></label>
    <input class="form-control" name="keywords" id="keywords">
  </div>
  <div class="form-group">
      <label for="reply">Reply <small>(the name of the post you want to reply to (optional))</small></label>
    <input class="form-control" name="reply" id="reply">
  </div>
    
  <div class="form-group">
    <label for="content">Post content</label>
    <textarea class="form-control" name="content" id="content" style="width: 100%; height: 400px;" validate="required"></textarea>
  </div>

  <button type="submit" class="btn btn-default">Submit</button>
</form>


<script type="text/javascript">
$.getJSON("/me/bloggers.php", {action: 'bloggers'}, function(data) {
    users = data;
    
    var html = "<select id='bloggers'>";
    for(var i in data) {
        if(data[i].key != null)
        html += "<option value='"+i+"'>"+data[i].name+"</option>";
    }
    html += "</select>";
    $('#containerSelect').html(html)
    
    $('#bloggers').trigger('change');
})

$(document).on('change', '#bloggers', function() {
    var postname = $('#name').val();
    if(postname != '') {
        var username = users[this.value].name;
        var userkey = users[this.value].key;
        $('#username').val(username);
        //console.log(postname, userkey)
        $.post("/me/posts.php", {action: 'signpost', postname: postname, userkey: userkey, username: username}, function(sig) {
            //console.log(sig)
            $('#sig').val(sig)
        })
    }
})

$(document).on('change', '#name', function() {
    $('#bloggers').trigger('change');
})

$(document).ready(function() {
     $('#form').bt_validate();
})
</script>