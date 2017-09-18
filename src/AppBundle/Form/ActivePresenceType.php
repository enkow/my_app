<?php
/**
 * Active Presence type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Presence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

/**
 * Class ActivePresenceType.
 *
 * @package AppBundle\Form
 */
class ActivePresenceType extends AbstractType
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
            'group',
            EntityType::class,
            [
                'label' => 'form.presence.group',
                'required' => false,
                'class' => 'AppBundle:Group',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->join('AppBundle:Year', 'y')
                        ->where('g.year = y.id')
                        ->andWhere('y.active = 1')
                        ->orderBy('g.name', 'ASC');
                }
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
