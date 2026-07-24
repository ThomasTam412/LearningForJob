<?php
class Flash {
    public function has($type) {
        return isset($_SESSION["flash"][$type]);
    }
    public function set($type, $msg) {
        $_SESSION["flash"][$type] = $msg;
    }

    public function get($type) {
        if (!$this->has($type)) { return null; }
        $msg = $_SESSION["flash"][$type];
        unset($_SESSION["flash"][$type]);
        return $msg;
    }
}