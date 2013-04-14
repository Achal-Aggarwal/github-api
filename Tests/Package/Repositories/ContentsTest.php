<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Repositories\Contents;
use Joomla\Registry\Registry;

/**
 * Test class for Contents.
 *
 * @since  ¿
 */
class ContentsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    \PHPUnit_Framework_MockObject_MockObject  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * @var Contents
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"message": "Generic Error"}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @since   ¿
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->options  = new Registry;
		$this->client   = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Contents($this->options, $this->client);
	}

	/**
	 * Tests the GetReadme method.
	 *
	 * @return  void
	 */
	public function testGetReadme()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/readme')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getReadme('joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the GetReadmeRef method.
	 *
	 * @return  void
	 */
	public function testGetReadmeRef()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/readme?ref=123abc')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getReadme('joomla', 'joomla-platform', '123abc'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the Get method.
	 *
	 * @return  void
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/contents?path=path/to/file.php')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', 'path/to/file.php'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the GetRef method.
	 *
	 * @return  void
	 */
	public function testGetRef()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/contents?path=path/to/file.php&ref=123abc')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', 'path/to/file.php', '123abc'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the GetArchiveLink method.
	 *
	 * @return  void
	 */
	public function testGetArchiveLink()
	{
		$this->response->code = 302;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/zipball')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getArchiveLink('joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the GetArchiveLinkRef method.
	 *
	 * @return  void
	 */
	public function testGetArchiveLinkRef()
	{
		$this->response->code = 302;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/zipball?ref=123abc')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getArchiveLink('joomla', 'joomla-platform', 'zipball', '123abc'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the GetArchiveLinkInvalidFormat method.
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testGetArchiveLinkInvalidFormat()
	{
		$this->response->code = 302;
		$this->response->body = $this->sampleString;

		$this->object->getArchiveLink('joomla', 'joomla-platform', 'invalid');
	}
}