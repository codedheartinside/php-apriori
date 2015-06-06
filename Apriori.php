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
    /**
     * @var \Bearwulf\DataMining\Apriori\ConfigurationInterface
     */
    private $projectConfiguration;

    /**
     * @var \Bearwulf\DataMining\Apriori\Threshold
     */
    private $threshold;

    /**
     * @var \Bearwulf\DataMining\Apriori\Support
     */
    private $support;

    private $confidence;

    /**
     * @param \Bearwulf\DataMining\Apriori\ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->threshold = new Threshold($configuration);
        $this->support = new Support($configuration);
        $this->confidence = new Confidence($configuration);
    }

    /**
     * This function is called after the configuration for the algorithm is set. The function will return the
     * given data set and return the support and confidence of each transaction.
     *
     * @yield Each record of the provided data set with the corresponding support and confidence
     */
    public function run()
    {
//        $this->threshold->createThresholdForDataSet();
//        $this->support->createSupportOnThresholdFile();
        $this->confidence->createConfidenceOnThresholdFile();
    }
}