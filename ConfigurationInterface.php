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


interface ConfigurationInterface
{
    public function getRootDirectory();

    public function getResourceDirectory();

    public function getTempDirectory();

    public function getProjectConfigurationFilePath();

    public function setDisplayDebugInformation($displayDebug = false);

    public function isDebugInformationDisplayed();

    public function setMinimumThreshold($minimumThreshold);

    public function getMinimumThreshold();

    public function setMinimumSupport($minimumSupport);

    public function getMinimumSupport();

    public function setMinimumConfidence($minimumConfidence);

    public function getMinimumConfidence();
}