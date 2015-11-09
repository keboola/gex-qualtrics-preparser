<?php

use Keboola\ExGenericModule\QualtricsPreParser;
use Keboola\Juicer\Config\JobConfig,
    Keboola\Juicer\Exception\UserException;

class QualtricsPreParserTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $cfg = JobConfig::create([
            'endpoint' => 'a',
            'parseObject' => [
                'keyColumn' => 'id',
                'path' => 'results'
            ]
        ]);

        $module = new QualtricsPreParser;

        $response = (object) [
            'results' => (object) [
                1 => 'first',
                2 => 'second'
            ],
            'otherArray' => ['a','b']
        ];

        $data = $module->process($response, $cfg);
        self::assertEquals([
            (object) ['data' => 'first', 'id' => 1],
            (object) ['data' => 'second', 'id' => 2]
        ], $data);
    }
}
