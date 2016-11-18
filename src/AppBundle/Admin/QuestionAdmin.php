<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class QuestionAdmin extends Admin
{

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('category')
            ->add('cost')
            ->add('question')
            ->add('answer')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('category', 'sonata_type_model', array(
                'empty_value' => '',
                'btn_add'     => false,
            ))
            ->add('cost')
            ->add('question', null, array(
                'attr' => array(
                    'rows' => 10,
                ),
            ))
            ->add('answer')
            ->add('image',
                'sonata_media_type',
                array(
                    'provider' => 'sonata.media.provider.image',
                    'context'  => 'default',
                )
            )
            ->add('answerImage',
                'sonata_media_type',
                array(
                    'provider' => 'sonata.media.provider.image',
                    'context'  => 'default',
                )
            )
        ;
    }
}
