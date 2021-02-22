<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TaskType extends AbstractType
{
    private $authorization;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorization = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = ['New' => 'New', 'Open' => 'Open', 'Done' => 'Done'];

        $builder
            ->add("description", TextareaType::class, [
            ])
            ->add("status", ChoiceType::class, [
                'choices' => $choices,
            ])
            ->add("user", EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'required' => true,
            ]);
        if ($this->authorization->isGranted('ROLE_ADMIN')) {
            $builder->add("checkbox", CheckboxType::class, [
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
