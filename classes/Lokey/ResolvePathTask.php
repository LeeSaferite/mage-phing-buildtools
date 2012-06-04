<?php
/**
 * This is a copy/re-implementation of the original ResolvePathTask from phing since they used private variables.
 */
class Lokey_ResolvePathTask extends Task
{
    /** Name of property to set. */
    protected $_propertyName;

    /** The [possibly] relative file/path that needs to be resolved. */
    protected $_file;

    /** Base directory used for resolution. */
    protected $_dir;

    /** Whether to force overwrite of existing property. */
    protected $_override = true;

    /**
     * Set the name of the property to set.
     *
     * @param string $v Property name
     *
     * @return void
     */
    public function setPropertyName($v)
    {
        $this->_propertyName = $v;
    }

    /**
     * Sets a base dir to use for resolution.
     *
     * @param PhingFile $d
     */
    function setDir(PhingFile $d)
    {
        $this->_dir = $d;
    }

    /**
     * Sets a path (file or directory) that we want to resolve.
     * This is the same as setFile() -- just more generic name so that it's
     * clear that you can also use it to set directory.
     *
     * @param string $f
     *
     * @see setFile()
     */
    function setPath($f)
    {
        $this->_file = $f;
    }

    /**
     * Sets a file that we want to resolve.
     *
     * @param string $f
     */
    function setFile($f)
    {
        $this->_file = $f;
    }

    function setOverride($v)
    {
        $this->_override = (boolean)$v;
    }

    /**
     * Perform the resolution & set property.
     */
    public function main()
    {
        if (!$this->_propertyName) {
            throw new BuildException("You must specify the propertyName attribute", $this->getLocation());
        }

        // Currently only files are supported
        if ($this->_file === null) {
            throw new BuildException("You must specify a path to resolve", $this->getLocation());
        }

        $fs = FileSystem::getFileSystem();

        // if dir attribute was specified then we should
        // use that as basedir to which file was relative.
        // -- unless the file specified is an absolute path
        if ($this->_dir !== null && !$fs->isAbsolute(new PhingFile($this->_file))) {
            $resolved = new PhingFile($this->_dir->getPath(), $this->_file);
        } else {
            // otherwise just resolve it relative to project basedir
            $resolved = $this->project->resolveFile($this->_file);
        }

        $this->log("Resolved " . $this->_file . " to " . $resolved->getAbsolutePath(), Project::MSG_INFO);

        if ($this->_override) {
            if($this->project->getUserProperty($this->_propertyName) !== null) {
                $this->project->setUserProperty($this->_propertyName, $resolved->getAbsolutePath());
            } else {
                $this->project->setProperty($this->_propertyName, $resolved->getAbsolutePath());
            }
        } else {
            $this->project->setNewProperty($this->_propertyName, $resolved->getAbsolutePath());
        }
    }
}
