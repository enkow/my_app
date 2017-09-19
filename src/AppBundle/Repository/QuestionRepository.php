<?php
/**
 * Question repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Question;

/**
 * Class QuestionRepository.
 *
 * @package AppBundle\Repository
 */
class QuestionRepository extends EntityRepository
{
    /**
     * Save entity.
     *
     * @param Question $question Question entity
     */
    public function save(Question $question)
    {
        $this->_em->persist($question);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Question $question Question entity
     */
    public function delete(Question $question)
    {
        $this->_em->remove($question);
        $this->_em->flush();
    }
}
