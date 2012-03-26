<?php
class MapsParamLine extends ItemParameterManipulation {



    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Manipulate an actual value.
     *
     * @param string $value
     * @param Parameter $parameter
     * @param array $parameters
     *
     * @since 0.4
     *
     * @return mixed
     */
    public function doManipulation(&$value, Parameter $parameter, array &$parameters)
    {
      $parts = preg_split('/[\|]+/',$value);
      $lineCoords = preg_split('/[:]/',$parts[0]);

      $value = new MapsLine($lineCoords);
      $value->setTitle( isset($parts[1]) ? $parts[1] : '' );
      $value->setText( isset($parts[2]) ? $parts[2] : '' );
      $value->setStrokeColor( isset($parts[3]) ? $parts[3] : '' );
      $value->setStrokeOpacity( isset($parts[4]) ? $parts[4] : '' );
      $value->setStrokeWeight( isset($parts[5]) ? $parts[5] : '' );

      $value = $value->getJSONObject();
    }
}