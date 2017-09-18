<?php
/**
 * UserPresence repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\UserPresence;

/**
 * Class UserPresenceRepository.
 *
 * @package AppBundle\Repository
 */
class UserPresenceRepository extends EntityRepository
{
    /**
     * Save entity.
     *
     * @param UserPresence $userPresence UserPresence entity
     */
    public function save(UserPresence $userPresence)
    {
        $this->_em->persist($userPresence);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param UserPresence $userPresence UserPresence entity
     */
    public function delete(UserPresence $userPresence)
    {
        $this->_em->remove($userPresence);
        $this->_em->flush();
    }
}
