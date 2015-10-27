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
// ’ « » “ ” …
//
$lang = array_merge($lang, array(
	'APPROVED'	=> 'Schváleno',
	'BTN_ADD_FRIEND'	=> 'Přidat přítele',
	'BTN_CANCEL_REQUEST'	=> 'Zrušit žádost',
	'BTN_CONFIRM_FRIEND'	=> 'Potvrdit žádost',
	'BTN_REMOVE_FRIEND'	=> 'Odebrat z přátel',
	'CHAT_BOX'	=> 'Chat',
	'CHAT_BOX_APPROVE'	=> 'Schválit',
	'CHAT_BOX_MESSAGE'	=> 'Sem napište zprávu a stisknutím klávesy Enter ji odešlete',
	'CHAT_BOX_SENT_AT'	=> 'Odesláno v',
	'CHAT_BOX_STATUS'	=> 'Stav',
	'CONFIRM_ADD_FRIEND'	=> 'Opravdu chcete přidat tohoto uživatele do seznamu přátel?',
	'CONFIRM_REMOVE_FRIEND'	=> 'Opravdu chcete odebrat tohoto uživatele ze seznamu přátel?',
	'CONFIRM_REMOVE_REQUESTS'	=> 'Opravdu chcete požadavky odstranit?',
	'FRIEND'	=> 'Přítel',
	'FRIENDS_LIST'	=> 'Seznam přátel',
	'FRIENDS_REQUESTS'	=> 'Žádosti o přátelství',
	'FRIEND_REQUEST_CONFIRM'	=> 'Tento uživatel vám posílá žádost o přátelství',
	'FRIEND_REQUEST_FROM'	=> 'Žádost o přátelství od ',
	'FRIEND_REQUEST_SENT'	=> 'Žádost o přátelství odeslána',
	'SEND_FRIEND_REQUEST'	=> 'Odeslat žádost o přátelství',
	'WAITING_FOR_APPROVAL'	=> 'Čeká na schválení',
));
