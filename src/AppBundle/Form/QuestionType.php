<?php
/**
 * Question type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class QuestionType.
 *
 * @package AppBundle\Form
 */
class QuestionType extends AbstractType
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
                'label' => 'form.question.name',
                'required' => true,
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => 'form.question.content',
                'required' => true,
            ]
        );
        $builder->add(
            'type',
            ChoiceType::class,
            [
                'label' => 'form.question.answer',
                'required' => true,
                'choices'  => [
                    'choice.one_correct' => 1,
                    'choice.many_correct' => 2,
                    'choice.regex' => 3,
                ],
                'choices_as_values' => true,
            ]
        );
        $builder->add(
            'answer',
            TextType::class,
            [
                'label' => 'form.question.answer',
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
                'data_class' => Question::class,
                'validation_groups' => 'question-default',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'question_type';
    }
}
