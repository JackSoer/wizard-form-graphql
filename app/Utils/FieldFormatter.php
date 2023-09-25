<?php

namespace App\Utils;

class FieldFormatter
{
  public static function formatFieldsToCamelCase($object, $fields)
    {
        $formattedData = [];

        foreach ($fields as $field) {
            $formattedKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $field))));
            $formattedData[$formattedKey] = $object->$field;
        }

        return $formattedData;
    }
}
