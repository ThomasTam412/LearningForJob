<?php
session_start();
class Flash {
    public function has($type) {
        return $_SESSION["flash"][$type];
    }
    public function set($type, $msg) {
        $_SESSION["flash"][$type] = $msg;
    }
    public function get($type) {
        if ($this->has($type)) {
            $msg = $_SESSION["flash"][$type];
            unset($_SESSION["flash"][$type]);
            return $msg;
        }
        return null;
    }
}