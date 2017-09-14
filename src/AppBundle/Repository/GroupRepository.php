<?php
/**
 * Group repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Group;

/**
 * Class GroupRepository.
 *
 * @package AppBundle\Repository
 */
class GroupRepository extends EntityRepository
{
    /**
     * Save entity.
     *
     * @param Group $group Group entity
     */
    public function save(Group $group)
    {
        $this->_em->persist($group);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Group $group Group entity
     */
    public function delete(Group $group)
    {
        $this->_em->remove($group);
        $this->_em->flush();
    }
}
