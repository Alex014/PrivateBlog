<div class='row'>
<div class='col-md-12'>
    
<div class='panel panel-default' style="margin-top: 60px;">
    <div class='panel-body'>
<form method="post" enctype='multipart/form-data' id="form">

  <div class="form-group">
      <label for="days">Days <small>(if the record will expire, you won't be able to edit or delete it)</small></label>
      <input class="form-control" name="days" id="days" validate="required|number" value="1">
  </div>

  <div class="form-group">
        <label for="name">Filename</label>
        <input class="form-control" name="name" id="name" validate="required" value="filename">
  </div>
    
  <div class="form-group">
    <label for="file">FILE</label>
    <input class="form-control" name="file" id="file" type='file'>
  </div>

    <input class="form-control" name="action" value="new" type="hidden">
        
  <button type="submit" class="btn btn-default">Upload</button>
</form>
    </div>
</div>

</div>
</div>
