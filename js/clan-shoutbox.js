// <![CDATA[
$(document).ready(function(){
    // Remove the "loading…" list entry
    $('ul#messages > li').remove();
    var scrollDown = 1;
    
    $('#shoutbox').submit(function(){
    
        var form = $(this);
        var name =  form.find("input[name='name']").val();
        var content =  form.find("input[name='content']").val();
        
        // Only send a new message if it's not empty (also it's ok for the server we don't need to send senseless messages)
        if (name == '' || content == '')
            return false;
        
        // Append a "pending" message (not yet confirmed from the server) as soon as the POST request is finished. The
        // text() method automatically escapes HTML so no one can harm the client.
        $.post(form.attr('action'), {'name': name, 'content': content}, function(data, status){
            $('<li class="pending" />').text(content).prepend($('<span />').text(name)).appendTo('ul#messages');
            $('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
            form.find("input[name='content']").val('').focus();
        });
        return false;
    });
    
    // Poll-function that looks for new messages
    var poll_for_new_messages = function(){
        $.ajax({url: 'shoutbox/clan-messages.php', dataType: 'json', timeout: 2000, success: function(messages, status){
            // Skip all responses with unmodified data
            
            if (!messages)
                return;
            
            // Remove the pending messages from the list (they are replaced by the ones from the server later)
            $('ul#messages > li.pending').remove();
            
            // Get the ID of the last inserted message or start with -1 (so the first message from the server with 0 will
            // automatically be shown).
            var last_message_id = $('ul#messages').data('last_message_id');
            if (last_message_id == null)
                last_message_id = -1;
            
            // Add a list entry for every incomming message, but only if we not already inserted it (hence the check for
            // the newer ID than the last inserted message).
            for(var i = 0; i < messages.length; i++)
            {
                var msg = messages[i];
                if (msg.id > last_message_id)
                {
                    var date = new Date(msg.time * 1000);
                    mins = ('0'+date.getMinutes()).slice(-2);
                    $('<li/>').html(msg.content).
                        prepend( $("<span />").html(date.getHours() + ":" + mins + " "+ "<a href=\"javascript:chatWith('"+msg.name+"')\">"+msg.name+": </a>" ) ).
                        appendTo('ul#messages');
                    $('ul#messages').data('last_message_id', msg.id);
                }
            }
            
            // Remove all but the last 50 messages in the list to prevent browser slowdown with extremely large lists
            // and finally scroll down to the newes message.
            $('ul#messages > li').slice(0, -50).remove();
            if (scrollDown == 1) {
                $('ul#messages').scrollTop($('ul#messages').get(0).scrollHeight);
                scrollDown = 0;
            }
        }});
    };
    
    // Kick of the poll function and repeat it every two seconds
    poll_for_new_messages();
    setInterval(poll_for_new_messages, 2000);
});
// ]]>