// refresh chat boxes
var refreshChatBoxesInterval = $.timer(3000, refreshChatBoxes);
var refreshInboxCount = $.timer(60000, checkForNewMessages);
var url = chat_options.baseUrl;

$(function(){

	$('.chat_head').click(function(){
		$('.chat_body').slideToggle('slow');
	});

	$('.user').each(function(index){
		$(this).click(function(){
			if(($('.msg_box').length+1) >= 5) {
				return false;
			}
			if(($('.msg_box').length+1) > 1) {
				generateBox($(this).data('userid'), $(this).data('username'), 290*($('.msg_box').length+1));
			} else {
				generateBox($(this).data('userid'), $(this).data('username'), 290);
			}
		});
	});
});

function generateBox(userId, username, right)
{
	if($("#chat_"+userId).length > 0) {
		if($("#chat_"+userId+" .msg_wrap").css('display') == 'none') {
			$("#chat_"+userId+" .msg_wrap").slideToggle('slow');
		}
	} else {
		var box;
		box = '<div id="chat_'+userId+'" class="msg_box" style="right: ' + right + 'px;">';
		box += '<div class="msg_head" onclick="minimizeChatBox(this);">'+username+' <div class="close" onclick="closeChatBox(this)">x</div></div>';
		box += '<div class="msg_wrap">';
		box += '<div class="msg_body">';
		loadMessagesOnBoxGenerate(userId);
		box += '<div class="msg_push"></div>';
		box += '</div>';
		box += '<div class="msg_footer"><textarea class="msg_input" onkeypress="sendMessage(event, this)" onkeyup="typing()" rows="4" placeholder="' + chat_lang.CHAT_BOX_MESSAGE + '"></textarea></div>';
		box += '</div>';
		box += '<div>';
		$('body').append(box);
		updateMessagesStatus(userId);
		// start chat boxes interval
		refreshChatBoxesInterval.reset(3000);
	}
}

function typing() {
    //refreshChatBoxesInterval.stop();
}

function sendMessage(e, $this) {
	if(e.keyCode == 13) {
        refreshChatBoxesInterval.stop();
		var userId = $($this).parent().parent().parent().attr('id').split('_')[1];
		$.post(url + '/app.php/messenger/publish', {
			text: $($this).val(),
			receiver_id: userId
		}, function(data){
			if(data.success == false){
				alert(data.error);
			}
			$($this).val('');
			addMessageInViewAfterAdd(data);
            refreshChatBoxesInterval.reset(3000);
			$($this).focus();
		});
		e.preventDefault();
	}
}

function loadMessagesOnBoxGenerate(userId)
{
	$("#chat_"+userId+" .msg_body").empty();
	$("#chat_"+userId+" .msg_body").html('<div class="msg_push"></div>');
	var box = '';
	$.getJSON(url + '/app.php/messenger/load', {
		friend_id: userId
	}, function(data){
		$.each(data, function(index, value){
			if(value.type == 'inbox') className = 'msg_a';
			else className = 'msg_b';
			$("#chat_"+userId+" .msg_body").prepend('<div class="msg '+className+'" data-msg-id="'+value.id+'">'+value.text+'</div>');
			$("#chat_"+userId+" .msg_body").scrollTop($("#chat_"+userId+" .msg_body")[0].scrollHeight);
		});
	});
}

function addMessageInViewAfterAdd(data)
{
	if(data.success == true)
	{
		var message = data.message;
		$('<div class="msg msg_b" data-msg-id="'+message.id+'">'+message.text+'</div>').insertBefore("#chat_"+message.receiver_id+" .msg_body .msg_push");
		$("#chat_"+message.receiver_id+" .msg_body").scrollTop($("#chat_"+message.receiver_id+" .msg_body")[0].scrollHeight);
	}
    
    return false;
}

function refreshChatBoxes()
{
	if($('.msg_box').length > 0) {
		$('.msg_box').each(function(){
			var userId = $(this).attr('id').split('_')[1];
			var msgs = $(this).find('.msg_body .msg');
			var msg_ids = [];
			msgs.each(function(){
				var msg = $(this);
				msg_ids.push(msg.data('msg-id'));
			});
			addNewMessageInView(userId, msg_ids);
		});
	} else {
		refreshChatBoxesInterval.stop();
	}
}

function addNewMessageInView(userId, array)
{
	$.getJSON(url + '/app.php/messenger/load', {
		friend_id: userId
	}, function(data){
		$.each(data, function(index, value){
			if($.inArray(parseInt(value.id), array) > -1)
			{
				return;
			}
			else
			{
				if(value.type == 'inbox') className = 'msg_a';
				else className = 'msg_b';
				$('<div class="msg '+className+'" data-msg-id="'+value.id+'">'+value.text+'</div>').insertBefore("#chat_"+userId+" .msg_body .msg_push");
				$("#chat_"+userId+" .msg_body").scrollTop($("#chat_"+userId+" .msg_body")[0].scrollHeight);
				refreshChatBoxesInterval.stop();
                setTimeout(function(){
                    refreshChatBoxesInterval.reset(3000);
                }, 1500);
			}
		});
	});
}

function updateMessagesStatus(userId)
{
	$.getJSON(url + '/app.php/messenger/update_messages', {
		friend_id: userId
	}, function(data){
		if(data.success == true) {
			$("#messenger_new_messages_count").html(data.newVal);
		}
	});
}

function checkForNewMessages()
{
	$('.user').each(function(index){
		var userId = $(this).data('userid');
		var elem = $(this).find("#messenger_new_messages_count");
		$.getJSON(url + '/app.php/messenger/check_new_messages', {
			friend_id: userId
		}, function(data){
			if(data.success == true) {
				elem.html(data.messages);
			}
		});
	});
}

function minimizeChatBox($this) {
	var msg_box = $($this).parent();
	var msg_wrap = $(msg_box).find('.msg_wrap');
	$(msg_wrap).slideToggle('slow');
}

function closeChatBox($this) {
	var msg_box = $($this).parent().parent();
	$(msg_box).remove();
	if ($(".msg_box").length > 0) {
		$(".msg_box").each(function(index) {
			var no = index + 1;
			$(this).css("right", no*290 + "px");	
		});
	}
}

function parseURL(url) {
	var parser = document.createElement('a'),
		searchObject = {},
		queries, split, i;
		
	parser.href = url;
	
	queries = parser.search.replace(/^\?/, '').split('&');
	for(i = 0; i < queries.length; i++) {
		split = queries[i].split('=');
		searchObject[split[0]] = split[1];
	}
	
	return {
		protocol: parser.protocol,
		host: parser.host,
		hostname: parser.hostname,
		port: parser.port,
		pathname: parser.pathname,
		search: parser.search,
		searchObject: searchObject,
		hash: parser.hash
	}
}
