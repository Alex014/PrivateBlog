<table class="table table-bordered" style="margin-top: 80px;">
    <thead>
        <tr>
            <td colspan='5'>
                <div class="input-group">
                <input id='search' type="text" placeholder="Search by name/title"  class="form-control">
                <span class="input-group-addon">
                    <input class='btn' id='expired' type='checkbox'/>
                    <span class="badge">Expired</span>
                </span>
                <div class="input-group-btn">
                    <a href='#' class='btn btn-info' id='do-search'>SEARCH</a>
                </div>
                </div>
            </td>
        </tr>
        <tr>
            <th> <a href='#' class="glyphicon glyphicon-sort-by-alphabet" id='order_title'> Title (Name)</a> </th>
            <th>Address</th>
            <th> <a href='#'class="glyphicon glyphicon-sort-by-alphabet-alt" id='order_expires_in'> Expires in</a> </th>
            <th>Lang</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?foreach($posts as $post):?>
        <tr>
            <td>
                <a href='/post.php?name=<?=$post['name']?>' target='blank'>
                <?if(isset($post['vars']['title'])):?>  
                    <?=$post['vars']['title']?> (<?=$post['name']?>) 
                <?else:?> 
                    <?=$post['name']?> 
                <?endif;?>
                </a>
            </td>
            <td><?=$post['address']?></td>
            <td> <?=$post['expires_in']?> blocks <?=$post['expires_in_days']?> days </td>

            <?if(isset($post['vars']['title'])):?>  
                <td><?=$post['vars']['lang']?></td>
            <?else:?> 
                <td></td>
            <?endif;?>

            <td><a class="btn btn-info" href="/me/edit_post.php?name=<?=$post['name']?>">Edit</a> </td>
        </tr>
        <?if(isset($post['vars']['keywords']) || isset($post['vars']['reply']) || isset($post['vars']['username']) || isset($post['vars']['sig'])):?>
        <tr>
            <td colspan='5'> 
                <?if(isset($post['vars']['keywords'])):?>
                <p> <b>Keywords:</b> <?=$post['vars']['keywords']?> </p>
                <?endif;?>
                <?if(isset($post['vars']['reply'])):?>
                <p> <b>Reply:</b> <?=$post['vars']['reply']?> </p>
                <?endif;?>
                <?if(isset($post['vars']['username'])):?>
                <p>  <b>Username:</b> <a href='/bloggers.php?name=<?=$post['vars']['username']?>' target='blank'><?=$post['vars']['username']?></a> </p>
                <?endif;?>
                <?if(isset($post['time'])):?>
                <p> <b>Created:</b> <?=$post['time']?> </p>
                <?endif;?>
                <?if(isset($post['vars']['sig'])):?>
                <p> <b>Signature:</b> <?=$post['vars']['sig']?> </p>
                <?endif;?>
            </td>
        </tr>
        <?endif;?>
        <?endforeach;?>
    </tbody>
</table>