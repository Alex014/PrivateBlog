<table class="table table-bordered" style="margin-top: 80px;">
    <thead>
        <tr>
            <th>Filename</th>
            <th>URL</th>
            <th>Expires in</th>
            <th width='1%'>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?foreach($files as $file):?>
        <tr>
            <td>
                <a href='/file.php?id=<?=$file['name']?>' target='blank'>
                <b><?=$file['name']?></b>
                </a>
            </td>
            <td>  <b>$$$<?=$file['name']?><b> - url to file <b>$<?=$file['name']?>="<?=$file['name']?>"</b> - link to file </td>
            <td> <?=$file['expires_in']?> blocks <?=round($file['expires_in']/175, 1)?> days </td>
            <td><a class="btn btn-info" href="/me/edit_file.php?name=<?=$file['name']?>">Edit</a></td>
        </tr>
        <?endforeach;?>
    </tbody>
</table>