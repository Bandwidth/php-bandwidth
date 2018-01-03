<?php

trait CheckBody {
    public static $bodies = [];
    public static $body_index = 0;

    static function check_body($body) {
        $body = preg_replace('/(\s{2,})/', "", $body);
        $body = preg_replace('/(\r|\n)/', "", $body);

        $expected_body = self::$bodies[self::$body_index++];
        $expected_body = preg_replace ('/(\s{2,})/', "", $expected_body);
        $expected_body = preg_replace ('/(\r|\n)/', "", $expected_body);

        echo $body."\n";
        echo $expected_body;

        return $body == $expected_body;
    }
}
