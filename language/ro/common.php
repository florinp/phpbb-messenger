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
	'APPROVE'	=> 'Aproba',
	'APPROVED'	=> 'Approved', // to be translated
	'BTN_ADD_FRIEND'	=> 'Adauga prieten',
	'BTN_CANCEL_REQUEST'	=> 'Anulare cerere',
	'BTN_CONFIRM_FRIEND'	=> 'Confirma cererea',
	'BTN_REMOVE_FRIEND'	=> 'Elimină din lista de prieteni',
	'CHAT_BOX'	=> 'Chat Box', // to be translated
	'CHAT_BOX_MESSAGE'	=> 'Type here and press shift + enter to send the message', // to be translated
	'CONFIRM_ADD_FRIEND'	=> 'Sunteți sigur că doriți ca utilizatorul să fie prietenul tău?',
	'CONFIRM_REMOVE_FRIEND'	=> 'Doriți să eliminați utilizatorul din lista de prieteni?',
	'CONFIRM_REMOVE_REQUESTS'	=> 'Sunteți sigur că doriți să ștergeți cererile?',
	'DELETE'	=> 'Sterge',
	'FRIEND'	=> 'Prieten',
	'FRIENDS_LIST'	=> 'Lista prieteni',
	'FRIENDS_REQUESTS'	=> 'Cereri',
	'FRIEND_REQUEST_CONFIRM'	=> 'Acest utilizator ti-a trimis o cerere de prietenie',
	'FRIEND_REQUEST_SENT'	=> 'Cerere trimisa',
	'FROM'	=> 'De la',
	'SEND_FRIEND_REQUEST'	=> 'Trimite cerere'
	'SENT_AT'	=> 'Trimis la',
	'STATUS'	=> 'Status',
	'WAITING_FOR_APPROVAL'	=> 'Waiting for approval', // to be translated
));
