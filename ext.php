<?php

namespace florinp\messenger;

class ext extends \phpbb\extension\base
{

  public function enable_step($old_state)
  {
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
    return parent::enable_step($old_state);
  }

  public function disable_step($old_state)
  {
    global $phpbb_root_path;
    $database = $phpbb_root_path . 'store/messenger.db';
    if(is_file($database))
    {
      unlink($database);
    }
    return true;
  }

}
