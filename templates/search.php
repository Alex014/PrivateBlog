<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Search</h3>
            </div>
            <div class="panel-body">
                
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" <?if($posted == 'title'):?>class="active"<?endif;?>><a <?if($posted == 'title'):?>style="font-weight: bold;"<?endif;?> href="#title" aria-controls="home" role="tab" data-toggle="tab">Title</a></li>
    <li role="presentation" <?if($posted == 'words'):?>class="active"<?endif;?>><a <?if($posted == 'words'):?>style="font-weight: bold;"<?endif;?> href="#words" aria-controls="profile" role="tab" data-toggle="tab">Words</a></li>
    <li role="presentation" <?if($posted == 'regexp'):?>class="active"<?endif;?>><a <?if($posted == 'regexp'):?>style="font-weight: bold;"<?endif;?> href="#regexp" aria-controls="messages" role="tab" data-toggle="tab">Regexp</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane <?if($posted == 'title'):?>active<?endif;?>" id="title">
          <br>
          <form method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="title" placeholder="Search by name and title ..." value='<?=fvalue('title')?>'>
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Search</button>
              </span>
            </div>
          </form>
      </div>
      <div role="tabpanel" class="tab-pane <?if($posted == 'words'):?>active<?endif;?>" id="words">
          <br>
                    <form method="post">
          <div class="row">
            <div class="col-md-6">
                      <div class="form-group">
                        <label for="allwords1">All words #1</label>
                        <input type="text" class="form-control" id="allwords1" placeholder="Search by name and title ..." name="allwords[]" value='<?=fvalue('allwords', 0)?>'>
                      </div>
                      <div class="form-group">
                        <label for="allwords2">All words #2</label>
                        <input type="text" class="form-control" id="allwords2" placeholder="Search by name and title ..." name="allwords[]" value='<?=fvalue('allwords', 1)?>'>
                      </div>
                      <div class="form-group">
                        <label for="allwords3">All words #3</label>
                        <input type="text" class="form-control" id="allwords3" placeholder="Search by name and title ..." name="allwords[]" value='<?=fvalue('allwords', 2)?>'>
                      </div>
            </div>
            <div class="col-md-6">
                      <div class="form-group">
                        <label for="anywords1">Any words #1</label>
                        <input type="text" class="form-control" id="anywords1" placeholder="Search by name and title ..." name="anywords[]" value='<?=fvalue('anywords', 0)?>'>
                      </div>
                      <div class="form-group">
                        <label for="anywords2">Any words #2</label>
                        <input type="text" class="form-control" id="anywords2" placeholder="Search by name and title ..." name="anywords[]" value='<?=fvalue('anywords', 1)?>'>
                      </div>
                      <div class="form-group">
                        <label for="anywords3">Any words #3</label>
                        <input type="text" class="form-control" id="anywords3" placeholder="Search by name and title ..." name="anywords[]" value='<?=fvalue('anywords', 2)?>'>
                      </div>
                      <button type="submit" class="btn btn-default" style="float: right;">Search</button>
            </div>
          </div>
                    </form>
      </div>
      <div role="tabpanel" class="tab-pane <?if($posted == 'regexp'):?>active<?endif;?>" id="regexp">
          <br>
          <form method="post">
            <div class="input-group">
              <input type="text" class="form-control" name="regexp" placeholder="Search by content and regular expression..." value='<?=fvalue('regexp')?>'>
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Search</button>
              </span>
            </div>
          </form>
      </div>
  </div>
                
            </div>
        </div>
    </div>
</div>

<?if(!empty($posts)):?>

<div class="row">
    <div class="col-md-2">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Keywords</h3>
            </div>
            <table class="table">
                <tbody>
                    <? foreach($keywords as $keyword): ?>
                    <tr>
                        <td><a href="/keywords.php?name=<?=$keyword['word']?>"> <?=$keyword['word']?> (<?=$keyword['posts']?>) </a> </td>
                    </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Posts</h3>
            </div>
            <?if(\darkblog\db\pager::$pages_count > 1):?>
            <div class="panel-body">
                Total <?=\darkblog\db\pager::$total?> posts
            </div>
            <?endif;?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Keywords</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($posts as $post): ?>
                    <tr>
                        <td> 
                          <?php if(!empty($post['title'])):  ?>
                          <a href="/post.php?name=<?=$post['name']?>"> <?=nl2br(strip_tags($post['title']))?> </a>
                          <?php else: ?>
                          <a href="/post.php?name=<?=$post['name']?>"> <?=nl2br(strip_tags($post['name']))?> </a>
                          <?php endif; ?>
                        </td>
                        <td><?=$post['keywords']?></td>
                    </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?if(\darkblog\db\pager::$pages_count > 1):?>
        <center>
        <ul class="pagination">
          <?if(isset(\darkblog\db\pager::$pages['prev'])):?><li><a href="<?=\darkblog\other\url::build(array('page' => $pages['prev']))?>">Prev</a></li><?endif;?>
          <?if(isset(\darkblog\db\pager::$pages['first'])):?><li><a href="<?=\darkblog\other\url::build(array('page' => $pages['first']))?>"><?=\darkblog\db\pager::$pages['first']?></a></li><?endif;?>
          <?foreach(\darkblog\db\pager::$pages['list'] as $p): ?>
            <li><a href="<?=\darkblog\other\url::build(array('page' => $p))?>" <?if($p == \darkblog\db\pager::$page):?>class="sel-page"<?endif;?>> <?if($p == \darkblog\db\pager::$page):?> <b> <?=$p?> </b> <?else:?> <?=$p?> <?endif;?> </a></li>
          <?endforeach;?>
          <?if(isset(\darkblog\db\pager::$pages['last'])):?><li><a href="<?=\darkblog\other\url::build(array('page' => \darkblog\db\pager::$pages['last']))?>"><?=\darkblog\db\pager::$pages['last']?></a></li><?endif;?>
          <?if(isset(\darkblog\db\pager::$pages['next'])):?><li><a href="<?=\darkblog\other\url::build(array('page' => \darkblog\db\pager::$pages['next']))?>">Next</a></li><?endif;?>
        </ul>
        </center>
        <?endif;?>
    </div>
</div>

<?elseif(count($_POST) > 0):?>
    <h3 class="panel-title">Nothing found</h3>
<?endif;?>