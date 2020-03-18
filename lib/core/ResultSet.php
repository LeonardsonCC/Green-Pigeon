<?php

class ResultSet extends ArrayObject {

    function __construct($array) {
        parent::__construct($array);
    }

    public function getNav() {
        if (isset($this->nav)) {
            $nav = $this->nav;
            return $nav();
        }
    }

}
