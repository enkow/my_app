<?php
/**
 * Presence type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Presence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PresenceType.
 *
 * @package AppBundle\Form
 */
class PresenceType extends AbstractType
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
            'ip',
            TextareaType::class,
            [
                'label' => 'form.presence.allowed_ip',
                'required' => false,
                'attr' => ['rows' => 20],
            ]
        );
        $builder->get('ip')->addModelTransformer(
            new IpDataTransformer()
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
                'data_class' => Presence::class,
                'validation_groups' => 'presence-default',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'presence_type';
    }
}
