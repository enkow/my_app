<?php
/**
 * Group type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class GroupType.
 *
 * @package AppBundle\Form
 */
class GroupType extends AbstractType
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
            'name',
            TextType::class,
            [
                'label' => 'form.group.name',
                'required' => true,
            ]
        );
        $builder->add(
            'year',
            EntityType::class,
            [
                'label' => 'form.group.year',
                'required' => true,
                'class' => 'AppBundle:Year',
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
                'data_class' => Group::class,
                'validation_groups' => 'group-default',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'group_type';
    }
}
