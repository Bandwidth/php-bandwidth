<?php

/**
 * FSHL 2.1.0                                  | Fast Syntax HighLighter |
 * -----------------------------------------------------------------------
 *
 * LICENSE
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace FSHL\Lexer\Cache;

/**
 * Optimized and cached Javascript lexer.
 *
 * This file is generated. All changes made in this file will be lost.
 *
 * @copyright Copyright (c) 2002-2005 Juraj 'hvge' Durech
 * @copyright Copyright (c) 2011-2012 Jaroslav Hanslík
 * @license http://fshl.kukulich.cz/#license
 * @see \FSHL\Generator
 * @see \FSHL\Lexer\Javascript
 */
class Javascript
{
	/**
	 * Language name.
	 *
	 * @var array
	 */
	public $language;

	/**
	 * Transitions table.
	 *
	 * @var array
	 */
	public $trans;

	/**
	 * Id of the initial state.
	 *
	 * @var integer
	 */
	public $initialState;

	/**
	 * Id of the return state.
	 *
	 * @var integer
	 */
	public $returnState;

	/**
	 * Id of the quit state.
	 *
	 * @var integer
	 */
	public $quitState;

	/**
	 * List of flags for all states.
	 *
	 * @var array
	 */
	public $flags;

	/**
	 * Data for all states.
	 *
	 * @var array
	 */
	public $data;

	/**
	 * List of CSS classes.
	 *
	 * @var array
	 */
	public $classes;

	/**
	 * List of keywords.
	 *
	 * @var array
	 */
	public $keywords;

	/**
	 * Initializes the lexer.
	 */
	public function __construct()
	{
		$this->language = 'Javascript';
		$this->trans = array(
			0 => array(
				0 => array(
					0 => 0, 1 => 1
				), 1 => array(
					0 => 0, 1 => 1
				), 2 => array(
					0 => 1, 1 => -1
				), 3 => array(
					0 => 2, 1 => 1
				), 4 => array(
					0 => 2, 1 => 1
				), 5 => array(
					0 => 1, 1 => 0
				), 6 => array(
					0 => 4, 1 => 1
				), 7 => array(
					0 => 5, 1 => 1
				), 8 => array(
					0 => 6, 1 => 1
				), 9 => array(
					0 => 7, 1 => 1
				), 10 => array(
					0 => 8, 1 => 1
				), 11 => array(
					0 => 9, 1 => 1
				), 12 => array(
					0 => 11, 1 => 1
				)
			), 1 => array(
				0 => array(
					0 => 10, 1 => -1
				)
			), 2 => array(
				0 => array(
					0 => 3, 1 => 1
				), 1 => array(
					0 => 2, 1 => 1
				), 2 => array(
					0 => 10, 1 => -1
				)
			), 3 => array(
				0 => array(
					0 => 10, 1 => -1
				)
			), 4 => array(
				0 => array(
					0 => 10, 1 => 0
				), 1 => array(
					0 => 9, 1 => 1
				)
			), 5 => array(
				0 => array(
					0 => 10, 1 => 0
				), 1 => array(
					0 => 9, 1 => 1
				)
			), 6 => array(
				0 => array(
					0 => 6, 1 => 1
				), 1 => array(
					0 => 6, 1 => 1
				), 2 => array(
					0 => 10, 1 => 0
				), 3 => array(
					0 => 9, 1 => 1
				)
			), 7 => array(
				0 => array(
					0 => 10, 1 => -1
				), 1 => array(
					0 => 7, 1 => 1
				), 2 => array(
					0 => 9, 1 => 1
				)
			), 8 => array(
				0 => array(
					0 => 10, 1 => -1
				)
			), 9 => NULL, 11 => NULL
		);
		$this->initialState = 0;
		$this->returnState = 10;
		$this->quitState = 11;
		$this->flags = array(
			0 => 0, 1 => 5, 2 => 4, 3 => 0, 4 => 4, 5 => 4, 6 => 4, 7 => 4, 8 => 0, 9 => 8, 11 => 8
		);
		$this->data = array(
			0 => NULL, 1 => NULL, 2 => NULL, 3 => NULL, 4 => NULL, 5 => NULL, 6 => NULL, 7 => NULL, 8 => NULL, 9 => 'Php', 11 => NULL
		);
		$this->classes = array(
			0 => 'js-out', 1 => 'js-out', 2 => 'js-num', 3 => 'js-num', 4 => 'js-quote', 5 => 'js-quote', 6 => 'js-comment', 7 => 'js-comment', 8 => 'js-quote', 9 => 'xlang', 11 => 'html-tag'
		);
		$this->keywords = array(
			0 => 'js-keywords', 1 => array(
				'abstract' => 1, 'boolean' => 1, 'break' => 1, 'byte' => 1, 'case' => 1, 'catch' => 1, 'char' => 1, 'class' => 1, 'const' => 1, 'continue' => 1, 'debugger' => 1, 'default' => 1, 'delete' => 1, 'do' => 1, 'double' => 1, 'else' => 1, 'enum' => 1, 'export' => 1, 'extends' => 1, 'false' => 1, 'final' => 1, 'finally' => 1, 'float' => 1, 'for' => 1, 'function' => 1, 'goto' => 1,
			'if' => 1, 'implements' => 1, 'import' => 1, 'in' => 1, 'instanceof' => 1, 'int' => 1, 'interface' => 1, 'long' => 1, 'native' => 1, 'new' => 1, 'null' => 1, 'package' => 1, 'private' => 1, 'protected' => 1, 'public' => 1, 'return' => 1, 'short' => 1, 'static' => 1, 'super' => 1, 'switch' => 1, 'synchronized' => 1, 'this' => 1, 'throw' => 1, 'throws' => 1, 'transient' => 1, 'true' => 1,
			'try' => 1, 'typeof' => 1, 'var' => 1, 'void' => 1, 'volatile' => 1, 'while' => 1, 'with' => 1, 'document' => 2, 'getAttribute' => 2, 'getElementsByTagName' => 2, 'getElementById' => 2
			), 2 => true
		);

	}

