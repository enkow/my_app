<?php
/**
 * Test repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Test;

/**
 * Class TestRepository.
 *
 * @package AppBundle\Repository
 */
class TestRepository extends EntityRepository
{
    /**
     * Save entity.
     *
     * @param Test $test Test entity
     */
    public function save(Test $test)
    {
        $this->_em->persist($test);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Test $test Test entity
     */
    public function delete(Test $test)
    {
        $this->_em->remove($test);
        $this->_em->flush();
    }

    /**
     * Active test
     *
     * @param Test $test Test
     *
     * @return boolean Result
     */
    public function active(Test $test)
  	{
        $test->setEnd(new \DateTime('+'.$test->getTime().' minutes'));

        return $this->save($test);
  	}

    /**
     * Get max points;
     *
     * @param Test $test Test
     *
     * @return integer Result
     */
    public function getMaxPoints($test)
    {
        $points = 0;
        foreach($test->getQuestions() as $question) {
            $points += $question->getPoints();
        }

        return $points;
    }
}
