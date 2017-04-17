<?php

require('./vendor/autoload.php');

use JsonSchema\Validator;

$jsonSchemaString = <<<'JSON'
{
    "type": "object",
    "properties": {
        "data": {
            "oneOf": [
                { "$ref": "#/definitions/integerData" },
                { "$ref": "#/definitions/stringData" }
            ]
        }
    },
    "required": ["data"],
    "definitions": {
        "integerData" : {
            "type": "integer",
            "minimum" : 0
        },
        "stringData" : {
            "type": "string"
        }
    }
}
JSON;

$data = json_decode(file_get_contents('./data.json'));
$schema = json_decode($jsonSchemaString);

$validator = new Validator();
$validator->check($data, $schema);

if ($validator->isValid()) {
    echo "The supplied JSON validates against the schema.\n";
} else {
    echo "JSON does not validate. Violations:\n";
    foreach ($validator->getErrors() as $error) {
        echo sprintf("[%s] %s\n", $error['property'], $error['message']);
    }
}
