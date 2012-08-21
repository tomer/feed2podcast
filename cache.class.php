<?php

class Cache {
    function __construct($path = './') {
    $this->path = $path;
    }
    
    private function encode_url($url) {
        return $this->path .'/'. md5($url);
    }
    
    public function is_cached($item) {
        $filename = $this->encode_url($item);
        if (!file_exists($filename)) return false;
        else {
            if (filemtime($filename) < time() - 60) // Is older than one hour
                return false; // needs to be purged. 
            else return true; // Cached!
        }
          
    }
    
    public function write($item, $content) {
        $filename = $this->encode_url($item);

//        if (is_writable($filename)) {
            if (!$handle = fopen($filename, 'w')) {
                echo ("Cannot open file\n");
                exit;
            }

        if (fwrite($handle, $content) === FALSE) {
            echo ("Cannot write file\n");
            exit;
        }

        fclose($handle);

//        } else {
//            echo ("file is not writable or does not exists\n");
//        }
    }
    
    public function read($item) {
        $filename = $this->encode_url($item);
        $handle = fopen($filename, "rb");
        $content = fread($handle, filesize($filename));
        fclose($handle);
        return ($content);
    }
}


$cache = new Cache();
$url = 'localhost/?';
if ($cache->is_cached($url)) {
    echo ("cached\n");
    echo ($cache->read($url));
}
else {
    echo ("not cached\n");
    $cache->write($url, "Hello World!\n");
    echo ("Done!\n");
}

