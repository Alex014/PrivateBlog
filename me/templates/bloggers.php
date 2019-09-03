<table class="table table-bordered" style="margin-top: 80px;">
    <thead>
        <tr>
            <th>Username</th>
            <th>Key</th>
            <th>Description</th>
            <th>Expires in</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?foreach($bloggers as $blogger):?>
        <tr>
            <td>
                <a href='/bloggers.php?name=<?=$blogger['name']?>' target='blank'>
                <b><?=$blogger['name']?></b>
                </a>
            </td>
            <td><?=$blogger['key']?></td>
            <td><?=$blogger['content']?></td>
            <td> <?=$blogger['expires_in']?> blocks <?=round($blogger['expires_in']/175, 1)?> days </td>
            <td><a class="btn btn-info" href="/me/edit_blogger.php?username=<?=$blogger['name']?>">Edit</a></td>
        </tr>
        <?if(!empty($blogger['sig'])):?>
        <tr>
            <td colspan="5" align="middle"> <b>Signature:</b> <?=$blogger['sig']?></td>
        </tr>
        <?endif;?>
        <?endforeach;?>
    </tbody>
</table>