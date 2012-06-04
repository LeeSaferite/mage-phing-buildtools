<?php
class Lokey_SymlinkTask extends SymlinkTask
{
    protected $_createParent = true;
    protected $_relative = true;
    protected $_silentOnExisting = true;

    protected function symlink($target, $link)
    {
        if ($this->_createParent) {
            $linkParent = dirname($link);
            if (!is_dir($linkParent) && !is_file($linkParent)) {
                mkdir($linkParent, 0775, true);
            }
        }

        if ($this->_relative) {
            $target = $this->resolveToRelative($link, $target);
        }

        if ($this->_silentOnExisting) {
            if (file_exists($link) && !is_link($link)) {
                return false;
            }
        }

        return parent::symlink($target, $link);
    }

    protected function resolveToRelative($source, $target)
    {
        $common = dirname($source);
        $back = 0;
        while (substr($target, 0, strlen($common)) !== $common) {
            $common = dirname($common);
            $back++;
            if ($back > 100) {
                throw new Exception("Common parent not close enough ({$source}) ({$target})");
            }
        }

        return str_repeat('../', $back) . substr($target, strlen($common . '/'));
    }
}
