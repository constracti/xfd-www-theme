<?php

# TODO comment handling

class HTML_Collection implements Iterator {

	/**
	 * @var    int     $key  an integer holding the key of the current HTML element
	 * @access private
	 */
	private $key;

	/**
	 * @var    array   $elements  an array holding the HTML elements
	 * @access private
	 */
	private $elements;

	/**
	 * @return HTML_Element|string  the current HTML element
	 * @access public
	 */
	public function current() {
		return $this->elements[$this->key];
	}

	/**
	 * @return int     the key of the current HTML element
	 * @access public
	 */
	public function key() {
		return $this->key;
	}

	/**
	 * @access public
	 */
	public function next() {
		$this->key++;
	}

	/**
	 * @access public
	 */
	public function rewind() {
		$this->key = 0;
	}

	/**
	 * @return boolean  whether the key has a valid value
	 * @access public
	 */
	public function valid() {
		return $this->key >= 0 && $this->key < count( $this->elements );
	}

	/**
	 * @param  array|string $elements  an array of HTML elements
	 *                                 or an HTML string
	 * @access public
	 */
	public function __construct( $elements = [] ) {
		if ( is_string( $elements ) )
			$this->elements = self::unserialize( $elements );
		else
			$this->elements = $elements;
	}

	/**
	 * @return string  the HTML string of collection
	 * @access public
	 */
	public function __toString() {
		return implode( $this->elements );
	}

	/**
	 * @param  string $html  the HTML string to parse
	 * @return array         an array of parsed HTML elements
	 * @access public
	 */
	public static function unserialize( $html ) {
		$matches = mb_ereg_all( '(<[^<>]*>|[^<>]+)', $html );
		$pieces = array_column( $matches, 1 );
		$elements = [];
		$cur = 0;
		$len = count( $pieces );
		while ( $cur < $len )
			list( $elements[], $cur ) = self::unserialize_aux( $pieces, $cur, NULL );
		return $elements;
	}

	private static function unserialize_aux( &$pieces, $cur, $parent ) {
		if ( mb_ereg_match( '<[^\/][^<>]*>', $pieces[$cur] ) ) {
			// piece is opening
			mb_ereg( '<([^\s]+)((?:\s+[\w-]+(?:="[^"]*")?)*)\s*(\/?)>', $pieces[$cur], $matches );
			$element = new HTML_Element();
			$element->tag = $matches[1];
			$is_self_closing = $matches[3] === '/';
			$element->attributes = [];
			$matches = mb_ereg_all( '\s+([\w-]+)(?:="([^"]*)")?', $matches[2] );
			foreach ( $matches as $match )
				$element->attributes[$match[1]] = $match[2] === FALSE ? NULL : $match[2];
			if ( $is_self_closing ) {
				$element->children = NULL;
			} else {
				$cur++;
				$element->children = [];
				while ( !mb_ereg_match( '<\/[^<>]*>', $pieces[$cur] ) ) {
					// piece is not closing
					list( $element->children[], $cur ) = self::unserialize_aux( $pieces, $cur, $element );
				}
			}
			$element->parent = $parent;
			return [ $element, $cur + 1 ];
		} else {
			// piece is text
			return [ $pieces[$cur], $cur + 1 ];
		}
	}

	public function serialize() {
		return strval( $this );
	}

	public function count() {
		return count( $this->elements );
	}

	public function children() {
		$set = [];
		foreach ( $this->elements as $element ) {
			if ( is_string( $element ) )
				continue;
			if ( is_null( $element->children ) )
				continue;
			$set = array_merge( $set, $element->children );
		}
		return new self( $set );
	}

	/**
	 * @param callable|string $selector  a function returning whether to keep an element
	 *                                   or a primitive CSS selector
	 * @param boolean         $inverse   whether to inverse the result
	 */
	public function filter( $selector, $inverse = FALSE ) {
		$set = [];
		foreach ( $this->elements as $element ) {
			if ( is_string( $element ) ) {
				if ( is_callable( $selector ) ) {
					if ( $selector( $element ) !== $inverse )
						$set[] = $element;
				} else {
					if ( $inverse )
						$set[] = $element;
				}
			} else {
				if ( $element->is( $selector ) !== $inverse )
					$set[] = $element;
			}
		}
		return new self( $set );
	}

	public function first() {
		$set = [];
		foreach ( $this->elements as $element ) {
			if ( is_string( $element ) )
				continue;
			$set[] = $element;
			break;
		}
		return new self( $set );
	}

	public function attr( $key, $value ) {
		foreach ( $this->elements as $element ) {
			if ( is_string( $element ) )
				continue;
			$element->attributes[$key] = $value;
		}
		return $this;
	}

	public function text( $text ) {
		foreach ( $this->elements as $element ) {
			if ( is_string( $element ) )
				continue;
			$element->children = [ $text ];
		}
		return $this;
	}

	public function clone() {
		return new self( strval( $this ) );
	}

	public function add( $collection ) {
		$this->elements = array_merge( $this->elements, $collection->elements );
		return $this;
	}

	public function after( $collection ) {
		foreach ( $this->elements as $element ) {
			$parent = $element->parent;
			if ( is_null( $parent ) )
				continue;
			if ( is_null( $parent->children ) )
				continue;
			$key = array_search( $element, $parent->children, TRUE );
			if ( $key === FALSE )
				continue;
			$copy = $collection->clone();
			array_splice( $parent->children, $key + 1, 0, $copy->elements );
			foreach ( $copy->elements as $copy_element )
				if ( !is_string( $copy_element ) )
					$copy_element->parent = $parent;
		}
		return $this;
	}

	public function prepend( $collection ) {
		foreach ( $this->elements as $element ) {
			if ( is_string( $element ) )
				continue;
			if ( is_null( $element->children ) )
				continue;
			$copy = $collection->clone();
			array_splice( $element->children, 0, 0, $copy->elements );
			foreach ( $copy->elements as $copy_element )
				if ( !is_string( $copy_element ) )
					$copy_element->parent = $element;
		}
		return $this;
	}
}
