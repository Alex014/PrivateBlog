<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <p>
        This is a blog aggregator. All posts are stored in <a href="http://emercoin.com/">emercoin</a> blockchain.
        The posts can be either anonymous or have anonymous blogger as an author.
        The posts can also reply to each other.
        The posts can be created, updated or deleted only by an owner of <a href="https://sourceforge.net/projects/emercoin/files/">emercoin wallet</a> using name-value storage (NVS).
        </p>
        
        <p>
        The blockchain values from NVS gets parsed by this aggregator using <code>@key="value"</code> params inside the NVS value.
        </p>
        
        <div class="panel panel-default">            
            <div class="panel-heading">
              <h3 class="panel-title">Register a blogger (optional)</h3>
            </div>
            <div class="panel-body">
                Create a record named <code>blogger:username</code> in emercoin NVS<br>
                Add optional value as a user description. <br><br>

                    <ul class="list-group">
                        <li class="list-group-item">
                            <b>@key</b> emercoin address (example <code>ENwm9Aq8vHgTW6akyti3vQSZJK2qPAGaYW</code>)
                        </li>
                        <li class="list-group-item">
                            <b>@sig</b> the bloggers signature, result of <code>signmessage "emercoinaddress" "username"</code> command from emercoin console or RPC
                        </li>
                    </ul>
            </div>
        </div>
        <div class="panel panel-default">            
            <div class="panel-heading">
              <h3 class="panel-title">Make a post</h3>
            </div>
            <div class="panel-body">
                Create a record named <code>blog:postname</code> in emercoin NVS<br>
                The value will be the post body.<br>
                The body can contain all HTML tags except &lt script &gt; tag <br><br>
                        
                    <ul class="list-group">
                        <li class="list-group-item">
                            <b>@title</b> title of a post (optional)
                        </li>
                        <li class="list-group-item">
                            <b>@lang</b> the <a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes">ISO_639-1</a> code of post language   (optional, default en)
                        </li>
                        <li class="list-group-item">
                            <b>@username</b> username of blogger (optional)
                        </li>
                        <li class="list-group-item">
                            <b>@sig</b> (optional used with @username) <br>
                            The result of <code>signmessage "emercoinaddress" "username:postname"</code> command, where <b>emercoinaddress</b> is <b>@key</b> from user's record and <b>postname</b> is this post name from <code>blog:postname</code>.
                            This signature gets verified by <code> verifymessage "emercoinaddress" "@sig" "username:postname"</code> command
                        </li>
                        <li class="list-group-item">
                            <b>@keywords</b> "drugs,sex,rockandroll" (optional)
                        </li>
                        <li class="list-group-item">
                            <b>@reply</b> the name of the post you want to reply to (optional)
                        </li>
                    </ul>
            </div>
        </div>
        <div class="panel panel-default">            
            <div class="panel-heading">
              <h3 class="panel-title">Make a reply to other post</h3>
            </div>
            <div class="panel-body">
                Any post can reply to any other post using <b>@reply</b> keyword <br><br>
                
                    <ul class="list-group">
                        <li class="list-group-item">
                            <b>@reply</b> the name of the post you want to reply to
                        </li>
                    </ul>
            </div>
        </div>
    </div>
</div>