<?php

namespace Article\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class nl_monthnewsletterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monthNewsletter')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Article\NewsletterBundle\Entity\nl_monthnewsletter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article_newsletterbundle_nl_monthnewsletter';
    }
}
