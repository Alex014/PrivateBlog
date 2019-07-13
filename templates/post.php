<? if(!empty($post['id'])): ?>
<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?=nl2br(strip_tags($post['title']))?></h3>
              <? if(!empty($post['replies'])): ?>
              <a href="/post.php?name=<?=$post['name']?>&full#replies" style="position: absolute; right: 32px; top: 10px;"> [Show Full] </a>
              <? endif; ?>
            </div>
            <div class="panel-body">
                <?=$post['content']?>
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
                        <th>Name / Title</th>
                        <th>Keywords</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(! empty($post['reply_to']['title'])) $title = $post['reply_to']['title']; else $title = $post['reply_to']['name'];
                    ?>
                    <tr>
                        <td>
                            <a href="/post.php?name=<?=$post['reply_to']['name']?>" target='_blank'> <?=nl2br(strip_tags($title))?> </a>
                        </td>
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
            <a name="replies"></a>
            <div class="panel-heading">
              <h3 class="panel-title">Replies to this post</h3>
              <a href="/me/new_post.php?reply=<?=$post['name']?>" style="position: absolute; right: 32px; top: 10px;" target='_blank'> [REPLY to this POST] </a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name / Title</th>
                        <th>Keywords</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($post['replies'] as $post): ?>
                    <?php 
                    if(! empty($post['title'])) $title = $post['title']; else $title = $post['name'];
                    ?>
                    <tr>
                        <td>
                            <a href="/post.php?name=<?=$post['name']?>" target='_blank'> <?=nl2br(strip_tags($title))?> </a>
                        </td>
                        <td><?=$post['keywords']?></td>
                        <td align="right"><a href="/me/new_post.php?reply=<?=$post['name']?>" target='_blank'> [REPLY] </a></td>
                    </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<? endif; ?>