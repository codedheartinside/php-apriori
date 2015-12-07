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

use CodedHeartInside\DataMining\Apriori\Exception\InvalidDataException;

class Validation
{
    public function validateDataSet(array $dataSet = array())
    {
        if (empty($dataSet)) {
            throw new InvalidDataException('The provided data set is empty');
        }

        if (! is_array($dataSet)) {
            throw new InvalidDataException('The provided data set is not an array');
        }

        if (array_keys($dataSet) !== range(0, sizeof($dataSet) - 1)) {
            throw new InvalidDataException('The provided data set is not an sequential array');
        }
    }

    public function validateDataSetRecord(array $dataSetRecord = array())
    {
        if (empty($dataSetRecord)) {
            throw new InvalidDataException('The provided data set record is empty');
        }

        if (! is_array($dataSetRecord)) {
            throw new InvalidDataException('The provided data set record is not an array');
        }

        if (array_keys($dataSetRecord) !== range(0, sizeof($dataSetRecord) - 1)) {
            throw new InvalidDataException('The provided data set record is not an sequential array');
        }
    }

    public function doesFileExist($filepath = '')
    {
        if (! is_string($filepath)) {
            throw new \InvalidArgumentException('The provided filepath param should be a string');
        }

        return is_file($filepath);
    }

    public function isFileEmpty($filepath = '')
    {
        if (! $this->doesFileExist($filepath)) {
            return true;
        }

        return ! filesize($filepath);
    }
}