<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/contao',
        __DIR__ . '/src',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withSkip([
        FlipTypeControlToUseExclusiveTypeRector::class => [
            __DIR__ . '/src/Controller/DetailController.php'
        ],
        EncapsedStringsToSprintfRector::class => [
            __DIR__ . '/src/Model/*'
        ],
        WrapEncapsedVariableInCurlyBracesRector::class => [
            __DIR__ . '/src/Model/*'
        ]
    ])
    ->withPreparedSets(
        true,
        true,
        true,
        true,
    #true,
    );
