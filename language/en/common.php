<?php

if(empty($lang) || !is_array($lang)) {
    $lang = array();
}

$lang = array_merge($lang, array(
    'CONFIRM_ADD_FRIEND' => 'Are you sure you want the user to be your friend?',
    'CONFIRM_REMOVE_FRIEND' => 'Are you sure you want to remove the user from your friends list?',
    'CONFIRM_REMOVE_REQUESTS' => 'Are you sure you want to delete the requests?',
    'BTN_REMOVE_FRIEND' => 'Remove from friends list',
    'BTN_CANCEL_REQUEST' => 'Cancel request',
    'BTN_ADD_FRIEND' => 'Add friend',
    'FRIEND' => 'Friend',
    'STATUS' => 'Status',
    'FRIENDS_LIST' => 'Friends list',
    'FRIENDS_REQUESTS' => 'Friends requests',
    'FROM' => 'From',
    'SENT_AT' => 'Sent at',
    'DELETE' => 'Delete',
    'APPROVE' => 'Approve',
    'FRIEND_REQUEST_SENT' => 'Friend request sent',
    'FRIEND_REQUEST_CONFIRM' => 'This user has sent you a friend request',
    'BTN_CONFIRM_FRIEND' => 'Confirm the request',
    'SEND_FRIEND_REQUEST' => 'Send friend request'
));
