<?php
/**
 * Bookmark repository.
 */
namespace AppBundle\Repository;

/**
 * Class BookmarkRepository.
 *
 * @package AppBundle\Repository
 */
class BookmarkRepository
{
    /**
     * Bookmarks array
     *
     * @var array $bookmarks
     */
    protected $bookmarks = [
        [
            'url' => 'http://symfony.com',
            'tags' => [
                'PHP', 'framework', 'Symfony',
            ],
        ],
        [
            'url' => 'http://learngitbranching.js.org',
            'tags' => [
                'tools', 'Git', 'VCS', 'tutorials',
            ],
        ],
        [
            'url' => 'https://www.jetbrains.com/phpstorm',
            'tags' => [
                'tools', 'IDE', 'PHP',
            ],
        ],
        [
            'url' => 'http://twig.sensiolabs.org',
            'tags' => [
                'tools', 'templates', 'Twig', 'Symfony', 'PHP',
            ],
        ],
    ];

    /**
     * Find all bookmarks.
     *
     * @return array Result
     */
    public function findAll()
    {
        return $this->bookmarks;
    }

    /**
     * Find single record by its id.
     *
     * @param integer $id Single record index
     *
     * @return array Result
     */
    public function findOneById($id)
    {
        return isset($this->bookmarks[$id]) && count($this->bookmarks)
            ? $this->bookmarks[$id] : null;
    }
}
