<?php

namespace Raideer\Tweech\Config;

class ConfigLoader
{
    protected $configPath;

    public function __construct($configPath)
    {
        $this->configPath = $configPath;
    }

    public function load($file, $property)
    {
        $items = [];

        $path = $this->configPath;

        $fileLocation = "$path/$file.php";

        if (file_exists($fileLocation)) {
            $items = $this->getRequire($fileLocation);
        }

        return $items;
    }

    protected function getRequire($filePath)
    {
        return require $filePath;
    }
}
