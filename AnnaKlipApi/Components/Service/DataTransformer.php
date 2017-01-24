<?php

namespace AnnaKlipApi\Components\Service;

/**
 * @author
 */
class DataTransformer
{

    /** @var  array */
    private $data = [];

    /** @var array  */
    private $transformedData = [];

    /**
     * DataTransformer constructor.
     * @param $format
     * @param array $data
     */
    public function __construct($format, array $data = [])
    {
        $this->data = $data;

        switch (strtolower($format)) {
            case 'json': $this->transformToJson(); break;
            case 'xml': $this->transformToXml(); break;
        }
    }

    /**
     * @return array
     */
    public function getTransformedData()
    {
        return $this->transformedData;
    }

    /**
     * @return array|string
     */
    private function transformToJson()
    {
        $this->transformedData = json_encode($this->data);

        return $this->transformedData;
    }

    /**
     * @return array|string
     */
    private function transformToXml()
    {
        $this->array_to_xml($this->data);
        $this->transformedData=$this->array_to_xml($this->data);
        return $this->transformedData;
    }

    /**
     * @param $data
     * @return string
     */
    function array_to_xml($data)
    {
        $xml='';
        foreach($data as $key=>$value)
        {
            if(is_array($value)){
                $xml .='<' . $key .'>' . $this->array_to_xml($value) . '</' . $key . '>';
            }
            else{
                $xml .='<' . $key .'>' . $value . '</' . $key . '>';
            }
        }
        return $xml;
    }


}