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

use CodedHeartInside\DataMining\Apriori\ConfigurationInterface;

/**
 * Class Configuration
 * @package CodedHeartInside\DataMining\Apriori
 */
class Configuration implements ConfigurationInterface
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
    private $displayDebugInformation = false;
    private $minimumThreshold= 2;
    private $minimumSupport = 0.1;
    private $minimumConfidence = 0.2;

    public function __construct()
    {
        $this->rootDirectory = __DIR__;
        $this->resourceDirectory = $this->rootDirectory . '/Resources';
        $this->tempDirectory = $this->resourceDirectory . '/temp';
        $this->projectConfigurationFilePath = $this->resourceDirectory . '/configuration.yml';
    }

    /**
     * @return string
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * @return string
     */
    public function getResourceDirectory()
    {
        return $this->resourceDirectory;
    }

    /**
     * @return string
     */
    public function getTempDirectory()
    {
        return $this->tempDirectory;
    }

    /**
     * @return string
     */
    public function getProjectConfigurationFilePath()
    {
        return $this->projectConfigurationFilePath;
    }

    public function setDisplayDebugInformation($displayDebug = false)
    {
        $this->displayDebugInformation = $displayDebug;

        return $this;
    }

    public function isDebugInformationDisplayed()
    {
        return $this->displayDebugInformation;
    }

    public function setMinimumThreshold($minimumThreshold)
    {
        $this->minimumThreshold = $minimumThreshold;

        return $this;
    }

    public function getMinimumThreshold()
    {
        return $this->minimumThreshold;
    }


    public function setMinimumSupport($minimumSupport)
    {
        $this->minimumSupport = $minimumSupport;

        return $this;
    }

    public function getMinimumSupport()
    {
        return $this->minimumSupport;
    }

    public function setMinimumConfidence($minimumConfidence)
    {
        $this->minimumConfidence = $minimumConfidence;

        return $this;
    }

    public function getMinimumConfidence()
    {
        return $this->minimumConfidence;
    }
}