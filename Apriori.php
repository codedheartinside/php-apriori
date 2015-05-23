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

namespace Bearwulf\DataMining\Apriori;

use Bearwulf\DataMining\Apriori\ConfigurationInterface;
use Bearwulf\DataMining\Apriori\Threshold;
use Bearwulf\DataMining\Apriori\Support;


/**
 * Class Apriori
 * @package Bearwulf\DataMining\Apriori
 *
 * @api
 */
class Apriori
{
    private $projectConfiguration;
    private $threshold;
    private $support;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->threshold = new Threshold($configuration);
        $this->support = new Support($configuration);
    }

    public function run()
    {
        $this->threshold->createThresholdForDataSet();
        $this->support->createSupportOnThresholdFile();
    }
}