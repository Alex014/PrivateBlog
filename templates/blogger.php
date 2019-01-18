<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?=$user['username']?></h3>
            </div>
            <div class="panel-body"><?=nl2br(strip_tags($user['descr']))?></div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Signature</th>
                        <th>Posts (total)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$user['key']?></td>
                        <td><?=$user['sig']?></td>
                        <td><?=$user['posts']?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Keywords</h3>
            </div>
            <table class="table">
                <tbody>
                    <? foreach($user['keywords'] as $keyword): ?>
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
            <table class="table">
                <thead>
                    <tr>
                        <th>Name / Title</th>
                        <th>Keywords</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($user['users_posts'] as $post): ?>
                    <?php 
                    if(! empty($post['title'])) $title = $post['title']; else $title = $post['name'];
                    ?>
                    <tr>
                        <td> <a href="/post.php?name=<?=$post['name']?>"> <?=nl2br(strip_tags($title))?> </a> </td>
                        <td><?=nl2br(strip_tags($title))?></td>
                        <td><?=$post['keywords']?></td>
                    </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>