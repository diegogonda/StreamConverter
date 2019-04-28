<?php

namespace handler;

class CSV implements HandlerInterface {

    public $delimiter = ";";
    public $maxLineLenght = 0;
    public $enclosure = '"';
    public $escape = '\\';
    public $codificarUTF8 = true;
    public $limpiarKeys = false;
    public $cabecera = true;
    public $contentType = "text/csv";

    public function read(string $source) {
        $fileExists = file_exists($source);
        $handle = fopen($source, 'r');
        if ($fileExists && $handle !== FALSE) {
            $arr = [];
            $i = 0;
            while (($lineArray = fgetcsv($handle, $this->maxLineLenght, $this->delimiter, $this->enclosure, $this->escape)) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $original = $lineArray[$j];
                    $utf8 = ($this->codificarUTF8) ? utf8_encode($original) : $original;
                    $utf8SinEspacios = trim($utf8);
                    $arr[$i][$j] = $utf8SinEspacios;
                }
                $i++;
            }
            fclose($handle);
        }
        $newArray = [];
        $keys = [];
        $count = count($arr) - 1;
        $labels = array_shift($arr);
        foreach ($labels as $label) {
            if ($this->limpiarKeys) {
                $temp = Regex::strAid($label);
                $label = strtolower($temp);
            }
            $keys[] = $label;
        }
        $keys[] = 'id';
        for ($i = 0; $i < $count; $i++) {
            $arr[$i][] = $i;
        }
        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $arr[$j]);
            $newArray[$j] = (object) $d;
        }
        return $newArray;
    }

    public function write(string $source, $content) {
        $csv = '';
        if (count($content) > 0) {
            $fileHandler = fopen($source, 'w');
            $primeraLinea = $content[0];
            if (is_object($primeraLinea)) {
                $primeraLinea = (array) $primeraLinea;
            }
            if ($this->cabecera) {
                $primeraLineaClaves = array_keys($primeraLinea);
                fputcsv($fileHandler, $primeraLineaClaves, $this->delimiter, $this->enclosure, $this->escape);
            }
            foreach ($content as $value) {
                fputcsv($fileHandler, (array) $value, $this->delimiter, $this->enclosure, $this->escape);
            }

            rewind($fileHandler);
            $csv = stream_get_contents($fileHandler);

            fclose($fileHandler);
        }
        return $csv;
    }

    public function toStream($content) {
        $source = "php://temp";
        $csv = $this->write($source, $content, true);
        return $csv;        
    }

    public function getContentType() {
        return $this->contentType;        
    }

}
