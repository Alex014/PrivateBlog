<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">            
            <div class="panel-heading">
              <h3 class="panel-title">Config</h3>
            </div>
            <div class="panel-body">
                
                <form method="post">
                    
  <div class="form-group">
    <label for="lang" class="col-sm-2 control-label">Language</label>
    <div class="col-sm-10">
        <select class="form-control" id="lang" name="lang">
          <option value="">None</option>
          <?foreach($languages as $lng):?>
            <option value="<?=$lng['id']?>" <? if($lng['id'] == $lang): ?> SELECTED="" <? endif; ?> ><?=$lng['name']?></option>
          <?endforeach;?>
        </select>
    </div>
  </div>
<br><br>
  <div class="form-group">
    <label for="records" class="col-sm-2 control-label">Records in 1 page</label>
    <div class="col-sm-10">
        <input class="form-control" name="records" id="records" value="<?=$records?>">
    </div>
  </div>
<br><br>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Save</button>
    </div>
  </div>
                    
                </form>
                
            </div>
        </div>
    </div>
</div>