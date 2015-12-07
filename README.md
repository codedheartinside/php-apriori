PHP Apriori
===========

This package is meant for implementing the Apriori algorithm as a microservice.

# Installation:

## Enable composer in your project

```bash
curl -s http://getcomposer.org/installer | php
```

## Option 1: Clone the project from github

Add the repository to the composer file. Replace 1.0.0 with the latest version number.

```
{
    "repositories": [
        {
            "type":"package",
            "package": {
                "name": "codedheartinside/php-apriori",
                "version":"dev-1.*",
                "source": {
                    "url": "https://github.com/codedheartinside/php-apriori.git",
                    "type": "git",
                    "reference":"dev-1.0.0"
                }
            }
        }
    ],
    "require": {
        "codedheartinside/php-apriori": "dev-1.*"
    }
}
```

## Option 2: Download the files from github

Retrieve the files:

1. Create the directory vendor/codedheartinside/DataMining/Apriori
2. Download the files from GitHub, or clone the project into the folder

Add the package to the composer file

```
{
    "autoload": {
        "psr-4": {
            "CodedHeartInside\\DataMining\\Apriori\\": "vendor/CodedHeartInside/DataMining/Apriori"
        }
    }
}
```

## Option 3: Downloading the package (Not yet available)

Add the package to your composer.json file

```
{
    "require": {
        "codedheartinside/apriori": "1.*"
    }
}
```

## Load the files and create the autoload file

Download the files

```bash
php composer.phar install
```

Add the autoloader for the files into your project

```php
require 'vendor/autoload.php';
```

## Set up the running environment

To set up the running environment for the package, run the installer

```php
$installer = new \Bearwulf\DataMining\Apriori\Installer();
$installer->createRunningEnvironment();
```

# Usage

## Configuration

You first need to create a configuration with the rules for the algorithm

```php
$aprioriConfiguration = new \Bearwulf\DataMining\Apriori\Configuration();

// Configuring the boundries is optional
$aprioriConfiguration->setDisplayDebugInformation(true)
    ->setMinimumThreshold(2) // Default is 2
    ->setMinimumSupport(0.2) // Default is 0.1
    ->setMinimumConfidence(5); // Default is 0.2
```

## Defining the data set
After that, all is set to run the algorithm on a data set. The data set can be added through the addDataSet function.

```php
$dataInput = new Bearwulf\DataMining\Apriori\Data\Input($aprioriConfiguration);
$dataInput->flushDataSet()
    ->addDataSet($dataSet)
    ->addDataSet($dataSet); // In this case, the data set is added twice to create more testing data
```

## Running the algorithm

To run the the algorithm on the data set, provide the Apriori class with the configuration and call the run function.

```php
$aprioriClass = new Bearwulf\DataMining\Apriori\Apriori($aprioriConfiguration);
$aprioriClass->run();
```

## Retrieving the data

After running the algorithm, the records with the statistics for support and confidence become retrievable.

To get the records with the support statistics:

```php
foreach ($apriori->getSupportRecords() as $record) {
    print_r($record);
    // Outputs:
    // Array
    // (
    //     [itemIds] => Array
    //     (
    //         [0] => 1
    //         [1] => 4
    //         [2] => 6
    //         [3] => 7
    //     )
    //
    //     [support] => 0.060606060606061
    // )
}
```

To get the records with the confidence statistics

```php
foreach ($apriori->getConfidenceRecords() as $record) {
    print_r($record);
    // Outputs
    // Array
    // (
    //     [if] => Array
    //     (
    //       [0] => 1
    //       [1] => 7
    //     )
    //
    //     [then] => 3
    //     [confidence] => 1
    // )
}
```