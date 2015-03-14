<?php

namespace florinp\messenger\notification\type;

class friend_request extends \phpbb\notification\type\base
{

  public function get_type()
  {
    return 'florinp.messenger.notification.type.friend_request';
  }

  public function __construct(\phpbb\user_loader $user_loader, \phpbb\db\driver\driver_interface $db, \phpbb\cache\driver\driver_interface $cache, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\config\config $config, $phpbb_root_path, $php_ext, $notification_types_table, $notifications_table, $user_notifications_table)
  {
    $this->user_loader = $user_loader;
    $this->db = $db;
    $this->cache = $cache;
    $this->user = $user;
    $this->auth = $auth;
    $this->config = $config;
    $this->phpbb_root_path = $phpbb_root_path;
    $this->php_ext = $php_ext;
    $this->notification_types_table = $notification_types_table;
    $this->notifications_table = $notifications_table;
    $this->user_notifications_table = $user_notifications_table;
  }

  public static $notification_option = array(
    'group'   => 'NOTIFICATION_GROUP_MISCELLANEOUS',
  );

  public function is_available()
  {
    return true;
  }

  public static function get_item_id($data)
  {
    return (int)$data['request_id'];
  }

  public static function get_item_parent_id($data)
  {
    return 0;
  }

  public function users_to_query()
  {
    return array();
  }

  public function find_users_for_notification($data, $options = array())
  {
    $options = array_merge(array(
       'ignore_users'      => array(),
    ), $options);

    $users = array($data['user_id']);
    
    return $this->check_user_notification_options($users, $options);
  }

  public function get_title()
  {
    $user_id = $this->user_loader->load_user_by_username($this->get_data('sender_username'));
    return 'Friend request from: '.$this->user_loader->get_username($user_id, 'no_profile');
  }

  public function get_url()
  {
    return append_sid($this->phpbb_root_path . 'ucp.' . $this->php_ext, "i=florinp-messenger-ucp-ucp_friends_module&amp;action=requests");
  }

  public function get_avatar()
  {
    $user_id = $this->user_loader->load_user_by_username($this->get_data('sender_username'));
    return $this->user_loader->get_avatar($user_id);
  }

  public function get_redirect_url()
  {
    return $this->get_url();
  }

  public function get_email_template()
  {
    return false;
  }

  public function get_email_template_variables()
  {
    return array();
  }

  public function create_insert_array($data, $pre_create_data = array())
  {
    $this->set_data('request_id', $data['request_id']);
    $this->set_data('sender_id', $data['sender_id']);
    $this->set_data('sender_username', $data['sender_username']);
    $this->set_data('user_id', $data['user_id']);

    return parent::create_insert_array($data, $pre_create_data);
  }


}
