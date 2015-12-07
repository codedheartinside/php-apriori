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

namespace CodedHeartInside\DataMining\Apriori\Data;

use CodedHeartInside\DataMining\Apriori\ConfigurationInterface;
use CodedHeartInside\DataMining\Apriori\Data\InputInterface;
use CodedHeartInside\DataMining\Apriori\Data\Validation;
use CodedHeartInside\DataMining\Apriori\Data\Parse;
use CodedHeartInside\DataMining\Apriori\Exception\InvalidDataException;


/**
 * Class Input
 * @package CodedHeartInside\DataMining\Apriori\Data
 */
class Input implements InputInterface
{
    /**
     * @var ConfigurationInterface
     */
    private $projectConfiguration;
    private $validation;
    private $parser;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->projectConfiguration = $configuration;

        $this->validation = new Validation();
        $this->parser = new Parse();
    }

    public function flushDataSet()
    {
        $this->flushFile("data_set.txt");

        return $this;
    }

    public function addDataSet($dataSet)
    {
        $this->validation->validateDataSet($dataSet);

        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/data_set.txt';

        $dataSetFile = fopen($tempFile, 'a');

        foreach ($dataSet as $record) {
            try {
                $this->validation->validateDataSetRecord($record);
            } catch (InvalidDataException $e) {
                if ($this->projectConfiguration->isDebugInformationDisplayed()) {
                    throw new \Exception($e->getMessage());
                }

                continue;
            }

            // Only one set of unique items is required for the Apriori algorithm
            $record = array_unique($record);
            $record = array_filter($record);

            // Place the product ids in a natural sorting order to
            // perform an easy compare later on
            sort($record, SORT_NATURAL);

            $parsedData = $this->parser->parse($record);
            fwrite($dataSetFile, $parsedData . PHP_EOL);
        }

        fclose($dataSetFile);

        return $this;
    }

    public function flushThresholdItems()
    {
        $this->flushFile("threshold.txt");

        return $this;
    }

    public function flushTmeporaryThresholdItems($countNumber)
    {
        $this->flushFile("threshold_{$countNumber}_temp.txt");

        return $this;
    }

    public function addThresholdOnItemsAndCount($itemIds, $count)
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/threshold.txt';

        $file = fopen($tempFile, 'a');

        fwrite(
            $file,
            $this->parser->parse(array('itemIds' => $itemIds, 'count' => $count)) . PHP_EOL
        );

        fclose($file);

        return $this;
    }

    public function addTemporaryThresholdOnItemsAndCount($itemIds, $count)
    {
        $numberOfItems = sizeof($itemIds);

        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . "/threshold_{$numberOfItems}_temp.txt";

        $file = fopen($tempFile, 'a');

        fwrite(
            $file,
            $this->parser->parse(array('itemIds' => $itemIds, 'count' => $count)) . PHP_EOL
        );

        fclose($file);

        return $this;
    }

    public function flushSupportItems()
    {
        $this->flushFile('support.txt');

        return $this;
    }

    public function addSupportOnItemIdAndSupport(array $itemIds = array(), $support)
    {
        if (empty($itemIds) || ! is_array($itemIds)) {
            throw new \InvalidArgumentException('The provided item ids must be in an aray format');
        }

        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/support.txt';

        $file = fopen($tempFile, 'a');

        fwrite(
            $file,
            $this->parser->parse(array('itemIds' => $itemIds, 'support' => $support)) . PHP_EOL
        );

        fclose($file);

        return $this;
    }

    public function flushThresholdItemCombinations()
    {
        $this->flushFile('threshold_combinations.txt');

        return $this;
    }

    public function flushConfidenceRecords()
    {
        $this->flushFile('confidence.txt');
    }

    public function addConfidenceRecord(array $confidenceRecord = array())
    {
        if (empty($confidenceRecord) || ! is_array($confidenceRecord)) {
            throw new \InvalidArgumentException('The provided record parameter needs to be an array');
        }

        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/confidence.txt';

        $file = fopen($tempFile, 'a');

        fwrite(
            $file,
            $this->parser->parse($confidenceRecord) . PHP_EOL
        );

        fclose($file);

        return $this;
    }

    private function flushFile($filename = '')
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();

        $file = fopen("{$tempDirectory}/{$filename}", 'w+');

        fclose($file);
    }
}