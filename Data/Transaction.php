<?php
/**
 * Created by IntelliJ IDEA.
 * User: wimulkeman
 * Date: 16-5-2015
 * Time: 16:04
 */

namespace CodedHeartInside\DataMining\Apriori\Data;


use CodedHeartInside\DataMining\Apriori\ConfigurationInterface;
use CodedHeartInside\DataMining\Apriori\Data\TransactionInterface;

class Transaction implements TransactionInterface
{
    private $projectConfiguration;
    private $validation;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->validation = new Validation();
    }

    public function getNumberOfTransactions()
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/data_set.txt';

        $dataSetFile = fopen($tempFile, 'rb');
        $lines = 0;

        while (! feof($dataSetFile)) {
            $lines += substr_count(fread($dataSetFile, 8192), "\n");
        }

        fclose($dataSetFile);

        // Reduce the number with one because the last line is always an empty one
        return $lines ? $lines - 1 : 0 ;
    }

    public function getTransactionItems($dataSetRecord)
    {
        $this->validation->validateDataSetRecord($dataSetRecord);

        $dataSetRecord = array_filter($dataSetRecord);

        foreach ($dataSetRecord as $item) {
            yield $item;
        }
    }
}