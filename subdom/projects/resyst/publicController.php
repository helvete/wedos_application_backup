<?php
/**
 * Public controller
 */
class PublicController extends BaseController {

	/**
	 * Constant stores default posts count per page
	 */
	const DEFAULT_POSTS_COUNT = 30;

	/**
	 * Posts cache
	 * @var array
	 */
	private $_posts = array();


	/**
	 * display indiviual post
	 *
	 * @param  int	$id
	 * @return PublicController
	 */
	public function displayPost($id) {
		$posts = new Posts();
		$this->_posts = $posts->get(null, $id, null);

		return $this;
	}


	/**
	 * display posts normally
	 *
	 * @param  int	$count
	 * @return PublicController
	 */
	public function displayPosts($count = self::DEFAULT_POSTS_COUNT) {
		$posts = new Posts();
		$this->_posts = $posts->get($count);

		return $this;
	}


	/**
	 * display posts by selected tag name
	 * TODO: print tag reset link
	 *
	 * @param  int	$tagName
	 * @return PublicController
	 */
	public function displayTag($tagName) {
		$posts = new Posts();
		$this->_posts = $posts->get(self::DEFAULT_POSTS_COUNT, null, $tagName);

		return $this;
	}


	/**
	 * print html data
	 *
	 * @return PublicController
	 */
	public function printHtml() {
		if (empty($this->_posts)) {
			$this->displayPosts();
		}
		$this->_posts->printHtml();

		return $this;
	}
}
