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
    <input class="form-control" name="reply" id="reply" value="<?=$__reply?>">
  </div>
    
  <div class="form-group">
    <label for="content">Post content  <a href="#" type="submit" class="btn btn-info" id="editor" data-enabled="1">EDITOR</a> </label>
    <textarea class="form-control summernote" name="content" id="content" style="width: 100%; height: 400px;" validate="required"></textarea>
  </div>

  <button type="submit" class="btn btn-default">Submit</button>
  
  <br/>
  <br/>
  <br/>
  <br/>
</form>

<!-- include summernote css/js -->
<link href="/css/summernote.css" rel="stylesheet">
<script src="/js/summernote.min.js"></script>


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

function summernote() {
    $('.summernote').summernote({height: 200,  toolbar: [
        // [groupName, [list of button]]
        ['style', ['style', 'bold', 'italic', 'underline']],
        ['misc', ['undo','redo']],
        ['font', ['fontsize','color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['other', ['link', 'hr','table','picture']],
        ['screen', ['codeview', 'fullscreen']]
      ]});
}

$(document).ready(function() {
    summernote()
    $('#form').bt_validate();
})


$(document).on('click', '#editor', function() {
    if($(this).data('enabled') == '1') {
        $('.summernote').summernote('destroy');
        $(this).data('enabled', '0')
    }
    else {
        summernote()
        $(this).data('enabled', '1')
    }
    
    return false;
})
</script>