<?php
/**
 * Created by IntelliJ IDEA.
 * User: wimulkeman
 * Date: 16-5-2015
 * Time: 16:17
 */

namespace CodedHeartInside\DataMining\Apriori;


use CodedHeartInside\DataMining\Apriori\Data\Input;
use CodedHeartInside\DataMining\Apriori\Data\Output;
use CodedHeartInside\DataMining\Apriori\Data\Transaction;
use CodedHeartInside\DataMining\Apriori\Data\Validation;
use CodedHeartInside\DataMining\Apriori\Exception\InvalidDataException;

class Threshold
{
    private $inputData;
    private $outputData;
    private $transaction;
    private $validation;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->inputData = new Input($configuration);
        $this->outputData = new Output($configuration);
        $this->transaction = new Transaction($configuration);
        $this->validation = new Validation();
    }

    public function createThresholdForDataSet()
    {
        $this->createThresholdForSingleItemCombination();
        $this->createThresholdCombinationsForNrOfItems(2);
    }

    private function createThresholdForSingleItemCombination()
    {
        $this->inputData->flushThresholdItems();
        $this->inputData->flushTmeporaryThresholdItems(1);

        $transactionItems = array();

        foreach ($this->outputData->getDataSetRecord() as $record) {
            foreach ($this->transaction->getTransactionItems($record) as $item) {
                if (! isset($transactionItems[$item])) {
                    $transactionItems[$item] = 0;
                }
                $transactionItems[$item] ++;
            }
        }

        ksort($transactionItems, SORT_NATURAL);

        foreach ($transactionItems as $itemId => $nrOfTransactions) {
            if ($nrOfTransactions >= $this->projectConfiguration->getMinimumThreshold()) {
                $this->inputData->addThresholdOnItemsAndCount(array($itemId), $nrOfTransactions);
                $this->inputData->addTemporaryThresholdOnItemsAndCount(array($itemId), $nrOfTransactions);
            }
        }
    }

    private function createThresholdCombinationsForNrOfItems($nrOfItems = 2)
    {
        if (! is_numeric($nrOfItems)) {
            throw new \InvalidArgumentException('The provided param should be a numeric format');
        }

        $previousThresholdItemSetSize = $nrOfItems - 1;

        try {
            $fileStatus = $this->validation
                ->isFileEmpty($this->projectConfiguration->getTempDirectory() .
                    "/threshold_{$previousThresholdItemSetSize}_temp.txt"
                );
            if ($fileStatus) {
                return;
            }
        } catch (InvalidDataException $e) {
            if ($this->projectConfiguration->isDebugInformationDisplayed()) {
                throw new InvalidDataException($e->getMessage());
            }

            return;
        }

        $this->inputData->flushTmeporaryThresholdItems($nrOfItems);

        foreach ($this->outputData->getThresholdItemsOnItemSetSize($previousThresholdItemSetSize) as $thresholdRecords) {
            $currentOldRecord = $thresholdRecords['currentRecord']['itemIds'];
            $nextOldRecord = $thresholdRecords['nextRecord']['itemIds'];
            if ($previousThresholdItemSetSize === 1) {
                $newItemSet = array_merge($currentOldRecord, $nextOldRecord);
            } else {
                if (array_slice($currentOldRecord, 0, -1) !== array_slice($nextOldRecord, 0, -1)) {
                    continue;
                }

                $newItemSet = array_merge($currentOldRecord, array_slice($nextOldRecord, -1));
            }

            $nrOfTransactions = 0;
            foreach ($this->outputData->getDataSetRecord() as $record) {
                if (sizeof(array_intersect($record, $newItemSet)) != $nrOfItems) {
                    continue;
                }

                $nrOfTransactions ++;
            }

            if ($nrOfTransactions < $this->projectConfiguration->getMinimumThreshold()) {
                continue;
            }

            $this->inputData->addThresholdOnItemsAndCount($newItemSet, $nrOfTransactions);
            $this->inputData->addTemporaryThresholdOnItemsAndCount($newItemSet, $nrOfTransactions);
        }

        $this->inputData->flushTmeporaryThresholdItems($previousThresholdItemSetSize);

        $this->createThresholdCombinationsForNrOfItems($nrOfItems + 1);
    }
}
