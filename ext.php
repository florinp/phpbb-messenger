<?php

namespace florinp\messenger;

// Test

class ext extends \phpbb\extension\base
{

  public function enable_step($old_state)
  {

    switch($old_state)
    {
      case '':
        $db = new \florinp\messenger\libs\database();
        $db->exec("
          CREATE TABLE IF NOT EXISTS messages (
            `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `sender_id` INTEGER NOT NULL,
            `receiver_id` INTEGER NOT NULL,
            `text` TEXT NOT NULL,
            `newMsg` INTEGER DEFAULT 0,
            `sentAt` INTEGER NOT NULL
          );
        ");
        return 'notifications';
      break;

      case 'notifications':
        $phpbb_notifications = $this->container->get('notification_manager');
        $phpbb_notifications->enable_notifications('florinp.messenger.notification.type.friend_request');
        return 'step2';
      break;

      default:
        return parent::enable_step($old_state);
      break;
    }

  }

  public function disable_step($old_state)
  {
    switch($old_state)
    {
      case '':
        $phpbb_notifications = $this->container->get('notification_manager');
        $phpbb_notifications->disable_notifications('florinp.messenger.notification.type.friend_request');
        return 'notifications';
      break;

      default:

        return parent::disable_step($old_state);
      break;
    }
  }

  public function purge_step($old_state)
  {
    global $phpbb_root_path;

    $database = $phpbb_root_path . 'store/messenger.db';
    if(is_file($database)) {
      unlink($database);
    }
    return parent::purge_step($old_state);

  }

}
