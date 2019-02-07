<?php
class Session {
    public static function is_user($mail) {
        return (isset($_SESSION['mail']) && ($_SESSION['mail'] == $mail));
    }
    public static function is_connected() {
        return (isset($_SESSION['mail']));
    }
    public static function is_employe() {
        return (isset($_SESSION['role']) && intval($_SESSION['role']) == 0);
    }
    public static function is_manager() {
        return (isset($_SESSION['role']) && intval($_SESSION['role']) == 1);
    }
    public static function is_directeur() {
        return (isset($_SESSION['role']) && intval($_SESSION['role']) == 2);
    }
}

?>