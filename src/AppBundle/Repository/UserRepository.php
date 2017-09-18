<?php
/**
 * User repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;

/**
 * Class UserRepository.
 *
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    /**
     * Approve User
     *
     * @param User $user User
     *
     * @return boolean Result
     */
    public function approve(User $user)
  	{
        $user->setApproved(true);

        return $this->save($user);
  	}

    /**
     * Create User
     *
     * @param Symfony\Bridge\Twig\TwigEngine $engine   Templating
     * @param User                           $user     User
     * @param Role                           $role     Role
     * @param string                         $password Hashed password
     *
     * @return boolean Result
     */
    public function createUser($engine, User $user, Role $role, $password)
  	{
        $user->setConfirmToken($token = $this->generateToken());
    		$user->setRole($role);
        $user->setPassword($password);
        $mail = $user->getEmail();
        $this->save($user);

        return $this->setMessage(
            $engine,
            'register',
            'Rejestracja konta',
            $mail,
            compact('token', 'user')
        );
  	}

    /**
     * Confirm User
     *
     * @param User $user User
     *
     * @return boolean Result
     */
    public function confirmUser(User $user)
  	{
        $user->setConfirmToken(null);

        return $this->save($user);
  	}

    /**
     * Reset password
     *
     * @param Symfony\Bridge\Twig\TwigEngine $engine Templating
     * @param User                           $user   User
     *
     * @return \Swift_Message Result
     */
    public function resetPassword($engine, User $user)
    {
        $user->setResetToken($token = $this->generateToken());
        $mail = $user->getEmail();
        $this->save($user);

        return $this->setMessage(
            $engine,
            'reset',
            'Reset hasła',
            $mail,
            compact('token', 'user')
        );
    }

    /**
     * Change password
     *
     * @param User   $user     User
     * @param string $password Hashed password
     *
     * @return string Result
     */
    public function changePassword(User $user, $password)
    {
        $user->setPassword($password);
        $user->setResetToken(null);

        return $this->save($user);
    }

    /**
     * Edit profile
     *
     * @param User        $user     User
     * @param string|null $password Hashed password
     *
     * @return string Result
     */
    public function editProfile(User $user, $password = null)
    {
        if ($password) {
            $user->setPassword($password);
        }

        return $this->save($user);
    }

    /**
     * Find students on year
     *
     * @param \AppBundle\Entity\Year $year Year
     *
     * @return string Result
     */
    public function findByYear($year)
    {
        return $this->_em->createQuery('
            SELECT u
            FROM AppBundle:User u
            JOIN AppBundle:Group g
            WHERE g.id = u.group
            AND g.year = :year
            ORDER BY u.username asc
        ')->setParameter('year', $year)
        ->getResult();
    }

    /**
     * Find students on year
     *
     * @param string $string String
     *
     * @return string Result
     */
    public function findByString($string)
    {
        $string = '%'.$string.'%';
        return $this->_em->createQuery('
            SELECT u
            FROM AppBundle:User u
            WHERE (u.username LIKE :q
            OR u.email LIKE :q)
            AND u.group IS NOT NULL
        ')->setParameter('q', $string)
        ->getResult();
    }

    /**
     * Find students on year
     *
     * @param string $string String
     *
     * @return string Result
     */
    public function getPresence($user, $presence)
    {
        return $this->_em->createQuery('
            SELECT up
            FROM AppBundle:UserPresence up
            WHERE up.user = :user
            AND up.presence = :presence
        ')->setParameter('user', $user)
        ->setParameter('presence', $presence)
        ->setMaxResults(1)
        ->getOneOrNullResult();
    }

    /**
     * Token generator
     *
     * @param integer $length Token length
     *
     * @return string Result
     */
    private function generateToken($length = 32)
  	{
        $string = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $string .= $keys[array_rand($keys)];
        }

        return $string;
  	}

    /**
     * Set email message
     *
     * @param Symfony\Bridge\Twig\TwigEngine $engine   Templating
     * @param string                         $template Message template
     * @param string                         $subject  Message subject
     * @param string                         $to       Message reciver
     * @param array                          $data     Message data
     *
     * @return \Swift_Message Result
     */
    private function setMessage($engine, $template, $subject, $to, $data = [])
    {
      return (new \Swift_Message($subject))
          ->setFrom('admin@serwis.app')
          ->setTo($to)
          ->setBody(
              $engine->render(
                  sprintf('mail/%s.html.twig', $template),
                  $data
              ),
              'text/html'
          );
    }
}
