<?php
/**
 * Register type.
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

/**
 * Class RegisterType.
 *
 * @package AppBundle\Form
 */
class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'label' => 'form.username',
                'required' => true,
            ]
        );
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => [
                    'label' => 'form.password',
                ],
                'second_options' => [
                    'label' => 'form.repeat.password',
                ],
            ]
        );
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'form.auth.email',
                'required' => true,
            ]
        );
        $builder->add(
            'group',
            EntityType::class,
            [
                'label' => 'form.user.group',
                'required' => true,
                'class' => 'AppBundle:Group',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->join('AppBundle:Year', 'y')
                        ->where('g.year = y.id')
                        ->andWhere('y.active = 1')
                        ->orderBy('g.name', 'ASC');
                },
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'register_type';
    }
}
