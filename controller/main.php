<?php

namespace florinp\messenger\controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use phpbb\request\request;
use phpbb\user;
use florinp\messenger\models\main_model;
use phpbb\notification\manager;
use florinp\messenger\libs\upload;
use florinp\messenger\libs\download;

class main
{

    protected $user;
    protected $model;
    protected $request;
    protected $notification_manager;
    protected $upload;
    protected $download;

    public function __construct(
        request $request,
        user $user,
        main_model $model,
        manager $notification_manager,
        upload $upload,
        download $download
    )
    {
        $this->request = $request;
        $this->user = $user;
        $this->model = $model;
        $this->notification_manager = $notification_manager;
        $this->upload = $upload;
        $this->download = $download;
    }

    public function handle()
    {
    }

    public function index()
    {

    }

    public function publish()
    {
        $text = $this->request->variable('text', '', true);
        $receiver_id = $this->request->variable('receiver_id', 0);
        $sender_id = $this->user->data['user_id'];

        $response = array();
        if ($receiver_id != 0 && trim($text) != '') {
            $text = htmlspecialchars($text);
            $text = str_replace(array("\n", "\r"), '', $text);

            $message = array(
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'text' => $text,
                'sentAt' => time()
            );

            if ($id = $this->model->sendMessage($message)) {
                $lastMessage = $this->model->getMessageById($id);
                $response = array('success' => true, 'message' => $lastMessage);
            } else {
                $response = array(
                    'succes' => false,
                    'error' => 'An error has been ocurred!'
                );
            }
        }

        return new JsonResponse($response, 200);
    }

    public function getFile($id)
    {
        $id = explode('_', $id)[1];
        $file = $this->model->getFileById($id);
        $this->download->setFile($file['file']);
        $this->download->sendDownload();
    }

    public function sendFile()
    {
        $receiver_id = $this->request->variable('receiver_id', 0);
        $sender_id = $this->user->data['user_id'];

        $response = array();
        $file = $this->request->file('file');
        if ($receiver_id != 0 && !empty($file)) {
            if ($file['error'] == 0) {
                $this->upload->file($file);
                $this->upload->set_allowed_mime_types(array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                    'application/pdf',
                    'application/x-rar-compressed',
                    'application/zip',
                    'application/x-7z-compressed',
                    'text/plain'
                ));
                $results = $this->upload->upload();
                if (isset($results['errors']) && count($results['errors'])) {
                    $response = array(
                        'success' => false,
                        'errors' => $results['errors']
                    );
                } else {
                    $data = array(
                        'sender_id' => $sender_id,
                        'receiver_id' => $receiver_id,
                        'fileName' => $results['original_filename'],
                        'file' => $results['filename'],
                        'type' => $results['mime'],
                    );
                    if ($id = $this->model->sendFile($data)) {
                        $lastFile = $this->model->getFileById($id);
                        $response = array(
                            'success' => true,
                            'file' => $lastFile
                        );
                    } else {
                        $response = array(
                            'succes' => false,
                            'error' => 'An error has been ocurred!'
                        );
                    }
                }
            } else {
                $response = array(
                    'succes' => false,
                    'error' => $file['error']
                );
            }
        }

        return new JsonResponse($response, 200);
    }

    public function load()
    {
        $friend_id = $this->request->variable('friend_id', 0);

        if ($friend_id > 0) {
            $messages = $this->model->getMessages($friend_id);
            return new JsonResponse($messages, 200);
        }
        return new JsonResponse(array('success' => false, 'error' => 'The request is invalid'), 200);
    }

    public function updateMessages()
    {
        $friend_id = $this->request->variable('friend_id', 0);
        if ($friend_id > 0) {
            $newVal = $this->model->updateMessagesStatus($friend_id);
            return new JsonResponse(array('success' => true, 'newVal' => $newVal), 200);
        }
        return new JsonResponse(array('success' => false), 200);
    }

    public function checkForNewMessages()
    {
        $friend_id = $this->request->variable('friend_id', 0);
        if ($friend_id > 0) {
            $messages = $this->model->getInboxFromId($friend_id);
            return new JsonResponse(array('success' => true, 'messages' => $messages), 200);
        }
        return new JsonResponse(array('success' => false), 200);
    }

    public function getFriends()
    {
        $friends = $this->model->getFriends();
        $friends_online = array_filter($friends, function ($friend) {
            return $friend['user_status'] != 0;
        });

        $response = array(
            'friends_online' => count($friends_online),
            'friends_list' => $friends
        );

        return new JsonResponse($response, 200);
    }

    public function getEmoticons()
    {
        $response = array();
        return new JsonResponse($response, 200);
    }

}
