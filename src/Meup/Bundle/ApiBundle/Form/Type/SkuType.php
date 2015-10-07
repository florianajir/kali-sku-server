<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SkuType
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
 */
class SkuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('project', 'text', array(
            'label' => 'Name of the application',
        ));
        $builder->add('type', 'text', array(
            'label' => 'Type of the entity to be skued',
            'property_path' => 'foreignType',
            'required'    => false,
        ));
        $builder->add('id', 'text', array(
            'label' => 'Id of the entity to be skued',
            'property_path' => 'foreignId',
            'required'    => false,
        ));
        $builder->add('permalink', 'text', array(
            'label' => 'Permanent link of the entity.',
            'required'    => false,
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Meup\Bundle\ApiBundle\Entity\Sku',
            'csrf_protection'    => false,
            'translation_domain' => 'MeupSkuBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sku';
    }
}
