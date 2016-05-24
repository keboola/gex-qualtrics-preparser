# About

Uses an object from within the response object (or the entire response), and converts the object to an array, while appending each item with its key from the object in a `keyColumn` field.

# Configuration

The component is configured in job configuration, and shouldn't be used with 'dataField'

- **parseObject**
    - **path**: `.` separated path to the object to convert into results array
    - **keyColumn**: (`rowId`) column name to store the key from the object

# Installation

    composer require "keboola/ex-generic-qualtrics-preparser"
    php ./console.php gex:module add ./vendor/keboola/ex-generic-qualtrics-preparser/config.yml
