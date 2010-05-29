<?php
/********************************************************************************************
* Farm Claims Subsidy System																*
* 																							*
* Written by Simon Walker ( 090931545 ) for module F27DB: Introduction to Database Systems	*
* Lecturer: Brian Palmer and Adil Ibrahim													*
* 																							*
* OutputPage class: handles all output in a secure and XHTML-valid method					*
*********************************************************************************************/

/**************************************************************************
* Please note: This file was originally written by Simon Walker for a     *
* university assignment, and may need adapting for purpose.               *
*                                                                         *
* DO NOT CHANGE THE EXISTING INTERFACE OF THIS CLASS unless you really    *
* know what you're doing.                                                 *
**************************************************************************/

// NOTE: Use Smarty instead. References to this class should be removed.

// check that this code is being called from a valid entry point. 
if(!defined("WARS"))
	die("Invalid code entry point!");

/**
 * OutputPage class
 * 
 * @author Simon Walker
 * 
 * This class provides 3 main public methods, tagStart, tagEnd, and output.
 * When tagStart is first used, that tag is stored in a cache. If tagEnd is called directly afterwards,
 * it will check the cache, and if there is a tag there, it will write it as a self-closing tag.
 * 
 * If output is called after a tagStart, the cache is written, then the output is written.
 * When tagEnd is then called, it notices there is nothing in the cache, and writes a closing tag.
 * This means the class will write <br /> instead of <br></br>, for example.
 * 
 */
class OutputPage
{
	/**
	 * @var string Data stream of XHTML waiting to be returned to the webserver
	 */
	private $data;
	
	/**
	 * Title of the page.
	 * 
	 * @var string
	 */
	public $title;
	
	/**
	 * Array of open tags.
	 * @var array
	 */
	private $tags = array();
	
	/**
	 * The last opened tag, to dynamically write either <tag>x</tag> or <tag />
	 * 
	 * @var array
	 */
	private $cache = array();
			
	public $stylesheets = array();
	
	/**
	 * Constructor, protected to ensure nothing can instantiate it
	 * 
	 * @return unknown_type
	 */
	protected function __construct()
	{
		
	}
	
	/**
	 * Singleton instance of this class
	 * 
	 * @var OutputPage
	 */
	private static $instance;
	
	/**
	 * Return the singleton instance of the class
	 * 
	 * @return OutputPage
	 */
	public static function getInstance()
	{
		if(!is_object(OutputPage::$instance))
		{
			OutputPage::$instance = new OutputPage();
		}
		
		return OutputPage::$instance;
	}
	
	/**
	 * Write the non-HTML text.
	 * 
	 * The text specified will be filtered to protect against HTML injection attacks.
	 * 
	 * @param string $text text to output
	 */
	public function output($text)
	{
		// nothing to output? no need for this function to continue
		if($text == "") return;
		
		$this->forceWriteOut();
		
		// write the output, passing through htmlentities, so html tags passed through here will not be
		// parsed by the web browser (closing the cross-site scripting security holes)
		$this->data .= htmlentities($text);
	}
	
	/**
	 * output the raw html specified.
	 * 
	 * WARNING: if used on user-provided data, this function is not safe for use.
	 * 
	 * This function is identical to output, with the exception that it is not filtered.
	 * 
	 * @param string $html
	 */
	public function outputHtml($html)
	{
		if($html == "") return;
		
		$this->forceWriteOut();
		
		$this->data .= $html;
	}
	
	/**
	 * Write a HTML start tag
	 * 
	 * @param string $name Name of the tag
	 * @param string $params attributes of the tag
	 */
	public function tagStart($name, $params = array())
	{
		
		$this->forceWriteOut();
		
		// Add the new tag to the cache.
		$this->cache[0] = $name;
		$this->cache[1] = $params;
	}
	
	/**
	 * Store the tag
	 * 
	 * @param string $name Name of the tag
	 * @param bool $close Close the tag now?
	 * @param array $params Attributes of the tag
	 */
	private function storeTag($name, $close = false, $params = array())
	{
		// Write the tag name
		$this->data .= "<" . htmlentities($name);
		
		// iterate through the parameters, if they exist
		if($params != array())
		{
			foreach ($params as $key => $value) {
				// add parameters one-by-one to the data stream.
				$this->data .= " " . htmlentities($key) . "=\"" . htmlentities($value) . "\"";
			}
		}
		
		// Close the tag?
		if($close)
			// yes! close it
			$this->data .= " /";
		else
			// no, add the tag to a stack.
			$this->tags[] = $name;
		
		$this->data .= ">";
	}
	
