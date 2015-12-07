<?php
/**
 * Created by IntelliJ IDEA.
 * User: wimulkeman
 * Date: 06-06-15
 * Time: 23:20
 */

namespace CodedHeartInside\DataMining\Apriori;

use CodedHeartInside\DataMining\Apriori\Data\Input;
use CodedHeartInside\DataMining\Apriori\Data\Output;

/**
 * Class Confidence
 * @package CodedHeartInside\DataMining\Apriori
 */
class Confidence
{
    /**
     * @var Input
     */
    private $inputData;
    /**
     * @var Output
     */
    private $outputData;
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->inputData = new Input($configuration);
        $this->outputData = new Output($configuration);
    }

    /**
     * @return $this
     */
    public function createConfidenceOnThresholdFile()
    {
        $this->inputData->flushConfidenceRecords();

        foreach ($this->outputData->getThresholdItems() as $item) {
            if (sizeof($item['itemIds']) < 2) {
                continue;
            }

            $currentItemsetLength = sizeof($item['itemIds']);
            $checkItemsetLength = $currentItemsetLength - 1;

            if (empty($item['count'])) {
                continue;
            }
            $currentItemsetOccurenceCount = $item['count'];

            for ($i = 0; $i < $currentItemsetLength; $i ++) {
                $matchId = $item['itemIds'][$i];
                $matchAgainstSet = array_values(array_diff($item['itemIds'], array($matchId)));
                $matchingAgainstOccurenceCount = 0;

                foreach ($this->outputData->getThresholdItems() as $matchRecord) {
                    if (sizeof($matchRecord['itemIds']) < $checkItemsetLength) {
                        continue;
                    }

                    if (sizeof($matchRecord['itemIds']) > $checkItemsetLength) {
                        break;
                    }

                    if ($matchRecord['itemIds'] !== $matchAgainstSet) {
                        continue;
                    }

                    $matchingAgainstOccurenceCount = $matchRecord['count'];
                    break;
                }

                if (empty($matchingAgainstOccurenceCount)) {
                    continue;
                }

                $confidence = $currentItemsetOccurenceCount / $matchingAgainstOccurenceCount;

                if ($confidence < $this->projectConfiguration->getMinimumConfidence()) {
                    continue;
                }

                $confedenceRecord = array(
                    'if' => $matchAgainstSet,
                    'then' => $matchId,
                    'confidence' => $confidence,
                );
                $this->inputData->addConfidenceRecord($confedenceRecord);
            }
        }

        return $this;
    }

    /**
     * @return \Generator
     */
    public function getConfidenceRecords()
    {
        return $this->outputData->getConfidenceRecords();
    }
}