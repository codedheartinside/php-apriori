<?php
/**
 * This file is part of the CodedHeartInside package.
 *
 * (c) Wim Ulkeman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For Contributing on the code, please view the CONTRIBUTING file that was
 * distrubuted with this source code
 */

namespace CodedHeartInside\DataMining\Apriori;

use CodedHeartInside\DataMining\Apriori\Installer\FolderCheck;
//use CodedHeartInside\DataMining\Apriori\Configuration;
use CodedHeartInside\DataMining\Apriori\Exception\MissingDirectoryException;

/**
 * Class Installer
 * @package CodedHeartInside\DataMining\Apriori
 */
class Installer implements InstallerInterface
{
    /**
     * @var string
     */
    private $rootDirectory = '';
    /**
     * @var string
     */
    private $resourceDirectory = '';
    /**
     * @var string
     */
    private $tempDirectory = '';
    /**
     * @var string
     */
    private $projectConfigurationFilePath = '';

    /**
     *
     */
    public function __construct()
    {
        $this->rootDirectory = __DIR__;
        $this->resourceDirectory = $this->rootDirectory . '/Resources';
        $this->tempDirectory = $this->resourceDirectory . '/temp';
        $this->projectConfigurationFilePath = $this->resourceDirectory . '/configuration.yml';
    }

    /**
     * This function can be called to setup the environment needed for running this
     * application
     *
     * @throws Exception
     */
    public function createRunningEnvironment()
    {
        $folderCheck = new FolderCheck();
        $folderCheck->checkIfTemporaryDirectoryAvailable($this->tempDirectory);
    }

    /**
     * @param string $path
     * @return $this
     * @throws \Exception
     */
    public function setRootDirectory($path = '')
    {
        if (! is_dir($path)) {
            throw new MissingDirectoryException('The provided path is non-existing');
        }

        $this->rootDirectory = $path;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     * @throws \Exception
     */
    public function setResourceDirectory($path = '')
    {
        if (! is_dir($path)) {
            throw new MissingDirectoryException('The provided path is non-existing');
        }

        $this->resourceDirectory = $path;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     * @throws \Exception
     */
    public function setTempDirectory($path = '')
    {
        if (! is_dir($path)) {
            throw new MissingDirectoryException('The provided path is non-existing');
        }

        $this->tempDirectory = $path;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     * @throws \Exception
     */
    public function setProjectConfigurationFilePath($path = '')
    {
        if (! is_dir($path)) {
            throw new MissingDirectoryException('The provided path is non-existing');
        }

        $this->projectConfigurationFilePath = $path;

        return $this;
    }
}