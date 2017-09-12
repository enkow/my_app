<?php
/**
 * Tag repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TagRepository.
 *
 * @package AppBundle\Repository
 */
class TagRepository extends EntityRepository
{
    /**
     * Save entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag)
    {
        $this->_em->persist($tag);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag)
    {
        $this->_em->remove($tag);
        $this->_em->flush();
    }
}
