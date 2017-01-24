<?php

namespace AnnaKlipApi\Components\Service;

class DataTransformer
{

    /** @var  array */
    private $data = [];

    /** @var array  */
    private $transformedData = [];

    public function __construct($format, array $data = [])
    {
        $this->data = $data;

        switch (strtolower($format)) {
            case 'json': $this->transformToJson(); break;
            case 'xml': $this->transformToXml(); break;
        }
    }

    public function getTransformedData()
    {
        return $this->transformedData;
    }

    private function transformToJson()
    {
        $this->transformedData = json_encode($this->data);

        return $this->transformedData;
    }

    private function transformToXml()
    {
        $this->array_to_xml($this->data);
        $this->transformedData=$this->array_to_xml($this->data);
        return $this->transformedData;
    }

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