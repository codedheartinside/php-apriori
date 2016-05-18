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
use CodedHeartInside\DataMining\Apriori\Threshold;
use CodedHeartInside\DataMining\Apriori\Support;


/**
 * Class Apriori
 * @package CodedHeartInside\DataMining\Apriori
 *
 * @api
 */
class Apriori
{
    /**
     * @var \CodedHeartInside\DataMining\Apriori\ConfigurationInterface
     */
    private $projectConfiguration;

    /**
     * @var \CodedHeartInside\DataMining\Apriori\Threshold
     */
    private $threshold;

    /**
     * @var \CodedHeartInside\DataMining\Apriori\Support
     */
    private $support;

    private $confidence;

    /**
     * @param \CodedHeartInside\DataMining\Apriori\ConfigurationInterface $configuration
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
     */
    public function run()
    {
        $this->threshold->createThresholdForDataSet();
        $this->support->createSupportOnThresholdFile();
        $this->confidence->createConfidenceOnThresholdFile();
    }

    /**
     * This function is called to retrieve the records with the support percentage for the provided dataset after
     * the algorithm has been run.
     *
     * @yield The record with the item set and the support
     */
    public function getSupportRecords()
    {
        return $this->support->getSupportRecords();
    }

    /**
     * Tihs function is called to retrieve the records with the confidence percentage between items in item sets
     *
     * @yield The record with the item set and the underlying confidence
     */
    public function getConfidenceRecords()
    {
        return $this->confidence->getConfidenceRecords();
    }
}