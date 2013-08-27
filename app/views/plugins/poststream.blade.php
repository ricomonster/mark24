<div class="post-stream-holder well">
    <div class="stream-title"><h4>Latest Posts</h4></div>

    <ul class="post-stream">
        @for($x = 0; $x < 5; $x++)
        <li class="post-holder">
            <a href="#"><img src="/assets/images/anon.png" width="50" class="img-rounded pull-left"></a>
            <div class="post-content pull-left">
                <div class="post-content-header">
                    <a href="#" class="post-sender-name">Juan dela Cruz</a>
                    <span class="subtext sender-to-receiver">to</span>
                    <a href="#" class="post-receiver-name">Group 1</a>
                </div>
                <div class="post-content-container">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Vivamus arcu lectus, euismod et lectus non, euismod aliquet orci.
                    Nam auctor eget ligula non porta.
                    Integer feugiat nunc sed laoreet euismod.
                </div>
            </div>
            <div class="post-etcs pull-left">
                <ul class="post-etcs-holder">
                    <li><a href="#"><i class="icon-thumbs-up-alt"></i> Like it</a></li>
                    <li><a href="#"><i class="icon-comment-alt"></i> Reply</a></li>
                    <li><a href="#"><i class="icon-time"></i> August 25, 2013</a></li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </li>
        @endfor
    </ul>
</div>
