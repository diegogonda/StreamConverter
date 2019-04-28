<?php

namespace handler;

class JSON implements HandlerInterface {

    public $options = JSON_UNESCAPED_UNICODE;
    public $depth = 512;
    public $contentType = "application/json";

    public function read(string $source) {
        $contenido = file_get_contents($source);
        $decoded = json_decode($contenido);
        return $decoded;
    }

    public function write(string $source, $content) {
        $string = json_encode($content,  $this->options, $this->depth);
        $writed = file_put_contents($source, $string);
        if (!$writed) {
            throw new \Exception("No se pudo insertar los datos en $source");
        }
        return $string;
    }

    public function toStream($content) {
        $source = "php://temp";
        $json = $this->write($source, $content, true);
        return $json;        
    }

    public function getContentType() {
        return $this->contentType;        
    }

}