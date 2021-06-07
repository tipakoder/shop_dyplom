<?php

namespace App\View;
use Core\Utils\Request;

class Error extends \Core\Base\View {
    public function codePage($code, $text){
        $options = [
            "code" => $code,
            "text" => $text
        ];
        $this->setup("error/codePage", "error");
        $this->setPageOptions($options);
        $this->addPageStyle("error");
        $this->setPageTitle("Ошибка {$code}");
        $this->render();
    }
}