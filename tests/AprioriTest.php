<?php

namespace Tests;

use CodedHeartInside\DataMining\Apriori\Apriori;
use CodedHeartInside\DataMining\Apriori\Configuration;
use CodedHeartInside\DataMining\Apriori\Data\Input;
use CodedHeartInside\DataMining\Apriori\Installer;
use PHPUnit\Framework\TestCase;

class AprioriTest extends TestCase
{
    protected function setUp()
    {
        (new Installer())->createRunningEnvironment();
    }

    public function testCalculating()
    {
        $aprioriConfiguration = new Configuration();

        // Configuring the boundries is optional
        $aprioriConfiguration->setDisplayDebugInformation();

        $dataSet = array(
            array(1, 3, 4),
            array(2, 4, 6),
            array(1, 2),
            array(5),
        );

        $dataInput = new Input($aprioriConfiguration);
        $dataInput->flushDataSet()
            ->addDataSet($dataSet)
            ->addDataSet($dataSet) // In this case, the data set is added twice to create more testing data
        ;

        $aprioriClass = new Apriori($aprioriConfiguration);
        $aprioriClass->run();

        $expectedSupports = [
            [
                'itemIds' => [1],
                'support' => 0.6
            ],
            [
                'itemIds' => [2],
                'support' => 0.6
            ],
            [
                'itemIds' => [3],
                'support' => 0.3
            ],
            [
                'itemIds' => [4],
                'support' => 0.6
            ],
            [
                'itemIds' => [5],
                'support' => 0.3
            ],
            [
                'itemIds' => [6],
                'support' => 0.3
            ],
            [
                'itemIds' => [1, 2],
                'support' => 0.3
            ],
            [
                'itemIds' => [1, 3],
                'support' => 0.3
            ],
            [
                'itemIds' => [1, 4],
                'support' => 0.3
            ],
            [
                'itemIds' => [2, 4],
                'support' => 0.3
            ],
            [
                'itemIds' => [2, 6],
                'support' => 0.3
            ],
            [
                'itemIds' => [3, 4],
                'support' => 0.3
            ],
            [
                'itemIds' => [4, 6],
                'support' => 0.3
            ],
            [
                'itemIds' => [1, 3, 4],
                'support' => 0.3
            ],
            [
                'itemIds' => [2, 4, 6],
                'support' => 0.3
            ],
        ];

        foreach ($aprioriClass->getSupportRecords() as $record) {
            $support = array_shift($expectedSupports);

            $record['support'] = (int) round($record['support'] * 10);
            $support['support'] = (int) round($support['support'] * 10);

            $this->assertSame($record, $support);
        }

        $expectedConfidences = [
            [
                'if' => [2],
                'then' => 1,
                'confidence' => 0.5
            ],
            [
                'if' => [1],
                'then' => 2,
                'confidence' => 0.5
            ],
            [
                'if' => [3],
                'then' => 1,
                'confidence' => 1
            ],
            [
                'if' => [1],
                'then' => 3,
                'confidence' => 0.5
            ],
            [
                'if' => [4],
                'then' => 1,
                'confidence' => 0.5
            ],
            [
                'if' => [1],
                'then' => 4,
                'confidence' => 0.5
            ],
            [
                'if' => [4],
                'then' => 2,
                'confidence' => 0.5
            ],
            [
                'if' => [2],
                'then' => 4,
                'confidence' => 0.5
            ],
            [
                'if' => [6],
                'then' => 2,
                'confidence' => 1
            ],
            [
                'if' => [2],
                'then' => 6,
                'confidence' => 0.5
            ],
            [
                'if' => [4],
                'then' => 3,
                'confidence' => 0.5
            ],
            [
                'if' => [3],
                'then' => 4,
                'confidence' => 1
            ],
            [
                'if' => [6],
                'then' => 4,
                'confidence' => 1
            ],
            [
                'if' => [4],
                'then' => 6,
                'confidence' => 0.5
            ],
            [
                'if' => [3, 4],
                'then' => 1,
                'confidence' => 1
            ],
            [
                'if' => [1, 4],
                'then' => 3,
                'confidence' => 1
            ],
            [
                'if' => [1, 3],
                'then' => 4,
                'confidence' => 1
            ],
            [
                'if' => [4, 6],
                'then' => 2,
                'confidence' => 1
            ],
            [
                'if' => [2, 6],
                'then' => 4,
                'confidence' => 1
            ],
            [
                'if' => [2, 4],
                'then' => 6,
                'confidence' => 1
            ],
        ];

        $this->assertCount(20, $aprioriClass->getConfidenceRecords());
        foreach ($aprioriClass->getConfidenceRecords() as $record) {
            $confidence = array_shift($expectedConfidences);

            $record['confidence'] = (int) round($record['confidence'] * 10);
            $confidence['confidence'] = (int) round($confidence['confidence'] * 10);

            $this->assertSame($record, $confidence);
        }
    }

    public function testConfidenceThreshold()
    {
        $aprioriConfiguration = new Configuration();

        // Configuring the boundries is optional
        $aprioriConfiguration->setDisplayDebugInformation();
        $aprioriConfiguration
            ->setMinimumThreshold(2) // Default is 2
            ->setMinimumConfidence(0.7) // Default is 0.2
        ;

        $dataSet = array(
            array(1, 3, 4),
            array(2, 4, 6),
            array(1, 2),
            array(5),
        );

        $dataInput = new Input($aprioriConfiguration);
        $dataInput->flushDataSet()
            ->addDataSet($dataSet)
            ->addDataSet($dataSet) // In this case, the data set is added twice to create more testing data
        ;

        $aprioriClass = new Apriori($aprioriConfiguration);
        $aprioriClass->run();

        $this->assertCount(10, $aprioriClass->getConfidenceRecords());
    }

    public function testSupportThreshold()
    {
        $aprioriConfiguration = new Configuration();

        // Configuring the boundries is optional
        $aprioriConfiguration->setDisplayDebugInformation();
        $aprioriConfiguration
            ->setMinimumThreshold(2) // Default is 2
            ->setMinimumSupport(0.4) // Default is 0.2
        ;

        $dataSet = array(
            array(1, 3, 4),
            array(2, 4, 6),
            array(1, 2),
            array(5),
        );

        $dataInput = new Input($aprioriConfiguration);
        $dataInput->flushDataSet()
            ->addDataSet($dataSet)
            ->addDataSet($dataSet) // In this case, the data set is added twice to create more testing data
        ;

        $aprioriClass = new Apriori($aprioriConfiguration);
        $aprioriClass->run();

        $this->assertCount(3, $aprioriClass->getSupportRecords());
    }
}
