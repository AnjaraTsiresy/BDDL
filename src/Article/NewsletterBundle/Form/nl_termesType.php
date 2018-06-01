<?php

namespace Article\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class nl_termesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('terme')
            ->add('definition')
            ->add('eG')
            ->add('dateCreate')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Article\NewsletterBundle\Entity\nl_termes'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article_newsletterbundle_nl_termes';
    }
}
