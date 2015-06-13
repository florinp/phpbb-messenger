<?php
/**
*
* Messenger extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Florin Pavel <https://github.com/florinp/>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'APPROVED'	=> 'Approved',
	'BTN_ADD_FRIEND'	=> 'Add friend',
	'BTN_CANCEL_REQUEST'	=> 'Cancel request',
	'BTN_CONFIRM_FRIEND'	=> 'Confirm the request',
	'BTN_REMOVE_FRIEND'	=> 'Remove from friends list',
	'CHAT_BOX'	=> 'Chat Box',
	'CHAT_BOX_APPROVE'	=> 'Approve',
	'CHAT_BOX_MESSAGE'	=> 'Type here and press shift + enter to send the message',
	'CHAT_BOX_SENT_AT'	=> 'Sent at',
	'CHAT_BOX_STATUS'	=> 'Status',
	'CONFIRM_ADD_FRIEND'	=> 'Are you sure you want the user to be your friend?',
	'CONFIRM_REMOVE_FRIEND'	=> 'Are you sure you want to remove the user from your friends list?',
	'CONFIRM_REMOVE_REQUESTS'	=> 'Are you sure you want to delete the requests?',
	'FRIEND'	=> 'Friend',
	'FRIENDS_LIST'	=> 'Friends list',
	'FRIENDS_REQUESTS'	=> 'Friends requests',
	'FRIEND_REQUEST_CONFIRM'	=> 'This user has sent you a friend request',
	'FRIEND_REQUEST_FROM'	=> 'Friend request from ',
	'FRIEND_REQUEST_SENT'	=> 'Friend request sent',
	'SEND_FRIEND_REQUEST'	=> 'Send friend request',
	'WAITING_FOR_APPROVAL'	=> 'Waiting for approval',
));
