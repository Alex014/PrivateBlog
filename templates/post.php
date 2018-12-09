<? if(!empty($post['id'])): ?>
<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?=nl2br(strip_tags($post['title']))?></h3>
              <? if(!empty($post['replies'])): ?>
              <a href="/post.php?name=<?=$post['name']?>&full" style="position: absolute; right: 32px; top: 10px;"> [Show Full] </a>
              <? endif; ?>
            </div>
            <div class="panel-body">
                <?=nl2br(trim($post['content']))?>
            </div>
        </div>
    </div>
</div>
<?else:?>
<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Post not found</h3>
            </div>
            <div class="panel-body">
                EMPTY
            </div>
        </div>
    </div>
</div>
<? endif; ?>

<? if(!empty($post['keywords'])): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Keywords</h3>
            </div>
            <div class="panel-body">
                <? foreach($post['keywords'] as $keyword): ?>
                    <a href="/keywords.php?name=<?=$keyword['word']?>"> <?=$keyword['word']?> (<?=$keyword['posts']?>) </a>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>
<? endif; ?>

<? if(!empty($post['user_id'])): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Blogger</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Description</th>
                        <th>Posts (total)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> <a href="/bloggers.php?name=<?=$post['user']['username']?>"> <?=nl2br(strip_tags($post['user']['username']))?> </a> </td>
                        <td><?=nl2br(strip_tags($post['user']['descr']))?></td>
                        <td><?=$post['user']['posts']?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<? endif; ?>

<? if(!empty($post['user_id'])): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Sysinfo</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Postname</th>
                        <th>Username</th>
                        <th>Userkey</th>
                        <th>Post signature</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$post['name']?></td>
                        <td><?=$post['user']['username']?></td>
                        <td><?=$post['user']['key']?></td>
                        <td><?=$post['sig']?></td>
                    </tr>
                    <tr>
                        <td colspan='4'> 
                            <code> verifymessage "<?=$post['user']['key']?>" "<?=$post['sig']?>" "<?=$post['user']['username']?>:<?=$post['name']?>"</code>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<? endif; ?>

<? if(!empty($post['reply_id'])): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Reply to ...</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Keywords</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> <a href="/post.php?name=<?=$post['reply_to']['name']?>"> <?=nl2br(strip_tags($post['reply_to']['name']))?> </a> </td>
                        <td><?=nl2br(strip_tags($post['reply_to']['title']))?></td>
                        <td><?=$post['reply_to']['_keywords']?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<? endif; ?>

<? if(!empty($post['replies'])): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Replies to this post</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Keywords</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($post['replies'] as $post): ?>
                    <tr>
                        <td> <a href="/post.php?name=<?=$post['name']?>"> <?=nl2br(strip_tags($post['name']))?> </a> </td>
                        <td><?=nl2br(strip_tags($post['title']))?></td>
                        <td><?=$post['keywords']?></td>
                    </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<? endif; ?>