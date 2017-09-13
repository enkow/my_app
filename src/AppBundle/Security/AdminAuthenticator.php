<?php
/**
 * Admin Authenticator
 */

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use AppBundle\Repository\UserRepository;

/**
 * Class AdminAuthenticator
 */
class AdminAuthenticator implements SimpleFormAuthenticatorInterface
{
    /**
     * Encoder.
     */
    private $encoder;

    /**
     * Encoder.
     */
    private $repository;

    /**
     * Constructor
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $repository)
    {
        $this->encoder = $encoder;
        $this->repository = $repository;
    }

    /**
     * Token authenticator
     *
     * @param TokenInterface        $token        Token interface
     * @param UserProviderInterface $userProvider User provider
     * @param string                $providerKey  Provider key
     *
     * @return string Result
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            throw new CustomUserMessageAuthenticationException('security.message.invalid_credentils');
        }

        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());

        if (!$passwordValid) {
          throw new CustomUserMessageAuthenticationException('security.message.invalid_credentils');
        }

        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new CustomUserMessageAuthenticationException(
                'security.message.invalid_credentils',
                [],
                100
            );
        }

        $user->setLastLogin(new \DateTime());
        $this->repository->save($user);

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * Supports token
     *
     * @param TokenInterface $token        Token interface
     * @param string         $providerKey  Provider key
     *
     * @return string Result
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    /**
     * Create token
     *
     * @param Request $request     Request
     * @param string  $username    Provider key
     * @param string  $password    Provider key
     * @param string  $providerKey Provider key
     *
     * @return UsernamePasswordToken Result
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
