<?php

declare(strict_types=1);

namespace App;

class View {

    public function render(array $pages, array $params){
        require_once ("templates/layout.php");
    }
}