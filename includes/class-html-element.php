<?php

class HTML_Element {

	/**
	 * @var    string
	 * @access public
	 */
	var $tag;

	/**
	 * @var    array   an associative array of the element's attributes
	 * @access public
	 */
	var $attributes;

	/**
	 * @var    array|null  an array of the element's children
	 *                     or null if the element is self-closing
	 * @access public
	 */
	var $children;

	/**
	 * @var    HTML_Element|null
	 * @access public
	 */
	var $parent;

	/**
	 * @return string  the HTML string of the element
	 * @access public
	 */
	function __toString() {
		$str = '<' . $this->tag;
		foreach ( $this->attributes as $key => $value ) {
			$str .= ' ' . $key;
			if ( !is_null( $value ) )
				$str .= '="' . $value . '"';
		}
		if ( is_null( $this->children ) ) {
			// element is self closing
			$str .= ' />';
		} else {
			// element is not self closing
			$str .= '>';
			$str .= implode( $this->children );
			$str .= '</' . $this->tag . '>';
		}
		return $str;
	}

	/**
	 * @param  callable|string $selector  a function returning whether to accept the element
	 *                                    or a primitive CSS selector
	 * @return boolean                    whether to accept the element
	 * @access public
	 */
	function is( $selector ) {
		if ( is_callable( $selector ) )
			return $selector( $this );
		mb_ereg( '^([\w-]+)?(?:\#([\w-]+))?((?:\.[\w-]+)*)$', $selector, $matches );
		if ( $matches[1] !== FALSE && $matches[1] !== $this->tag )
			return FALSE;
		if ( $matches[2] !== FALSE ) {
			if ( !array_key_exists( 'id', $this->attributes ) || $matches[2] !== $this->attributes['id'] )
			return FALSE;
		}
		if ( $matches[3] !== FALSE ) {
			if ( !array_key_exists( 'class', $this->attributes ) )
				return FALSE;
			$required = array_filter( mb_split( '\.', $matches[3] ) );
			$included = array_filter( mb_split( '\s+', $this->attributes['class'] ) );
			if ( count( array_diff( $required, $included ) ) )
				return FALSE;
		}
		return TRUE;
	}
}
