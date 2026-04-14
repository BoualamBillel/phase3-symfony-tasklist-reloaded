<?php

namespace App\Form;

use App\Entity\Folder;
use App\Entity\Priority;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\Status;
use App\Repository\FolderRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options["user"];
        $builder
            ->add('title')
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'choice_label' => fn (Status $status) => $status->value,
            ])
            ->add('isPinned')
            ->add('folder', EntityType::class, [
                'class' => Folder::class,
                'query_builder' => function (FolderRepository $er) use ($user) {
                    return $er->createQueryBuilder('f')
                    ->where('f.owner = :user')
                    ->setParameter('user', $user)
                    ->orderBy('f.name','ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('priority', EntityType::class, [
                'class' => Priority::class,
                'choice_label' => 'level',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'user' => null,
        ]);
    }
}
