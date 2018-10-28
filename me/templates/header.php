<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Private Blog</title>
        
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        
        
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="/js/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="/js/ie10-viewport-bug-workaround.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        

    <script src="/js/bootstrap.validate.js"></script>
    <script src="/js/bootstrap.validate.en.js"></script>

        <script src="/js/me.js"></script>
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
