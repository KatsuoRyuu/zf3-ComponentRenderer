<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRenderer;

class Compiler
{

    private $cachePath = "/data/cache/components/";
    private $compiled = false;
    private $file = null;
    private $cacheFile = null;
    private $noCache = false;
    /**
     *
     * @var \ComponentRenderer\ComponentParser
     */
    private $componentParser = null;

    public function __construct($parser)
    {
        $this->componentParser = $parser;
    }

    public function loadFile($file)
    {
        $this->file = $file;
        $this->compiled = false;
        $this->getCacheFile();
        return $this;
    }

    public function compile()
    {
        if ($this->compiled) {
            return $this->cacheFile;
        }

        $content = file_get_contents($this->file);
        $content = $this->componentParser->parse($content);
        $this->storeCache($content);
        return $this->getCacheFile();
    }

    private function getCacheFile()
    {
        $modulePath = getcwd() . '/module';
        $filePath = str_replace($modulePath, $this->cachePath, $this->file);
        $this->cacheFile = getcwd() . $filePath;

        $dirpath = dirname($this->cacheFile);

        if (! file_exists($dirpath) && ! is_dir($dirpath)) {
            @mkdir($dirpath, 0777, true);
        }

        if (file_exists($this->cacheFile) && ! $this->noCache) {
            if (filemtime($this->file) < filemtime($this->cacheFile)) {
                $this->compiled = true;
            }
        }

        return $this->cacheFile;
    }

    private function storeCache($content)
    {
        file_put_contents($this->getCacheFile(), $content);
    }
}
