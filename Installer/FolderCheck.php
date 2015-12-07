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

namespace CodedHeartInside\DataMining\Apriori\Installer;

/**
 * Class FolderCheck
 * @package CodedHeartInside\DataMining\Apriori\Installer
 */
class FolderCheck
{
    public function checkIfTemporaryDirectoryAvailable($tempDirectory)
    {
        if (! is_dir($tempDirectory)) {
            $this->createDirectory($tempDirectory);
        }
    }

    private function createDirectory($tempDirectory)
    {
        mkdir($tempDirectory, 0755);
    }
}