<?php
class ControllerExtensionOpenbayOpenbay extends Controller {
    public function index() {
        $this->response->addHeader('Cache-Control: no-cache, must-revalidate');
        $this->response->addHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        $this->response->addHeader('Content-type: application/json; charset=utf-8');
        $this->response->addHeader('X-Openbay-Header: hello');

        http_response_code(200);
        $this->response->setOutput(json_encode(array('reply' => 'hello')));
    }
}
