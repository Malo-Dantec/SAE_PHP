<?php
declare(strict_types=1);

namespace View;

final class Template {
    private string $path;
    private string $layout;
    private string $content;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getLayout():string {
        return $this->layout;
    }

    public function getContent():string {
        return $this->content;
    }

    public function setLayout(string $layout):self {
        $this->layout = $layout;
        return $this;
    }

    public function setContent(string $content):self {
        $this->content = $content;
        return $this;
    }

    public function compile():string { // en fonction du chemin, afficher la page avec Buffer(ob_start, ob_get_clean)
        $content = $this->getContent();
        ob_start();
        require sprintf("%s/%s.php", $this->getPath(), $this->getLayout());
        return ob_get_clean();
    }
}




?>