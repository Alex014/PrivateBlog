<? if(!empty($post['id'])): ?>
<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?=nl2br(strip_tags($post['title']))?></h3>
              <a href="/post.php?name=<?=$post['name']?>" style="position: absolute; right: 32px; top: 10px;">[Show simple]</a>
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

<?php

function replies_recursive($replies, $level = 1, $name = '') {
    $oposts = new \darkblog\db\posts();

    foreach($replies as $index => $reply) {
        if(($name == '') || ($name != $reply['name']) || ($level < 33)) {
            $replies[$index]['children'] = $oposts->getReplies($reply['id']);

            echo '<div class="panel panel-default" style="margin: 2px; margin-left: 20px">';
            echo '<div class="panel-heading">';

            echo "<a href='/post.php?name=$reply[name]' target='_blank'> ";

            if(!empty($reply['title'])) {
                echo nl2br(strip_tags($reply['title'])); 
            } else {
                echo nl2br(strip_tags($reply['name']));
            }

            if(count($replies[$index]['children']) > 0)
                echo ' <b>['.count($replies[$index]['children']).']</b>';

            echo "</a>";


            if($level == 1)
                echo "<a href=# class='expand' data-id='$reply[id]' data-visible='1' style='float: right; font-size: 18px;'><span class='glyphicon glyphicon-minus'/></a>";
            else
                echo "<a href=# class='expand' data-id='$reply[id]' data-visible='0' style='float: right; font-size: 18px;'><span class='glyphicon glyphicon-plus'/></a>";
            echo "</div>";

            if($level == 1)
                echo '<div id="content_'.$reply['id'].'">'.$reply['content'].'</div>';
            else
                echo '<div id="content_'.$reply['id'].'" style="display: none;">'.$reply['content'].'</div>';

            echo "<center></center>";

            $replies[$index]['children'] = $oposts->getReplies($reply['id']);


            if($level == 1)
                echo '<div id="replies_'.$reply['id'].'">';
            else
                echo '<div id="replies_'.$reply['id'].'" style="display: none;">';


            if(!empty($reply['children']))
                replies_recursive($reply['children'], $level+1, $name);

            echo "</div>";

            echo "</div>";
        }
    }
}
?>

<? if(!empty($post['replies'])): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Replies to this post</h3>
            </div>
            <?=replies_recursive($post['replies'], 1, $post['name'])?>
        </div>
    </div>
</div>
<? endif; ?>