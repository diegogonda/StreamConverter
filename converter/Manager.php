<?php

namespace converter;

use handler\HandlerInterface;

class Manager {

    public function convert(HandlerInterface $inputHandler, HandlerInterface $outputHandler, string $inputSource, string $outputFilename) {
        $content = $inputHandler->read($inputSource);
        return $outputHandler->write($outputFilename, $content);
    }
    
}