	/**
	 * Close the last opened tag
	 */
	public function tagEnd()
	{
		// anythign in the cache?
		if(sizeof($this->cache) == 0)
		{ // no. something was written in the middle.
			// write a close tag.
			$this->data .= "</" . htmlentities(array_pop($this->tags)) . ">";
		}
		else
		{
			// yes. store the tag that's in the cache
			$this->storeTag($this->cache[0], true, $this->cache[1]);
			$this->cache = array();
		}
	} 
	
	/**
	 * Write out a table based on the array given to it
	 * 
	 * @param array $data
	 * @param boolean $firstRowIsColHeaders
	 * @param function $extraRowHtmlCallback Extra HTML to be added to the end of each row.
	 */
	public function table($data, $firstRowIsColHeaders = false, $extraRowHtmlCallback = null)
	{
		// do I write the column headers?
		$writeColumnHeaders = $firstRowIsColHeaders;
		
		// start a table.
		$this->tagStart('table');
		// iterate each row
		foreach ($data as $row) {
			// start a row tag
			$this->tagStart('tr');
			// iterate each cell
			foreach ($row as $cell) {
				// am I supposed to be writing headers this time around?
				if($writeColumnHeaders)
				{// yes, write a header
					$this->tagStart('th');
				}
				else
				{// no, write a cell
					$this->tagStart('td');
				}
				// write the data
				$this->output($cell);
				// end the cell
				$this->tagEnd();
			}
			
			// is there a callable function that has been requested to be executed?
			if(is_callable($extraRowHtmlCallback))
			{
				// output the html generated by the extra function.
				$this->outputHtml(call_user_func($extraRowHtmlCallback,$row, $writeColumnHeaders));
			}
			
			// don't write column headers any more.
			$writeColumnHeaders=false;
			// end the row
			$this->tagEnd();
		}
		// end the table
		$this->tagEnd();
	}
	
	/**
	 * Actually send the page to the server
	 */
	public function sendPage()
	{
		// is there something in the cache?
		if(sizeof($this->cache) != 0)
		{
			// yes, write it out
			$this->storeTag($this->cache[0], false, $this->cache[1]);
			$this->cache = array();
		}
		// are there open tags on the stack?
		while(sizeof($this->tags) != 0)
			// write out, and repeat
			$this->tagEnd();
			 
		// actually print the header, data stream, and footer to the client!
		// THIS IS THE ONLY PLACE DATA IS EVER WRITTEN TO THE CLIENT!
		echo $this->getHeader() . $this->data . $this->getFooter();
	}

	/**
	 * Retrieve the xhtml header, for the entire site. 
	 * This provides JUST the head section, and starts the body
	 * 
	 * @return HTML Standard header
	 */
	private function getHeader()
	{
		global $baseFilePath;

		$time = time();
		
		$header = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>{$this->title}</title>
HTML;
		foreach ($this->stylesheets as $style) {
			$header .= <<<HTML
	<link rel="stylesheet" type="text/css" href="{$baseFilePath}/{$style}?{$time}" />
HTML;
		}
		
		$header .= <<<HTML
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<div id="globalwrapper">
HTML;

		return $header;
	}
	
	
	/**
	 * Get the standard footer
	 * 
	 * @return HTML standard footer
	 */
	private function getFooter()
	{
		$footer = <<<HTML
</div>
</body>
</html>
HTML;

		return $footer;
	}
	
	
	public function wrap($text, $tag, $params = null)
	{
		$this->tagStart($tag, $params);
		$this->output($text);
		$this->tagEnd();
	}

	public function tag($tag, $params = null)
	{
		$this->tagStart($tag, $params);
		$this->tagEnd();
	}
	
	public function anchor($target, $text)
	{
		$this->tagStart('a', array('href' => $target));
		$this->output($text);
		$this->tagEnd();
	}
	
	public function forceWriteOut()
	{
		// has a tag been stored in the cache instead of written out?
		if(sizeof($this->cache) != 0)
		{
			// write out this tag to the output, not closing the tag
			$this->storeTag($this->cache[0], false, $this->cache[1]);
			// empty the cache
			$this->cache = array();
		}
	}
}