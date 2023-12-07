<?php
/**
 * Tag class
 * TODO
 */
class Tag {
	/**
	 * Tag id
	 *
	 * @var	int
	 */
	public $id;

	/**
	 * Tag name
	 *
	 * @var	string
	 */
	public $name;

	/**
	 * Name of parent of this tag
	 *
	 * @var	string
	 */
	public $parentName;

	/**
	 * Tag colour
	 *
	 * @var	string
	 */
	public $colour;

	/**
	 * Parent tag
	 *
	 * @var	Tag
	 */
	public $parent;

	/**
	 * Tag tree from downside to up
	 *
	 * @var array
	 */
	static private $_upTree;

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
	 * Get available tags from DB and create a tree;
	 * Fills static cache property
	 *
	 * @return void
	 */
	static private function _assembleTagTree() {
		$tagsData = self::_retrieveTagData();

		// reformat the array to name => data
		foreach ($tagsData as $index => $tagData) {
			$tagsData[$tagData['name']] = $tagData;
			unset($tagsData[$index]);
		}
		foreach ($tagsData as $name => $tagData) {
			self::$_upTree[$name] = self::_findRootPath($name, $tagsData);
		}
	}


	/**
	 * Recursive function for assembling tag tree
	 *
	 * @param  string	$name
	 * @param  array	$dataStorage
	 * @return array
	 */
	static private function _findRootPath($name, $dataStorage) {
		$tag = new Tag($dataStorage[$name]);
		$tag->parent = null;
		if (empty($tag->parentName)) {
			return $tag;
		}
		$tag->parent = self::_findRootPath($tag->parentName, $dataStorage);

		return $tag;
	}


	/**
	 * Retrieve tag data from DB
	 *
	 * @return array
	 */
	static private function _retrieveTagData() {
		$queryString = '
			SELECT resyst_tag.id as id, resyst_tag.tagname as name,
				resyst_tag.parenttag as parentName, resyst_tag.colour as colour
			FROM resyst_tag
			ORDER BY parentName, id ASC
		';
		$pdo = DBConnector::getPDO();
		$query = $pdo->prepare($queryString);

		$query->execute(array());

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	 * Generate html upTree
	 *
	 * @param  string	$tagName
	 * @return void
	 */
	static public function generateHtmlUpTree($tagName) {
		if (empty(self::$_upTree)) {
			self::_assembleTagTree();
		}
		$tag = self::$_upTree[$tagName];
		include('./tags.template.php');
	}
}

