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

use Bearwulf\DataMining\Apriori\ConfigurationInterface;
use Bearwulf\DataMining\Apriori\Data\InputInterface;
use Bearwulf\DataMining\Apriori\Data\Validation;
use Bearwulf\DataMining\Apriori\Data\Parse;
use Bearwulf\DataMining\Apriori\Exception\InvalidDataException;


/**
 * Class Input
 * @package Bearwulf\DataMining\Apriori\Data
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
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/data_set.txt';

        $dataSetFile = fopen($tempFile, 'w+');

        fclose($dataSetFile);

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
            }

            $parsedData = $this->parser->parse($record);
            fwrite($dataSetFile, $parsedData . PHP_EOL);
        }

        fclose($dataSetFile);

        return $this;
    }

    public function flushThresholdItems()
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/threshold.txt';

        $file = fopen($tempFile, 'w+');

        fclose($file);

        return $this;
    }

    public function addThresholdOnItemIdAndCount($itemId, $count)
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/threshold.txt';

        $file = fopen($tempFile, 'a');

        fwrite($file, $this->parser->parse(array('itemId' => $itemId, 'count' => $count)) . PHP_EOL);

        fclose($file);

        return $this;
    }
}