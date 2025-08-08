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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;


class   RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('slug', TextType::class, [
                'required' => false,
                // sequentially permet de valider plusieurs contraintes dans le tableau une après l'autre, si une des contraintes échoue, la validation s'arrête et l'erreur est renvoyée sans continuer à parcourir lz tableau.
                'constraints' => new Sequentially([
                    new Length(min: 6, minMessage: 'Le slug doit contenir en moin 6 caractères'),
                    new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: "Ceci n'est pas un slug valide.")

                ])
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
