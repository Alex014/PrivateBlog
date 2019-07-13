<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Keywords</h3>
            </div>
            <div class="panel-body">
                <? if(empty($keywords)): ?>
                    No data you must <a href="#" class="__sync">SYNC</a> data  with blockchain
                <? endif; ?>
                <? foreach($keywords as $keyword): ?>
                    <a href="/keywords.php?name=<?=$keyword['word']?>"> <?=$keyword['word']?> (<?=$keyword['posts']?>) </a>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>