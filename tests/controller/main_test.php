<?php

namespace florinp\messenger\tests\controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class main_test extends \phpbb_test_case {

    /**
     * @var \florinp\messenger\controller\main
     */
    protected $controller;

    public function setUp() {
        parent::setUp();
        /**
         * @var \phpbb\user $user Mock the user class
         */
        $user = $this->getMock('\phpbb\user', array(), array('\phpbb\datetime'));

        /**
         * @var \phpbb\request\request $request
         */
        $request = $this->getMockBuilder('\phpbb\request\request')
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var \florinp\messenger\models\main_model $model
         */
        $model = $this->getMockBuilder('\florinp\messenger\models\main_model')
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var \phpbb\notification\manager $notification_manager
         */
        $notification_manager = $this->getMockBuilder('\phpbb\notification\manager')
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var \florinp\messenger\libs\emojione $emojione
         */
        $emojione = $this->getMockBuilder('\florinp\messenger\libs\emojione')
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var \florinp\messenger\libs\upload $upload
         */
        $upload = $this->getMockBuilder('\florinp\messenger\libs\upload')
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var \florinp\messenger\libs\download $download
         */
        $download = $this->getMockBuilder('\florinp\messenger\libs\download')
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new \florinp\messenger\controller\main(
            $request,
            $user,
            $model,
            $notification_manager,
            $emojione,
            $upload,
            $download
        );
    }

    public function tearDown() {
        $this->controller = null;
        parent::tearDown();
    }

    public function publish_data() {
        return array(
            array(200, json_encode(array()))
        );
    }
    /**
     * @dataProvider publish_data
     */
    public function test_publish($status_code, $page_content) {
        $response = $this->controller->publish();
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals($status_code, $response->getStatusCode());
        $this->assertEquals($page_content, $response->getContent());
    }


    public function load_data() {
        return array(
            array(200, json_encode(array(
                'success' => false,
                'error' => 'The request is invalid'
            )))
        );
    }
    /**
     * @dataProvider load_data
     */
    public function test_load($status_code, $page_content) {
        $response = $this->controller->load();
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals($status_code, $response->getStatusCode());
        $this->assertEquals($page_content, $response->getContent());
    }


    public function updateMessages_data() {
        return array(
            array(200, json_encode(array('success' => false)))
        );
    }
}