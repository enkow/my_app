<?php
/**
 * User repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

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
