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
        echo file_get_contents(__DIR__.'/../../css/bootstrap.min.css');
        ?>
        </style>
        <style>
        <?php
        echo file_get_contents(__DIR__.'/../../css/bootstrap-cyborg.min.css');
        ?>
        </style>
        
        <script type='text/javascript'>
        <?php
        echo file_get_contents(__DIR__.'/../../js/jquery.min.js');
        ?>
        </script>
        <script type='text/javascript'>
        <?php
        echo file_get_contents(__DIR__.'/../../js/ie10-viewport-bug-workaround.js');
        ?>
        </script>
        <script type='text/javascript'>
        <?php
        echo file_get_contents(__DIR__.'/../../js/me.js');
        ?>
        </script>

        <?else:?>
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-cyborg.min.css">
        
        <script src="/js/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="/js/ie10-viewport-bug-workaround.js"></script>
        <script src="/js/me.js"></script>
        <?endif;?>
        
        <style>
            body {
                /*background: url('/img/bblack.jpg'); 
                 background: url('/img/bblue.jpg'); */
                 background: url('/img/bgreen.png');
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
          <a class="navbar-brand" href="#">My Private Blog</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li role="presentation" <?if($page == 'new_post'):?>class="active"<?endif;?>><a href="/me/new_post.php">Make a post</a></li>
            <li role="presentation" <?if($page == 'posts'):?>class="active"<?endif;?>><a href="/me/posts.php">My Posts</a></li>
            <li role="presentation" <?if($page == 'new_blogger'):?>class="active"<?endif;?>><a href="/me/new_blogger.php">Make a Blogger</a></li>
            <li role="presentation" <?if($page == 'bloggers'):?>class="active"<?endif;?>><a href="/me/bloggers.php">My Bloggers</a></li>
            <li role="presentation" <?if($page == 'new_file'):?>class="active"<?endif;?>><a href="/me/new_file.php">Upload a file</a></li>
            <li role="presentation" <?if($page == 'files'):?>class="active"<?endif;?>><a href="/me/files.php">My Files</a></li>
            <li role="presentation"><a href="/">BACK &gt; &gt; &gt;</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container" id='container-main'>
