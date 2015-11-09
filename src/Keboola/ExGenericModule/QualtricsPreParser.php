<?php
namespace Keboola\ExGenericModule;

use Keboola\GenericExtractor\Modules\ResponseModuleInterface;
use Keboola\Juicer\Config\JobConfig;
use Keboola\Juicer\Exception\UserException;
use Keboola\Utils\Utils;

class QualtricsPreParser implements ResponseModuleInterface
{
    /**
     * @return array
     */
    public function process($response, JobConfig $jobConfig)
    {
        if (empty($jobConfig->getConfig()['parseObject'])) {
            return $response;
        }

        $config = $jobConfig->getConfig()['parseObject'];

        if (!is_object($response)) {
            if (empty($response)) {
                return [];
            }

            throw new UserException("Data in response is not an object, while one was expected!");
        }

        $path = empty($config['path'])
            ? "."
            : $config['path'];

        $key = empty($config['keyColumn'])
            ? "rowId"
            : $config['keyColumn'];

        return $this->convertObjectWithKeys(Utils::getDataFromPath($path, $response, '.'), $key);
    }

    /**
     * Convert an object to array, adding keys from the first level of the object
     * to each first child's $key property
     * @param \stdClass $data
     * @param string $key
     * @return array
     * @todo Belongs to a dfifferent class!
     */
    protected function convertObjectWithKeys(\stdClass $data, $key)
    {
        $convertedData = [];
        foreach($data as $id => $record) {
            if (is_scalar($record)) {
                $convertedData[] = (object) [
                    'data' => $record,
                    $key => $id
                ];
            } elseif (is_object($record)) {
                $record->{$key} = $id;
                $convertedData[] = $record;
            } else {
                throw new UserException("'dataField.objectKey' can only append keys to objects and scalars! '{$id}' is an " . gettype($record));
            }
        }
        return $convertedData;
    }
}
