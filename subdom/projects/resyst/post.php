<?php
/**
 * Post class
 * TODO
 */
class Post {
	/**
	 * Post id
	 *
	 * @var	int
	 */
	public $id;

	/**
	 * Post title
	 *
	 * @var	string
	 */
	public $title;

	/**
	 * Post contents
	 *
	 * @var	string
	 */
	public $contents;

	/**
	 * Post tag
	 *
	 * @var	Tag
	 */
	public $tag;

	/**
	 * Post links
	 *
	 * @var	array
	 */
	public $links = array();

	/**
	 * Post timestamp
	 *
	 * @var	string
	 */
	public $timestamp;

	/**
	 * Construct
	 *
	 * @param  array	$rawData
	 */
	public function __construct(array $rawData) {

		// init post
		foreach ($rawData as $itemName => $item) {
			$this->$itemName = $item;
		}
	}


	/**
	 * Generate html post
	 *
	 * @return void
	 */
	public function generateHtml() {
		$post = $this;
		include './post.template.php';
	}


	/**
	 * Get post anchor
	 *
	 * @return string
	 */
	public function getAnchor() {
		return "#$this->id";
	}


	/**
	 * Generate indivual post post-link
	 *
	 * @return void
	 */
	public function getIndividual() {
		$link = array(
			'action' => '',
			'operations' => array(
				'displayPost' => $this->id,
			),
			'submit-val' => '@',
		);
		include './getLink.template.php';
	}
}
