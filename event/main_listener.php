<?php

namespace florinp\messenger\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RequestContext;

use phpbb\template\template;
use florinp\messenger\models\main_model;
use florinp\messenger\models\friends_model;
use phpbb\user;
use phpbb\symfony_request;

class main_listener implements EventSubscriberInterface
{

    static public function getSubscribedEvents()
    {
        return array(
            'core.user_setup' => 'load_language_on_setup',
            'core.page_footer' => 'get_language',
            'core.page_header' => 'check_login',
            'core.memberlist_view_profile' => 'check_friends'
        );
    }


    /* @var \phpbb\template\template */
    protected $template;

    /**
     * @var main_model
     */
    protected $model;

    /**
     * @var friends_model
     */
    protected $friends_model;

    /**
     * @var user
     */
    protected $user;

    /**
     * @var symfony_request
     */
    protected $symfony_request;

    public function __construct(
        template $template,
        main_model $model,
        friends_model $friends_model,
        user $user,
        symfony_request $symfony_request
    )
    {
        $this->template = $template;
        $this->model = $model;
        $this->friends_model = $friends_model;
        $this->user = $user;
        $this->symfony_request = $symfony_request;
    }

    public function get_language()
    {
        $this->template->assign_vars(array(
            'CHAT_LANGUAGE' => $this->user->lang_name
        ));
    }

    public function load_language_on_setup($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'florinp/messenger',
            'lang_set' => 'common',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }

    public function check_login()
    {
        $s_enable_messenger = 0;
        if (in_array($this->user->data['user_type'], array(USER_NORMAL, USER_FOUNDER))) {
            $s_enable_messenger = 1;
        }
        $this->template->assign_var('S_ENABLE_MESSENGER', $s_enable_messenger);
    }

    public function check_friends($event)
    {
        $context = new RequestContext();
        $context->fromRequest($this->symfony_request);
        $baseUrl = generate_board_url(true) . $context->getBaseUrl();

        $scriptName = $this->symfony_request->getScriptName();
        $scriptName = substr($scriptName, -1, 1) == '/' ? '' : utf8_basename($scriptName);

        if ($scriptName != '') {
            $baseUrl = str_replace('/' . $scriptName, '', $baseUrl);
        }

        $user_id = $event['member']['user_id'];
        $sender_id = $this->user->data['user_id'];
        $request = $this->friends_model->get_request_by_sender_id($sender_id);
        $check_friend = $this->friends_model->check_friend(array(
            'user_id' => $this->user->data['user_id'],
            'friend_id' => $user_id,
        ));
        $check_request = $this->friends_model->check_request(array(
            'user_id' => $user_id,
            'sender_id' => $this->user->data['user_id']
        ));
        $check_request_confirm = $this->friends_model->check_request(array(
            'user_id' => $this->user->data['user_id'],
            'sender_id' => $user_id
        ));
        $check_widget = true;
        if ($user_id == $this->user->data['user_id']) $check_widget = false;
        $this->template->assign_vars(array(
            'U_USER_ID' => $user_id,
            'U_CHECK_FRIEND' => $check_friend,
            'U_CHECK_REQUEST' => $check_request,
            'U_CHECK_REQUEST_CONFIRM' => $check_request_confirm,
            'U_CHECK_WIDGET' => $check_widget,
            'U_REQUEST_ID' => $request['request_id'],
            'BASE_URL' => $baseUrl
        ));
    }
}
