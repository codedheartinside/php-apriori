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


interface InstallerInterface
{
    public function createRunningEnvironment();

    public function setRootDirectory($path = '');

    public function setResourceDirectory($path = '');

    public function setTempDirectory($path = '');
}