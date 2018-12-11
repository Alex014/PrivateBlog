<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Private Blog</title>
        
        <link rel="stylesheet" href="/css/bootstrap.min.css">
    </head>
<body>
    
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Private Blog</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li role="presentation" <?if($page == 'bloggers'):?>class="active"<?endif;?>><a href="/bloggers.php">Bloggers</a></li>
            <li role="presentation" <?if($page == 'keywords'):?>class="active"<?endif;?>><a href="/keywords.php">Keywords</a></li>
            <li role="presentation" <?if($page == 'search'):?>class="active"<?endif;?>><a href="/search.php">Search</a></li>
            <?if($page == 'post'):?>
            <li role="presentation" class="active"><a href="#"><?=text::cut($post['title'], 42)?></a></li>
            <?endif;?>
            <li role="presentation" <?if($page == 'config'):?>class="active"<?endif;?>><a href="/config.php">Config</a></li>
            <li role="presentation" <?if($page == 'howto'):?>class="active"<?endif;?>><a href="/howto.php">HowTo</a></li>
            <li role="presentation"><a href=# id="sync">SYNC</a></li>
            <li role="presentation" <?if($page == 'about'):?>class="active"<?endif;?>><a href="/about.php">About</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
