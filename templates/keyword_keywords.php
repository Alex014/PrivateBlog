<div class="row" style="padding-top: 60px;">
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav nav-pills">
                    <li role="presentation"><a href='#'> <b> <?=$keyword['word']?> </b> </a></li>
                    <li role="presentation"><a href="?name=<?=$keyword['word']?>&mode=posts">Posts</a></li>
                    <li role="presentation"><a href="?name=<?=$keyword['word']?>&mode=bloggers">Bloggers</a></li>
                    <li role="presentation" class="active"><a href="?name=<?=$keyword['word']?>&mode=keywords">Keywords</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Keywords used with keyword "<?=$keyword['word']?>"</h3>
            </div>
            <div class="panel-body">
                <? foreach($keyword['keywords'] as $keyword): ?>
                    <a href="/keywords.php?name=<?=$keyword['word']?>"> <?=$keyword['word']?> (<?=$keyword['posts']?>) </a>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>