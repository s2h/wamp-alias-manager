<?php


class Alias
{
    private $path;

    function __construct($path)
    {
        $this->path = $this->_fixPath($path);
    }

    public function createNewAlias($name, $pathTo)
    {
        $pathTo = $this->_fixPath($pathTo);
        $fileNamePath = $this->path . $name . '.conf';
        if ( ! file_exists($fileNamePath)) {
            touch($fileNamePath);
            $content = '';
            $content .= PHP_EOL . 'Alias /' . $name . ' "' . $pathTo . '"';
            $content .= PHP_EOL;
            $content .= PHP_EOL . '<Directory "' . $pathTo . '">';
            $content .= PHP_EOL . '    Options Indexes FollowSymLinks MultiViews';
            $content .= PHP_EOL . '    Require all granted';
            $content .= PHP_EOL . '    AllowOverride all';
            $content .= PHP_EOL . '        Order allow,deny';
            $content .= PHP_EOL . '    Allow from all';
            $content .= PHP_EOL . '</Directory>';
            file_put_contents($fileNamePath, $content);
        }

        return true;
    }

    private function _fixPath($path)
    {
        return $path[ strlen($path) - 1 ] == '/' ? $path : $path . '/';
    }

    public function deleteAlias($name)
    {
        $fileNamePath = $this->path . $name . '.conf';
        if (file_exists($fileNamePath)) {
            unlink($fileNamePath);
        }

        return true;
    }

    public function listAllAliases()
    {
        $files = scandir($this->path);
        $aliases = array();
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') $aliases[] = $file;
        }

        return $aliases;
    }

    public function viewAlias($name)
    {
        $fileNamePath = $this->path . $name . '.conf';
        $output = '';
        if (file_exists($fileNamePath)) {
            $output = htmlentities(file_get_contents($fileNamePath));
        }

        return $output;
    }
}