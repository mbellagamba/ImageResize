<?php

/**
 * The configuration class. It parse a XML file and save the configuration in an array.
 * If the XML needs to import other files, it recursively adds them.
 * Class Config
 */
class Config
{
    /** @var  array the key-value array of configuration properties. */
    private $properties;
    /** @var string the directory where XML files are stored. */
    private $dirName;

    /**
     * Config constructor.
     * @param $filename string the main XML filename.
     */
    public function __construct($filename)
    {
        $this->dirName = dirname($filename) . '/';
        $this->parse($filename);
    }

    /**
     * Returns the value corresponding to key passed as parameter. The returned value could be
     * string, int, bool, array or null depending on property.
     * @param $property string the property key.
     * @return mixed the value of the property or null if it does not exists.
     */
    public function get($property)
    {
        return array_key_exists($property, $this->properties) ?
        $this->properties[$property] : null;
    }

    /**
     * Parse an XML file using the glz namespace. It recursively parses imported files.
     * @param $filename string the glz:XML file to parse.
     */
    private function parse($filename)
    {
        $glz = $this->readGLZ($filename);
        $this->cacheImport($glz);
        $this->cacheParam($glz);
        $this->cacheGroup($glz);
    }

    /**
     * Returns the XML object with glz namespace.
     * @param $filename string the XML filename.
     * @return SimpleXMLElement the glz XML.
     */
    private function readGLZ($filename)
    {
        $contents = file_get_contents($filename);
        $xml = new SimpleXMLElement($contents);
        $namespaces = $xml->getNameSpaces();
        return $xml->children($namespaces['glz']);
    }

    /**
     * Calls the parsing function for each imported file.
     * @param $xml SimpleXMLElement the XML object.
     */
    private function cacheImport($xml)
    {
        foreach ($xml->Import as $import) {
            $importFile = $import->attributes()->src->__toString();
            $this->parse($this->dirName . $importFile);
        }
    }

    /**
     * Saves on properties array all params belonging to a group.
     * @param $xml SimpleXMLElement the XML object.
     */
    private function cacheGroup($xml)
    {
        foreach ($xml->Group as $group) {
            $groupName = $group->attributes()->name->__toString();
            $this->cacheParam($group, $groupName);
        }
    }

    /**
     * Saves the all params on the XML object, converting them in its true type.
     * Params ending with [], are converted to array.
     * @param $xml SimpleXMLElement the XML object.
     * @param null $prefix string the group prefix.
     */
    private function cacheParam($xml, $prefix = null)
    {
        foreach ($xml->Param as $param) {
            $attributes = $param->attributes();
            $name = $attributes->name->__toString();
            $value = $attributes->value->__toString();
            $value = $this->castIfNeeded($value);
            if ($prefix !== null) {
                $name = $prefix . '/' . $name;
            }
            if ($this->endsWith($name, '[]')) {
                $prop = str_replace('[]', '', $name);
                if (array_key_exists($prop, $this->properties)) {
                    array_push($this->properties[$prop], $value);
                } else {
                    $this->properties[$prop] = [$value];
                }
            } else {
                $this->properties[$name] = $value;
            }
        }
    }

    /**
     * Checks if the string has the specified suffix.
     * @param $string string the string to check.
     * @param $suffix string the searched suffix.
     * @return bool true if the string ends with suffix.
     */
    private function endsWith($string, $suffix)
    {
        $stringLength = strlen($string);
        $suffixLength = strlen($suffix);
        if ($suffixLength > $stringLength) {
            return false;
        }
        return substr_compare($string, $suffix, $stringLength - $suffixLength, $suffixLength) === 0;
    }

    /**
     * Cast the string content if it represent an int or a bool value.
     * @param $string string the value to cast.
     * @return bool|int the value corresponding to the string.
     */
    private function castIfNeeded($string)
    {
        $result = is_numeric($string) ? intval($string) : $string;
        $result = strcmp($result, 'true') === 0 ? true : $result;
        $result = strcmp($result, 'false') === 0 ? false : $result;
        return $result;
    }
}
