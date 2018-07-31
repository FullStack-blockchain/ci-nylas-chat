<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

            <div class="content-wrapper">
                <?php init_header(); ?>
                <div class="content custom-scrollbar">

                    <div id="chat" class="page-layout carded full-width">

                        <div class="top-bg bg-primary"></div>

                        <!-- CONTENT -->
                        <div class="page-content-wrapper w-100 mx-auto px-0 pt-0 pt-sm-4 px-sm-4 pt-sm-8">

                            <div class="page-content-card">

                                <aside class="left-sidebar" data-fuse-bar="chat-left-sidebar" data-fuse-bar-media-step="lg">

                                    <div id="chat-left-sidebar-views" class="views">

                                        <div id="chats-view" class="view d-flex flex-column no-gutters">
                                            <!-- CHATS TOOLBAR -->
                                            <div class="toolbar">

                                                <!-- TOOLBAR TOP -->
                                                <div class="toolbar-top row no-gutters align-items-center justify-content-between px-4">

                                                    <!-- USER AVATAR WRAPPER -->
                                                    <div class="avatar-wrapper">

                                                        <!-- USER AVATAR -->
                                                        <img id="user-avatar-button" src="../assets/images/avatars/profile.jpg" class="avatar" alt="John Doe" />
                                                        <!-- / USER AVATAR -->

                                                        <!-- USER STATUS -->
                                                        <i class="icon- status online s-4"></i>
                                                        <!-- / USER STATUS -->

                                                    </div>
                                                    <div class="col px-4">
                                                        <span class="name h6"><?= $users['username']; ?></span>
                                                        <p class="last-message text-truncate text-muted" style="display: none;">last message</p>
                                                    </div>
                                                    <!-- / USER AVATAR -->

                                                    <button type="button" class="btn btn-icon">
                                                        <i class="icon icon-dots-vertical"></i>
                                                    </button>
                                                </div>
                                                <!-- / TOOLBAR TOP -->

                                                <!-- TOOLBAR BOTTOM -->
                                                <div class="toolbar-bottom row align-items-center no-gutters px-4">

                                                    <!-- SEARCH -->
                                                    <div class="search-wrapper md-elevation-1 row no-gutters align-items-center w-100 p-2">
                                                        <i class="icon-magnify s-4"></i>
                                                        <input class="col pl-2" type="text" placeholder="Search or start new chat">
                                                    </div>
                                                    <!-- / SEARCH -->
                                                </div>
                                                <!-- / TOOLBAR BOTTOM -->

                                            </div>
                                            <!-- / CHATS TOOLBAR -->

                                            <!-- CHATS CONTENT -->
                                            <div class="content col custom-scrollbar">

                                                <!-- CHATS LIST-->
                                                <div id="chat_conver_list" class="chat-list">
                                                    <? foreach ($conversation as $item) { ?>
                                                        <div class="contact ripple flex-wrap flex-sm-nowrap row p-4 no-gutters align-items-center unread" onclick="showconverlist('<?= $item['c_id'] ?>', '<?= $item['username']; ?>')">

                                                            <div class="col-auto avatar-wrapper">
                                                                <img src="../assets/images/avatars/profile.jpg" class="avatar" alt="Barrera" />
                                                                <i class="icon- status online s-4"></i>
                                                            </div>

                                                            <div class="col px-4">
                                                                <span class="name h6"><?= $item['username'] ?></span>
                                                                <p class="last-message text-truncate text-muted"  style="display: none;">last message</p>
                                                            </div>

                                                            <div class="col-12 col-sm-auto d-flex flex-column align-items-end">
                                                                <div class="last-message-time"><?= gmdate("d M y", $item['time']); ?></div>
                                                                <div id="div_unread_<?= $item['c_id'] ?>" class="bg-secondary text-auto unread-message-count mt-2" style="<?= $item['unread'] != '0' ? '' : 'display: none;'; ?>" ><?= $item['unread']; ?></div>
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="divider"></div>
                                                    <? } ?>
                                                </div>
                                                <!-- / CHATS LIST-->
                                            </div>
                                            <!-- / CHATS CONTENT -->

                                            <!-- CONTACTS BUTTON -->
                                            <button id="contacts-button" type="button" class="btn btn-fab btn-secondary a-align-bottom-right m-2" aria-label="contacts button">
                                                <i class="icon icon-plus"></i>
                                            </button>
                                            <!-- / CONTACTS BUTTON -->
                                        </div>

                                        <div id="contacts-view" class="view d-none flex-column no-gutters">
                                            <!-- CONTACTS TOOLBAR -->
                                            <div class="toolbar bg-secondary text-auto">

                                                <!-- TOOLBAR TOP -->
                                                <div class="toolbar-top row no-gutters align-items-center px-4">

                                                    <button type="button" class="back-to-chats-button btn btn-icon" aria-label="back button">
                                                        <i class="icon icon-arrow-left"></i>
                                                    </button>

                                                    <span class="h6">CONTACTS</span>
                                                </div>
                                                <!-- / TOOLBAR TOP -->

                                                <!-- TOOLBAR BOTTOM -->
                                                <div class="toolbar-bottom row align-items-center no-gutters px-4">

                                                    <div class="search-wrapper md-elevation-1 row no-gutters align-items-center w-100 p-2">
                                                        <i class="icon-magnify s-4"></i>
                                                        <input class="col pl-2" type="text" placeholder="Search contact">
                                                    </div>
                                                </div>
                                                <!-- / TOOLBAR BOTTOM -->
                                            </div>
                                            <!-- / CONTACTS TOOLBAR -->

                                            <!-- CONTACTS CONTENT -->
                                            <div class="content col custom-scrollbar">

                                                <!-- CONTACTS LIST-->
                                                <div class="contact-list">
                                                    <? foreach ($contactlists as $item) { ?>
                                                        <div class="contact ripple flex-wrap flex-sm-nowrap row p-4 no-gutters align-items-center unread" onclick="showcontactlist('<?= $item['user_id'] ?>')">

                                                            <div class="col-auto avatar-wrapper">
                                                                <img src="../assets/images/avatars/profile.jpg" class="avatar" alt="Barrera" />
                                                                <i class="icon- status online s-4"></i>
                                                            </div>

                                                            <div class="col px-4">
                                                                <span class="name h6"><?= $item['username'] ?></span>
                                                                <p class="last-message text-truncate text-muted"  style="display: none;">last message</p>
                                                            </div>
                                                        </div>

                                                        <div class="divider"></div>
                                                    <? } ?>
                                                </div>
                                                <!-- / CONTACTS LIST-->
                                            </div>
                                            <!-- / CONTACTS CONTENT -->
                                        </div>

                                        <div id="user-view" class="view d-none flex-column no-gutters">
                                            <!-- CONTACTS TOOLBAR -->
                                            <div class="toolbar bg-secondary text-auto d-flex flex-column no-gutters">

                                                <!-- TOOLBAR TOP -->
                                                <div class="toolbar-top row no-gutters align-items-center px-4">

                                                    <button type="button" class="back-to-chats-button btn btn-icon" aria-label="back button">
                                                        <i class="icon icon-arrow-left"></i>
                                                    </button>
                                                </div>
                                                <!-- / TOOLBAR TOP -->

                                                <!-- TOOLBAR BOTTOM -->
                                                <div class="toolbar-bottom col d-flex flex-column align-items-center justify-content-center">

                                                    <img src="../assets/images/avatars/profile.jpg" class="avatar huge" alt="John Doe" />

                                                    <div class="user-name h4 my-2"><?= $users['username']; ?></div>

                                                </div>
                                                <!-- / TOOLBAR BOTTOM -->
                                            </div>
                                            <!-- / CONTACTS TOOLBAR -->

                                            <!-- CONTACTS CONTENT -->
                                            <div class="content col bg-light p-4">

                                                <div class="card p-4">
                                                    <form>
                                                        <div class="form-group mt-4">
                                                            <textarea class="form-control" id="exampleTextarea" rows="3">it's a status....not your diary...</textarea>
                                                            <label for="exampleTextarea">Mood</label>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                            <!-- / CONTACTS CONTENT -->
                                        </div>

                                    </div>
                                </aside>

                                <div class="page-content">

                                    <div id="chat-content-views" class="views">

                                        <!-- START -->
                                        <div id="start-view" class="view d-flex flex-column align-items-center justify-content-center">
                                            <div class="big-circle md-elevation-4 row align-items-center justify-content-center no-gutters">

                                                <i class="s-32 text-secondary icon-hangouts"></i>

                                            </div>

                                            <span class="app-title h3 my-3">Chat App</span>

                                            <span class="text-muted h6 d-none d-xl-block">Select contact to start the chat!..</span>

                                            <button type="button" class="btn btn-secondary d-block d-xl-none" data-fuse-bar-toggle="chat-left-sidebar">
                                                Select contact to start the chat!..
                                            </button>

                                        </div>
                                        <!-- / START -->

                                        <!-- CHAT -->
                                        <div id="chat-view" class="view flex-column align-items-center justify-content-center d-none">
                                            <!-- CHAT TOOLBAR -->
                                            <div class="toolbar row no-gutters align-items-center justify-content-between w-100 px-4">

                                                <div class="col">

                                                    <div class="row no-gutters align-items-center">

                                                        <!-- RESPONSIVE CHATS BUTTON-->
                                                        <button type="button" class="btn btn-icon" data-fuse-bar-toggle="chat-left-sidebar">
                                                            <i class="icon icon-hangouts s-8"></i>
                                                        </button>
                                                        <!-- / RESPONSIVE CHATS BUTTON-->

                                                        <!-- CHAT CONTACT-->
                                                        <div class="chat-contact row no-gutters align-items-center">

                                                            <div class="avatar-wrapper mr-4">
                                                                <img src="../assets/images/avatars/profile.jpg" class="avatar" alt="Barrera" />
                                                                <i class="icon- status online s-4"></i>
                                                            </div>

                                                            <div class="chat-contact-name">
                                                                Barrera
                                                            </div>
                                                        </div>
                                                        <!-- / CHAT CONTACT-->

                                                    </div>
                                                </div>

                                                <button type="button" class="btn btn-icon">
                                                    <i class="icon icon-dots-vertical"></i>
                                                </button>
                                            </div>
                                            <!-- / CHAT TOOLBAR -->

                                            <!-- CHAT CONTENT -->
                                            <div id="chat_message_scroll" class="chat-content col custom-scrollbar">

                                                <!-- CHAT MESSAGES -->
                                                <div id="chat-messages" class="chat-messages">

                                                </div>
                                                <!-- CHAT MESSAGES -->
                                            </div>
                                            <!-- / CHAT CONTENT -->

                                            <!-- CHAT FOOTER -->
                                            <div class="chat-footer row align-items-center justify-content-center w-100 p-2 pl-4">

                                                <!-- REPLY FORM -->
                                                <?= form_open('',array('class' => 'reply-form row no-gutters align-items-center w-100', 'id' => 'reply_msg_form')); ?>
                                                    <input type="hidden" name="reply_c_id" id="reply_c_id" value="">
                                                    <input type="hidden" name="reply_user_name" id="reply_user_name" value="1">
                                                    <div class="form-group col mr-4">
                                                        <textarea class="form-control" id="reply_txt_msg" name="reply_txt_msg" placeholder="Type and hit enter to send message"></textarea>
                                                    </div>

                                                    <button type="submit" class="btn btn-fab btn-secondary" aria-label="Send message">
                                                        <i class="icon icon-send"></i>
                                                    </button>

                                                <?php echo form_close(); ?>
                                                <!-- / REPLY FORM -->
                                            </div>
                                            <!-- / CHAT FOOTER-->
                                        </div>
                                        <!-- / CHAT -->

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- / CONTENT -->
                    </div>
                </div>
                <nav id="footer" class="bg-dark text-auto row no-gutters align-items-center px-6">
                    <a class="btn btn-secondary text-capitalize" href="http://themeforest.net/item/fuse-angularjs-material-design-admin-template/12931855?ref=srcn" target="_blank">
                        <i class="icon icon-cart mr-2 s-4"></i>Purchase FUSE Bootstrap
                    </a>
                </nav>
            </div>
            <div class="quick-panel-sidebar custom-scrollbar" fuse-cloak data-fuse-bar="quick-panel-sidebar" data-fuse-bar-position="right">
                <div class="list-group" class="date">

                    <div class="list-group-item subheader">TODAY</div>

                    <div class="list-group-item two-line">

                        <div class="text-muted">

                            <div class="h1"> Friday</div>

                            <div class="h2 row no-gutters align-items-start">
                                <span> 4</span>
                                <span class="h6">th</span>
                                <span> Apr</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="list-group">

                    <div class="list-group-item subheader">Events</div>

                    <div class="list-group-item two-line">

                        <div class="list-item-content">
                            <h3>Group Meeting</h3>
                            <p>In 32 Minutes, Room 1B</p>
                        </div>
                    </div>

                    <div class="list-group-item two-line">

                        <div class="list-item-content">
                            <h3>Public Beta Release</h3>
                            <p>11:00 PM</p>
                        </div>
                    </div>

                    <div class="list-group-item two-line">

                        <div class="list-item-content">
                            <h3>Dinner with David</h3>
                            <p>17:30 PM</p>
                        </div>
                    </div>

                    <div class="list-group-item two-line">

                        <div class="list-item-content">
                            <h3>Q&amp;A Session</h3>
                            <p>20:30 PM</p>
                        </div>
                    </div>

                </div>

                <div class="divider"></div>

                <div class="list-group">

                    <div class="list-group-item subheader">Notes</div>

                    <div class="list-group-item two-line">

                        <div class="list-item-content">
                            <h3>Best songs to listen while working</h3>
                            <p>Last edit: May 8th, 2015</p>
                        </div>
                    </div>

                    <div class="list-group-item two-line">

                        <div class="list-item-content">
                            <h3>Useful subreddits</h3>
                            <p>Last edit: January 12th, 2015</p>
                        </div>
                    </div>

                </div>

                <div class="divider"></div>

                <div class="list-group">

                    <div class="list-group-item subheader">Quick Settings</div>

                    <div class="list-group-item">

                        <div class="list-item-content">
                            <h3>Notifications</h3>
                        </div>

                        <div class="secondary-container">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" />
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>

                    </div>

                    <div class="list-group-item">

                        <div class="list-item-content">
                            <h3>Cloud Sync</h3>
                        </div>

                        <div class="secondary-container">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" />
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>

                    </div>

                    <div class="list-group-item">

                        <div class="list-item-content">
                            <h3>Retro Thrusters</h3>
                        </div>

                        <div class="secondary-container">

                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" />
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
<?php init_tail(); ?>
<script type="text/javascript">
    function startrefresh(){ 
        getnewconversationreply();
        getchatconverlist();
    }
    $(document).ready(function(){
       setInterval(startrefresh, 1000);
    });

    function getchatconverlist()
    {
        var url = "<? echo site_url().'admin/chatting/getchatconverlist'; ?>";
        $.ajax({
            type: "POST",
            url: url, 
            success : 
                function(msg) {
                    msg = JSON.parse(msg);
                    for(var i=0; i<msg.length; i++)
                    {
                        if(msg[i]['unread'] != '0'){
                            $('#div_unread_'+msg[i]['c_id']).css('display','');
                            $('#div_unread_'+msg[i]['c_id']).html(msg[i]['unread']);
                        }
                        else
                        {
                            $('#div_unread_'+msg[i]['c_id']).css('display','none');
                        }
                    }                    
                }
        });
    }

    function getnewconversationreply()
    {
        var c_id = $('#reply_c_id').val();
        if(c_id == '')
            return;
        var user_name = $('#reply_user_name').val();
        showconverlist(c_id, user_name, false);
    }

    function showconverlist(c_id, username, scrollTop = true)
    {
        var url = "<? echo site_url().'admin/chatting/getconverlist'; ?>";
        $.ajax({
            type: "POST",
            url: url, 
            data: {c_id: c_id},
            success : 
                function(msg) {
                    msg = JSON.parse(msg);
                    $('#chat-messages').html(msg);
                    $('#reply_c_id').val(c_id);
                    $('#reply_user_name').val(username);
                    $('.chat-contact-name').html(username);
                    changeView('#chat-content-views', '#chat-view');
                    if(scrollTop){
                        $("#chat_message_scroll").scrollTop(0);
                        $('#chat_message_scroll').stop().animate({
                          scrollTop: $('#chat_message_scroll')[0].scrollHeight
                        }, 1);
                    }
                }
        });
    }

    function showcontactlist(user_id)
    {
        var url = "<? echo site_url().'admin/chatting/createconversation/'; ?>" + user_id;
        location.href= url;
    }

    $( "#reply_msg_form" ).submit(function( event ) {
        event.preventDefault();

        var url = "<? echo site_url().'admin/chatting/reply_msg'; ?>";
        var data = $("#reply_msg_form").serializeArray();
        $.ajax({
            type: "POST",
            url: url, 
            data: data,
            success : 
                function(msg) {
                    msg = JSON.parse(msg);
                    msg = $('#chat-messages').html() + msg;
                    $('#chat-messages').html(msg);
                    $('#reply_txt_msg').val('');
                    
                    $("#chat_message_scroll").scrollTop(0);
                    $('#chat_message_scroll').stop().animate({
                      scrollTop: $('#chat_message_scroll')[0].scrollHeight
                    }, 1);
                }
        });
        
    });
    $('#reply_txt_msg').on('keypress', function (e) {
        if(e.which === 13){
            $('form#reply_msg_form').submit();
            return false;    //<---- Add this line
        }
    });

    $('#contacts-button').on('click', function ()
    {
        changeView('#chat-left-sidebar-views', '#contacts-view');
    });

    $('.back-to-chats-button').on('click', function ()
    {
        changeView('#chat-left-sidebar-views', '#chats-view');
    });

    $('#user-avatar-button').on('click', function ()
    {
        changeView('#chat-left-sidebar-views', '#user-view');
    });

    function changeView(wrapper, view)
    {
        var wrapper = $(wrapper);
        wrapper.find('.view').removeClass('d-none d-flex');
        wrapper.find('.view').not(view).addClass('d-none');
        wrapper.find(view).addClass('d-flex');
    }

</script>
</html>