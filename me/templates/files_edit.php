<div class='row'>
<div class='col-md-12'>
    
<div class='panel panel-default' style="margin-top: 60px;">
    <div class='panel-body'>
<form method="post" id="form">

  <div class="form-group">
      <label for="days">Days  <small><?=$file['days']?> + days</small></label>
      <input class="form-control" name="days" id="days"  validate="required|number" value="1">
  </div>

  <div class="form-group">
        <label for="filename">Filename</label>
        <input class="form-control" name="filename" id="filename" validate="required" value="<?=$file['name']?>">
  </div>
    
  <div class="form-group">
    <label for="content">Content</label>
    <textarea class="form-control" name="content" id="content" style="width: 100%; height: 200px;"><?=$file['content']?></textarea>
  </div>

    <input class="form-control" name="action" id='action' value="edit" type="hidden">
    
</form>
    </div>
</div>

</div>
</div>


<?if($file['expired']):?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  You can't update or delete this file<br>
  The record is expired for <b><?=$file['_days']?></b> days
  You have to make the new record
</div>
<?else:?>
    <button type="submit" class="btn btn-info" id="do_edit">UPDATE</button>
    <button type="submit" class="btn btn-danger" id="do_delete">DELETE</button>
<?endif;?>

<script type="text/javascript">
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
