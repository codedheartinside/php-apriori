<?php
/**
 * This file is part of the Bearwulf package.
 *
 * (c) Wim Ulkeman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For Contributing on the code, please view the CONTRIBUTING file that was
 * distrubuted with this source code
 */

namespace Bearwulf\DataMining\Apriori\Data;

use Bearwulf\DataMining\Apriori\Data\OutputInterface;
use Bearwulf\DataMining\Apriori\ConfigurationInterface;


class Output implements OutputInterface
{
    /**
     * @var ConfigurationInterface
     */
    private $projectConfiguration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->parser = new Parse();
    }
    
    public function getDataSetRecord()
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/data_set.txt';

        $dataSetFile = fopen($tempFile, 'r');

        while ($line = fgets($dataSetFile)) {
            if (empty($line)) {
                break;
            }

            yield $this->parser->unparse($line);
        }

        fclose($dataSetFile);
    }


}