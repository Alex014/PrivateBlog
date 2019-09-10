<div class='row'>
<div class='col-md-12'>
    
<div class='panel panel-default' style="margin-top: 60px;">
    <div class='panel-body'>

        <form method="post" id="form">

  <div class="form-group">
      <label for="days">Days <small><?=$post['days']?> + days</small></label>
      <input class="form-control" name="days" id="days" validate="required|number" value="1">
  </div>

  <div class="form-group">
      <label for="Created">Created (GMT)</label>
      <input class="form-control" id="Created" value="<?=$post['created']?> ( Expires: <?=$post['expires']?> )" readonly="">
  </div>
    
  <div class="form-group">
    <label for="name">Post name</label>
    <input class="form-control" name="name" id="name" validate="required" value="<?=$post['name']?>">
  </div>
    
    
  <div class="form-group">
    <label for="title">Title <small>(title of a post (optional))</small></label>
    <input class="form-control" name="title" id="title" value="<?=$post['title']?>">
  </div>
  <div class="form-group">
      <label for="lang">Language <small>(the ISO_639-1 code of post language (optional, default en))</small></label>
    <input class="form-control" name="lang" id="lang" value="<?=$post['lang']?>">
  </div>
  <div class="form-group">
        <label for="username">Username <small>(username of blogger (optional))</small></label>
        <div id="containerSelect"></div>
        <input class="form-control" name="username" id="username" value="<?=$post['username']?>">
  </div>
  <div class="form-group">
      <label for="sig">Signature <small>((optional used with Username) The result of signmessage "emercoinaddress" "username:postname" command, where emercoinaddress is @key from user's record and postname is this post name from blog:postname. This signature gets verified by verifymessage "emercoinaddress" "@sig" "username:postname" command)</small></label>
    <input class="form-control" name="sig" id="sig" value="<?=$post['sig']?>">
  </div>
  <div class="form-group">
      <label for="keywords">Keywords <small>("drugs,sex,rockandroll" (optional))</small></label>
    <input class="form-control" name="keywords" id="keywords" value="<?=$post['keywords']?>">
  </div>
  <div class="form-group">
      <label for="reply">Reply <small>(the name of the post you want to reply to (optional))</small></label>
    <input class="form-control" name="reply" id="reply" value="<?=$post['reply']?>">
  </div>
    
  <div class="form-group" style='background: white;'>
      <style>
          span {
              color: black;
          }
      </style>
    <label for="content" style='color: black;'>Post content  <a href="#" type="submit" class="btn btn-info" id="editor" data-enabled="0">EDITOR</a> </label>
    <textarea class="form-control editor" name="content" id="content" style="width: 100%; height: 400px;" validate="required"><?=$post['content']?></textarea>
  </div>

    <input class="form-control" name="action" id="action" value="edit" type="hidden">
  
  <br/>
  <br/>
  <br/>
  <br/>
        
</form>
    </div>
</div>

</div>
</div>

<?if($post['expired']):?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  You can't update or delete this post<br>
  The record is expired for <b><?=$post['_days']?></b> days
  You have to make the new record
</div>
<?else:?>
    <button type="submit" class="btn btn-info" id="do_edit">UPDATE</button>
    <button type="submit" class="btn btn-danger" id="do_delete">DELETE</button>
  
  <br/>
  <br/>
  <br/>
  <br/>
<?endif;?>

        <?if(PHAR):?>
        <style>
        <?php
        echo file_get_contents(__DIR__.'/../../css/simplemde.min.css');
        ?>
        </style>
        <script type='text/javascript'>
        <?php
        echo file_get_contents(__DIR__.'/../../js/simplemde.min.js');
        ?>
        </script>
        <?else:?>
        
<!-- include summernote css/js -->
<link href="/css/simplemde.min.css" rel="stylesheet">
<script src="/js/simplemde.min.js"></script>
        
        <?endif;?>


<script type="text/javascript">
$.getJSON("/me/bloggers.php", {action: 'bloggers'}, function(data) {
    users = data;
    
    var html = "<select id='bloggers'>";
    html += "<option value=''> [Anonymous] </option>";
    for(var i in data) {
        if(data[i].key != null)
        html += "<option value='"+i+"'>"+data[i].name+"</option>";
    }
    html += "</select>";
    $('#containerSelect').html(html)
    
    var username = $('#username').val();
    //console.log(username)
    if(username != '') {
        var selected = '';
        
        for(var i in data) {
            if(data[i].name == username)
                selected = i;
        }
        
        if(selected != '') $('#bloggers').val(selected);
    }
    
    //$('#bloggers').trigger('change');
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
    else {
        $('#username').val('');
        $('#sig').val('')
    }
})

$(document).on('change', '#name', function() {
    $('#bloggers').trigger('change');
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

$(document).ready(function() {
    $('#form').bt_validate();
})


$(document).on('click', '#editor', function() {
    if($(this).data('enabled') == '1') {
        //$('.editor').summernote('destroy');
        simplemde.toTextArea();
        simplemde = null;
        $(this).data('enabled', '0')
    }
    else {
        /*$('.editor').summernote({height: 200,  toolbar: [
            // [groupName, [list of button]]
            ['style', ['style', 'bold', 'italic', 'underline']],
            ['misc', ['undo','redo']],
            ['font', ['fontsize','color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['other', ['link', 'hr','table','picture']],
            ['screen', ['codeview', 'fullscreen']]
          ]});*/
        
        simplemde = new SimpleMDE({ 
            toolbar: ["bold", "italic", "strikethrough", "heading", "|", "quote", "code", "horizontal-rule", "|", 
                "unordered-list", "ordered-list", "|", "link", "image", "|",
                "preview", "side-by-side", "fullscreen", "|", "guide"],
            element: $(".editor")[0] 
        });
        $(this).data('enabled', '1')
    }
    
    return false;
})
</script>