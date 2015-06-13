<?php
/**
*
* Messenger extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com)
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
	'APPROVE'	=> 'Accepter',
	'APPROVED'	=> 'Acceptée',
	'BTN_ADD_FRIEND'	=> 'Ajouter comme ami(e)',
	'BTN_CANCEL_REQUEST'	=> 'Annuler la demande',
	'BTN_CONFIRM_FRIEND'	=> 'Confirmer la demande',
	'BTN_REMOVE_FRIEND'	=> 'Retirer de la liste d’amis',
	'CHAT_BOX'	=> 'Boite du tchat',
	'CHAT_BOX_MESSAGE'	=> 'Saisir votre message puis appuyer simultanément sur les touches MAJ + ENTRÉE pour envoyer le message.',
	'CONFIRM_ADD_FRIEND'	=> 'Êtes-vous sûre de vouloir ajouter cet utilisateur comme votre ami ?',
	'CONFIRM_REMOVE_FRIEND'	=> 'Êtes-vous sûre de vouloir retirer cet utilisateur de votre liste d’amis ?',
	'CONFIRM_REMOVE_REQUESTS'	=> 'Êtes-vous sûre de vouloir supprimer les demandes d’amis ?',
	'DELETE'	=> 'Supprimer',
	'FRIEND'	=> 'Ami(e)',
	'FRIENDS_LIST'	=> 'Liste d’amis',
	'FRIENDS_REQUESTS'	=> 'Demandes d’amis',
	'FRIEND_REQUEST_CONFIRM'	=> 'Cet utilisateur vous a envoyé une demande d’ami.',
	'FRIEND_REQUEST_SENT'	=> 'Demande d’ami envoyée',
	'FROM'	=> 'De',
	'SEND_FRIEND_REQUEST'	=> 'Envoyer la demande d’ami',
	'SENT_AT'	=> 'Envoyer à',
	'STATUS'	=> 'Statut',
	'WAITING_FOR_APPROVAL'	=> 'En attente d’acceptation',
));
