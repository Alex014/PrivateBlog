<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Private Blog</title>
        <?if(PHAR):?>
        <style>
        <?php
        echo file_get_contents(__DIR__.'/../css/bootstrap.min.css');
        ?>
        </style>
        <style>
        <?php
        echo file_get_contents(__DIR__.'/../css/bootstrap-cyborg.min.css');
        ?>
        </style>
        <?else:?>
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-cyborg.min.css">
        <?endif;?>
        
        <style>
            body {
                background: url('/img/bblack.jpg'); 
                /* background: url('/img/bblue.jpg'); */
                /* background: url('/img/bgreen.png'); */
                background-attachment: fixed;
            }
            
            *, h1,h2,h3, p, td,th {
                color: #ccc;
            }
            
            p {
                color: #ccc !important;
            }
            
            a, table a:not(.btn), .table a:not(.btn) {
                color: lightgreen;
            }
            
            a:hover,a:focus {
                color: limegreen;
            }
            
            code, pre {
                color: yellow;
                background: black;
            }
        </style>
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
            <?if(\config::$editable):?>
            <li role="presentation"><a href="/me">MAKE A POST</a></li>
            <?endif;?>
            <li role="presentation" <?if($page == 'config'):?>class="active"<?endif;?>><a href="/config.php">Config</a></li>
            <li role="presentation" <?if($page == 'howto'):?>class="active"<?endif;?>><a href="/howto.php">HowTo</a></li>
            <li role="presentation"><a href=#  class="__sync">SYNC</a></li>
            <li role="presentation" <?if($page == 'about'):?>class="active"<?endif;?>><a href="/about.php">About</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
