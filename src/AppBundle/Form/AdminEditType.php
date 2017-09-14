<?php
/**
 * Admin Edit type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Class AdminEditType.
 *
 * @package AppBundle\Form
 */
class AdminEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array
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
            'email',
            EmailType::class,
            [
                'label' => 'form.email',
                'required' => true,
            ]
        );
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'required' => false,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'form.password',
                ],
                'second_options' => [
                    'label' => 'form.repeat.password',
                ],
            ]
        );
        $builder->add(
            'current',
            PasswordType::class,
            [
                'label' => 'form.current.password',
                'required' => true,
                'mapped' => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver Options resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'validation_groups' => 'user-default',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_edit_type';
    }
}
