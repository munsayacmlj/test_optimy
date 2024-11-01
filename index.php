<?php
require 'vendor/autoload.php';

use App\Utils\{NewsManager, CommentManager};

/**
 * Used the htmlspecialchars() function to prevent XSS attacks
 */
foreach (NewsManager::getInstance()->listAll() as $news) {
	echo("############ NEWS " . htmlspecialchars($news->getTitle()) . " ############\n");
	echo(htmlspecialchars($news->getBody()) . "\n");
	foreach (CommentManager::getInstance()->listAll() as $comment) {
		if ($comment->getNewsId() == $news->getId()) {
			echo("Comment " . htmlspecialchars($comment->getId()) . " : " . htmlspecialchars($comment->getBody()) . "\n");
		}
	}
}