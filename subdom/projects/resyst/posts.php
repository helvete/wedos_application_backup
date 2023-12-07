<?php
/**
 * Posts class
 * TODO
 */
class Posts implements Countable {
	/**
	 * Post links
	 *
	 * @var	array
	 */
	protected $_posts = array();

	/**
	 * Construct
	 */
	public function __construct() {
	}


	/**
	 * Get posts from DB according to input parameters
	 *
	 * @param  int		$count
	 * @param  int		$id
	 * @param  string	$tag
	 * @return Posts
	 */
	public function get($count = false, $id = false, $tag = false) {
		$queryString = '
			SELECT resyst_db.time as timestamp, resyst_db.name as title,
				resyst_db.text as contents, resyst_db.id as id,
				resyst_tag.id as tag_id, resyst_tag.tagname as tag_name,
				resyst_tag.parenttag as tag_parentName,
				resyst_tag.colour as tag_colour
			FROM resyst_db
			INNER JOIN resyst_tag
				ON resyst_db.tag = resyst_tag.id
			'. ($id ? "WHERE resyst_db.id = $id" : '') . '
			'. ($tag ? "WHERE resyst_tag.tagname = '$tag'" : '') . '
			ORDER BY id DESC
			'. ($count ? "LIMIT $count" : '') . '
		';
		$pdo = DBConnector::getPDO();
		$query = $pdo->prepare($queryString);

		$query->execute(array());
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as &$postData) {
			$postData['contents'] = html_entity_decode($postData['contents']);
			$postData = $this->_processTagData($postData);
			$this->_posts[] = new Post($postData);
		}

		return $this;
	}


	/**
	 * Posts count
	 * Defined by Countable interface
	 *
	 * @return int
	 */
	public function count() {
		return count($this->_posts);
	}


	/**
	 * Dump posts
	 *
	 * @return Posts
	 */
	public function dump() {
		unset($this->_posts);
		$this->_posts = array();
		return $this;
	}


	/**
	 * Print posts
	 *
	 * @param  bool	$remove
	 * @return Posts
	 */
	public function printHtml($remove = true) {
		foreach ($this->_posts as $post) {
			$post->generateHtml();
		}
		if ($remove) {
			$this->dump();
		}
		return $this;
	}


	/**
	 * Process tag data of post
	 *
	 * @param  array	$postData
	 * @return array
	 */
	private function _processTagData($postData) {
		$fields = array('tag_id', 'tag_name', 'tag_parentName', 'tag_colour');
		$tagInitArray = array();
		foreach ($fields as $colName) {
			$initName = explode('_', $colName);
			$tagInitArray[$initName[1]] = $postData[$colName];
			unset($postData[$colName]);
		}
		$tag = new Tag($tagInitArray);
		$postData['tag'] = $tag;

		return $postData;
	}
}

