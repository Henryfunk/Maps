<?php

/**
 * Class for the 'coordinates' parser hooks, 
 * which can transform the notation of a set of coordinates.
 * 
 * @since 0.7
 * 
 * @file Maps_Coordinates.php
 * @ingroup Maps
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MapsCoordinates extends ParserHook {
	/**
	 * No LSB in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */	
	public static function staticInit( Parser &$parser ) {
		$instance = new self;
		return $instance->init( $parser );
	}	
	
	/**
	 * Gets the name of the parser hook.
	 * @see ParserHook::getName
	 * 
	 * @since 0.7
	 * 
	 * @return string
	 */
	protected function getName() {
		return 'coordinates';
	}
	
	/**
	 * Returns an array containing the parameter info.
	 * @see ParserHook::getParameterInfo
	 * 
	 * @since 0.7
	 * 
	 * @return array
	 */
	protected function getParameterInfo( $type ) {
		global $egMapsAvailableCoordNotations;
		global $egMapsCoordinateNotation;
		global $egMapsCoordinateDirectional;
		
		$params = array();

		$params['location'] = array(
			// new CriterionIsLocation() FIXME
		);

		$params['format'] = array(
			'default' => $egMapsCoordinateNotation,
			'values' => $egMapsAvailableCoordNotations,
			'aliases' => 'notation',
			// new ParamManipulationFunctions( 'strtolower' ) FIXME
		);

		$params['directional'] = array(
			'type' => 'boolean',
			'default' => $egMapsCoordinateDirectional,
		);

		foreach ( $params as $name => &$param ) {
			$param['message'] = 'maps-coordinates-par-' . $name;
		}

		return $params;
	}
	
	/**
	 * Returns the list of default parameters.
	 * @see ParserHook::getDefaultParameters
	 * 
	 * @since 0.7
	 * 
	 * @return array
	 */
	protected function getDefaultParameters( $type ) {
		return array( 'location', 'format', 'directional' );
	}
	
	/**
	 * Renders and returns the output.
	 * @see ParserHook::render
	 * 
	 * @since 0.7
	 * 
	 * @param array $parameters
	 * 
	 * @return string
	 */
	public function render( array $parameters ) {
		$parsedCoords = MapsCoordinateParser::parseCoordinates( $parameters['location'] );
		
		if ( $parsedCoords ) {
			$output = MapsCoordinateParser::formatCoordinates( $parsedCoords, $parameters['format'], $parameters['directional'] );
		} else {
			// The coordinates should be valid when this method gets called.
			throw new MWException( 'Attempt to format an invalid set of coordinates' );
		}
		
		return $output;		
	}
	
	/**
	 * @see ParserHook::getMessage()
	 * 
	 * @since 1.0
	 */
	public function getMessage() {
		return 'maps-coordinates-description';
	}	
	
}