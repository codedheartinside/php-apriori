<?php
/**
 * Created by IntelliJ IDEA.
 * User: wimulkeman
 * Date: 16-5-2015
 * Time: 16:17
 */

namespace Bearwulf\DataMining\Apriori;


use Bearwulf\DataMining\Apriori\Data\Input;
use Bearwulf\DataMining\Apriori\Data\Output;
use Bearwulf\DataMining\Apriori\Data\Transaction;

class Threshold
{
    private $inputData;
    private $outputData;
    private $transaction;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->inputData = new Input($configuration);
        $this->outputData = new Output($configuration);
        $this->transaction = new Transaction($configuration);
    }

    public function createThresholdForSingleItemCombination()
    {
        $this->inputData->flushThresholdItems();
        $this->inputData->flushTmeporaryThresholdItems(1);

        $transactionItems = array();

        foreach ($this->outputData->getDataSetRecord() as $record) {
            // An item only needs one presents in an transaction
            $record = array_unique($record);

            foreach ($this->transaction->getTransactionItems($record) as $item) {
                if (! isset($transactionItems[$item])) {
                    $transactionItems[$item] = 0;
                }
                $transactionItems[$item] ++;
            }
        }

        ksort($transactionItems, SORT_NATURAL);

        foreach ($transactionItems as $itemId => $itemCount) {
            if ($itemCount >= $this->projectConfiguration->getMinimumThreshold()) {
                $this->inputData->addThresholdOnItemsAndCount(array($itemId), $itemCount);
                $this->inputData->addTemporaryThresholdOnItemsAndCount(array($itemId), $itemCount);
            }
        }
    }
}