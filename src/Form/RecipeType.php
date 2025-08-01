<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class   RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('content')
            ->add('duration')
            ->add('ingredients')
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'])
            ->addEventListener( formEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoDate(...));
    }

    /**
     * Automatically sets the slug based on the title
     * @param PreSubmitEvent $event
     * @return void
     */
    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if(empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug']= $slugger->slug($data['title'])->lower()->toString();
        }
        $event->setData($data);
    }

    /**
     * Automatically sets the createdAt and updatedAt date when the recipe is created or updated
     * @param PostSubmitEvent $event
     * @return void
     */
    public function autoDate(PostSubmitEvent $event): void {
        $data = $event->getData();
        if(!($data instanceof Recipe)) {
            return;
        }
        $data->setUpdatedAt(new \DateTimeImmutable());
        if(!$data->getId()){
            $data->setCreatedAt(new \DateTimeImmutable());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
