<?php

declare(strict_types=1);

return [
    'config' => [
        \NunoMaduro\PhpInsights\Domain\Insights\Composer\ComposerMustBeValid::class => [
            //optional configuration value for composer version 2 only
            //you can allow 'version' in your composer.json and disable the check of it so that you don't get an error
            'composerVersionCheck' => 0,
        ],
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 200,
        ],
        \SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff::class => [
            'maxLinesLength' => 30,
        ],
        \NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 20,
            'exclude' => [
                'src/Model.php',
            ],
        ],
        \NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff::class => [
            'exclude' => [
                'src/Model.php',
            ],
        ],
        \SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff::class => [
            'exclude' => [
                'src/Model.php',
            ],
        ],
        \SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff::class => [
            'exclude' => [
                'src/Model.php',
            ],
        ],
    ],
    'remove' => [
        \SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff::class,
        \SlevomatCodingStandard\Sniffs\ControlStructures\AssignmentInConditionSniff::class,
        \SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\ModernClassNameReferenceSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff::class,
        \PhpCsFixer\Fixer\Operator\NewWithBracesFixer::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff::class,
        \PHP_CodeSniffer\Standards\PSR12\Sniffs\Classes\ClassInstantiationSniff::class,
    ],
];
