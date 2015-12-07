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

use CodedHeartInside\DataMining\Apriori\Data\ParseInterface;
use CodedHeartInside\DataMining\Apriori\Exception\InvalidDataException;


class Parse implements ParseInterface
{
    public function parse($unparsedData)
    {
        if (! is_array($unparsedData)) {
            throw new InvalidDataException('The data needs to be in an array format');
        }

        return json_encode($unparsedData);
    }

    public function unparse($parsedData)
    {
        if (! is_string($parsedData)) {
            throw new InvalidDataException('The data needs to be in an string format');
        }

        return json_decode($parsedData, true);
    }
}