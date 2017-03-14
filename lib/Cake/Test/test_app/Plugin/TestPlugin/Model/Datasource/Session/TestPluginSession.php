<?php
/**
 * Test suite plugin session handler
 */

use App\Model\Datasource\Session\SessionHandlerInterface;

class TestPluginSession implements SessionHandlerInterface {

	public function open() {
		return true;
	}

	public function close() {
		return true;
	}

	public function read($id) {
		return '';
	}

	public function write($id, $data) {
		return true;
	}

	public function destroy($id) {
		return true;
	}

	public function gc($expires = null) {
		return true;
	}

}
