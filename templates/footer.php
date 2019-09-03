
    </div><!-- /.container -->

<?if(PHAR):?>
<script type='text/javascript'>
<?php
echo file_get_contents(__DIR__.'/../js/jquery.min.js');
?>
</script>
<script type='text/javascript'>
<?php
echo file_get_contents(__DIR__.'/../js/ie10-viewport-bug-workaround.js');
?>
</script>
<script type='text/javascript'>
<?php
echo file_get_contents(__DIR__.'/../js/bootstrap.min.js');
?>
</script>
<script type='text/javascript'>
<?php
echo file_get_contents(__DIR__.'/../js/common.js');
?>
</script>
<?else:?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <script src="/js/common.js"></script>
<?endif;?>

</body></html>