	/**
	 * Finds a delimiter for state OUT.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter0($text, $textLength, $textPos)
	{
		static $delimiters = array(
			0 => "\n", 1 => "\t", 5 => '.', 6 => '"', 7 => '\'', 8 => '/*', 9 => '//', 12 => '</'
		);

		$buffer = false;
		while ($textPos < $textLength) {
			$part = substr($text, $textPos, 10);
			$letter = $text[$textPos];

			if ($delimiters[0] === $letter) {
				return array(0, $delimiters[0], $buffer);
			}
			if ($delimiters[1] === $letter) {
				return array(1, $delimiters[1], $buffer);
			}
			if (preg_match('~^[a-z]+~i', $part, $matches)) {
				return array(2, $matches[0], $buffer);
			}
			if (preg_match('~^\\d+~', $part, $matches)) {
				return array(3, $matches[0], $buffer);
			}
			if (preg_match('~^\.\\d+~', $part, $matches)) {
				return array(4, $matches[0], $buffer);
			}
			if ($delimiters[5] === $letter) {
				return array(5, $delimiters[5], $buffer);
			}
			if ($delimiters[6] === $letter) {
				return array(6, $delimiters[6], $buffer);
			}
			if ($delimiters[7] === $letter) {
				return array(7, $delimiters[7], $buffer);
			}
			if (0 === strpos($part, $delimiters[8])) {
				return array(8, $delimiters[8], $buffer);
			}
			if (0 === strpos($part, $delimiters[9])) {
				return array(9, $delimiters[9], $buffer);
			}
			if (preg_match('~/.*?[^\\\\]/[gim]*~A', $text, $matches, 0, $textPos)) {
				return array(10, $matches[0], $buffer);
			}
			if (preg_match('~<\\?(php|=|(?!xml))~A', $text, $matches, 0, $textPos)) {
				return array(11, $matches[0], $buffer);
			}
			if (0 === strpos($part, $delimiters[12])) {
				return array(12, $delimiters[12], $buffer);
			}
			$buffer .= $letter;
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state KEYWORD.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter1($text, $textLength, $textPos)
	{

		$buffer = false;
		while ($textPos < $textLength) {
			$part = substr($text, $textPos, 10);

			if (preg_match('~^\\W+~', $part, $matches)) {
				return array(0, $matches[0], $buffer);
			}
			$buffer .= $text[$textPos];
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state NUMBER.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter2($text, $textLength, $textPos)
	{
		static $delimiters = array(
			0 => 'x'
		);

		$buffer = false;
		while ($textPos < $textLength) {
			$part = substr($text, $textPos, 10);
			$letter = $text[$textPos];

			if ($delimiters[0] === $letter) {
				return array(0, $delimiters[0], $buffer);
			}
			if (preg_match('~^\.\\d+~', $part, $matches)) {
				return array(1, $matches[0], $buffer);
			}
			return array(2, $letter, $buffer);
			$buffer .= $letter;
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state HEXA.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter3($text, $textLength, $textPos)
	{

		$buffer = false;
		while ($textPos < $textLength) {
			$part = substr($text, $textPos, 10);

			if (preg_match('~^[^a-f\\d]+~i', $part, $matches)) {
				return array(0, $matches[0], $buffer);
			}
			$buffer .= $text[$textPos];
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state QUOTE_DOUBLE.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter4($text, $textLength, $textPos)
	{
		static $delimiters = array(
			0 => '"'
		);

		$buffer = false;
		while ($textPos < $textLength) {

			$letter = $text[$textPos];

			if ($delimiters[0] === $letter) {
				return array(0, $delimiters[0], $buffer);
			}
			if (preg_match('~<\\?(php|=|(?!xml))~A', $text, $matches, 0, $textPos)) {
				return array(1, $matches[0], $buffer);
			}
			$buffer .= $letter;
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state QUOTE_SINGLE.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter5($text, $textLength, $textPos)
	{
		static $delimiters = array(
			0 => '\''
		);

		$buffer = false;
		while ($textPos < $textLength) {

			$letter = $text[$textPos];

			if ($delimiters[0] === $letter) {
				return array(0, $delimiters[0], $buffer);
			}
			if (preg_match('~<\\?(php|=|(?!xml))~A', $text, $matches, 0, $textPos)) {
				return array(1, $matches[0], $buffer);
			}
			$buffer .= $letter;
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state COMMENT_BLOCK.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter6($text, $textLength, $textPos)
	{
		static $delimiters = array(
			0 => "\n", 1 => "\t", 2 => '*/'
		);

		$buffer = false;
		while ($textPos < $textLength) {
			$part = substr($text, $textPos, 10);
			$letter = $text[$textPos];

			if ($delimiters[0] === $letter) {
				return array(0, $delimiters[0], $buffer);
			}
			if ($delimiters[1] === $letter) {
				return array(1, $delimiters[1], $buffer);
			}
			if (0 === strpos($part, $delimiters[2])) {
				return array(2, $delimiters[2], $buffer);
			}
			if (preg_match('~<\\?(php|=|(?!xml))~A', $text, $matches, 0, $textPos)) {
				return array(3, $matches[0], $buffer);
			}
			$buffer .= $letter;
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state COMMENT_LINE.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter7($text, $textLength, $textPos)
	{
		static $delimiters = array(
			0 => "\n", 1 => "\t"
		);

		$buffer = false;
		while ($textPos < $textLength) {

			$letter = $text[$textPos];

			if ($delimiters[0] === $letter) {
				return array(0, $delimiters[0], $buffer);
			}
			if ($delimiters[1] === $letter) {
				return array(1, $delimiters[1], $buffer);
			}
			if (preg_match('~<\\?(php|=|(?!xml))~A', $text, $matches, 0, $textPos)) {
				return array(2, $matches[0], $buffer);
			}
			$buffer .= $letter;
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

	/**
	 * Finds a delimiter for state REGEXP.
	 *
	 * @param string $text
	 * @param string $textLength
	 * @param string $textPos
	 * @return array
	 */
	public function findDelimiter8($text, $textLength, $textPos)
	{

		$buffer = false;
		while ($textPos < $textLength) {

			$letter = $text[$textPos];

			return array(0, $letter, $buffer);
			$buffer .= $letter;
			$textPos++;
		}
		return array(-1, -1, $buffer);
	}

}