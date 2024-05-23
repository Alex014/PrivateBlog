
    </div><!-- /.container -->


<?if(PHAR):?>
<script type='text/javascript'>
<?php
echo file_get_contents(__DIR__.'/../../js/bootstrap.min.js');
?>
</script>
<script type='text/javascript'>
<?php
echo file_get_contents(__DIR__.'/../../js/bootstrap.validate.js');
?>
</script>
<script type='text/javascript'>
<?php
echo file_get_contents(__DIR__.'/../../js/bootstrap.validate.en.js');
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

        <script src="/js/bootstrap.min.js"></script>
        

        <script src="/js/bootstrap.validate.js"></script>
        <script src="/js/bootstrap.validate.en.js"></script>
    
        <script src="/js/common.js"></script>

        
<?endif;?>

</body></html>