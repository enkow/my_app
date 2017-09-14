<?php
/**
 * Year type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class YearType.
 *
 * @package AppBundle\Form
 */
class YearType extends AbstractType
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
            'start',
            NumberType::class,
            [
                'label' => 'form.year.start',
                'required' => true,
            ]
        );
        $builder->add(
            'end',
            NumberType::class,
            [
                'label' => 'form.year.end',
                'required' => true,
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
                'data_class' => Year::class,
                'validation_groups' => 'year-default',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'year_type';
    }
}
