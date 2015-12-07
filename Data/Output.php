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

use CodedHeartInside\DataMining\Apriori\Data\OutputInterface;
use CodedHeartInside\DataMining\Apriori\ConfigurationInterface;


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

        $file = fopen($tempFile, 'r');

        while ($line = fgets($file)) {
            if (empty($line)) {
                break;
            }

            yield $this->parser->unparse($line);
        }

        fclose($file);
    }

    public function getThresholdItems()
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/threshold.txt';

        $file = fopen($tempFile, 'r');

        while ($line = fgets($file)) {
            if (empty($line)) {
                break;
            }

            yield $this->parser->unparse($line);
        }

        fclose($file);
    }

    public function getThresholdItemsOnItemSetSize($itemSetCount = 1)
    {
        if (empty($itemSetCount) || ! is_numeric($itemSetCount)) {
            throw new \InvalidArgumentException('The provided value should be a numeric value');
        }

        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $thresholdFile = $tempDirectory . "/threshold_{$itemSetCount}_temp.txt";

        $file = fopen($thresholdFile, 'r');

        while ($line = fgets($file)) {
            if (empty($line)) {
                break;
            }

            $currentLine = $this->parser->unparse($line);

            // See if there is a following line. If there isn't then there is no need to continue;
            // First get the current position of the file pointer so it can be reset afterwards
            $currentFilePointerPosition = ftell($file);
            while ($nextLineRaw = fgets($file)) {
                $nextLine = $this->parser->unparse($nextLineRaw);

                yield array('currentRecord' => $currentLine, 'nextRecord' => $nextLine);
            }

            fseek($file, $currentFilePointerPosition);
        }

        fclose($file);
    }

    public function getSupportRecords()
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/support.txt';

        $file = fopen($tempFile, 'r');

        while ($line = fgets($file)) {
            if (empty($line)) {
                break;
            }

            yield $this->parser->unparse($line);
        }

        fclose($file);
    }

    public function getConfidenceRecords()
    {
        $tempDirectory = $this->projectConfiguration->getTempDirectory();
        $tempFile = $tempDirectory . '/confidence.txt';

        $file = fopen($tempFile, 'r');

        while ($line = fgets($file)) {
            if (empty($line)) {
                break;
            }

            yield $this->parser->unparse($line);
        }

        fclose($file);
    }
}