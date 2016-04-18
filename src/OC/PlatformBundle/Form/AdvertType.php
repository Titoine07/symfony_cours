<?php

namespace OC\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OC\PlatformBundle\Repository\AdvertRepository;

class AdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('date',		'date')
			->add('title',		'text')
			->add('author',		'text')
			->add('content',		'textarea')
			->add('published',	'checkbox', array('required' => false))
			->add('image',		new ImageType())
		
			
			/*Rappel :
			- 1er argument : nom du champ, ici « categories », car c'est le nom de l'attribut
			- 2e argument : type du champ, ici « collection » qui est une liste de quelque chose
			- 3e argument : tableau d'options du champ*/
			
			->add('categories', 'entity', array(
				'class' => 'OCPlatformBundle:Category',
				'property' => 'name',
				'multiple' => true
			))
			->add('save', 'submit')
			;
	}

//	public function setDefaultOptions(OptionsResolverInterface $resolver) {
//		$resolver->setDefaults(array(
//			'data_class' => 'OC\PlatformBundle\Entity\Advert'
//		));
//	}

	/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
	{
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Advert'
        ));
	}
	
	public function getName() {
		return 'oc_platformbundle_advert';
	}
}
