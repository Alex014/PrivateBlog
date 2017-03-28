<div class="row" style="padding-top: 60px;">
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav nav-pills">
                    <li role="presentation"><a href='#'> <b> <?=$keyword['word']?> </b> </a></li>
                    <li role="presentation"><a href="?name=<?=$keyword['word']?>&mode=posts">Posts</a></li>
                    <li role="presentation" class="active"><a href="?name=<?=$keyword['word']?>&mode=bloggers">Bloggers</a></li>
                    <li role="presentation"><a href="?name=<?=$keyword['word']?>&mode=keywords">Keywords</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Bloggers used with keyword "<?=$keyword['word']?>"</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th> <a href='<?=\darkblog\other\url::build(array('order' => 'username'))?>'>Username</a> </th>
                        <th>Description</th>
                        <th> <a href='<?=\darkblog\other\url::build(array('order' => 'total'))?>'>Posts (total)</a> </th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($keyword['users'] as $user): ?>
                    <tr>
                        <td> <a href="/bloggers.php?name=<?=$user['username']?>"> <?=nl2br(strip_tags($user['username']))?> </a> </td>
                        <td><?=nl2br(strip_tags($user['descr']))?></td>
                        <td><?=$user['posts']?></td>
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