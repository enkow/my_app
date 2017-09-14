<?php
/**
 * Year repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Year;

/**
 * Class YearRepository.
 *
 * @package AppBundle\Repository
 */
class YearRepository extends EntityRepository
{
    /**
     * Save entity.
     *
     * @param Year $year Year entity
     */
    public function save(Year $year)
    {
        $this->_em->persist($year);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Year $year Year entity
     */
    public function delete(Year $year)
    {
        $this->_em->remove($year);
        $this->_em->flush();
    }
}
