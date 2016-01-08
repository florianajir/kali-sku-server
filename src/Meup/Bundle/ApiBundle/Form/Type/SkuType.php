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

use Meup\Bundle\ApiBundle\Factory\SkuFactory;
use Meup\Bundle\ApiBundle\Manager\SkuManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SkuType
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
 */
class SkuType extends AbstractType
{
    /**
     * @var SkuFactory
     */
    private $skuFactory;

    /**
     * @var SkuManagerInterface
     */
    private $skuManager;

    /**
     * @param SkuFactory $skuFactory
     * @param SkuManagerInterface $skuManager
     */
    public function __construct(SkuFactory $skuFactory, SkuManagerInterface $skuManager)
    {
        $this->skuFactory = $skuFactory;
        $this->skuManager = $skuManager;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Meup\Bundle\ApiBundle\Entity\Sku',
            'csrf_protection'    => false,
            'translation_domain' => 'MeupSkuBundle',
            'empty_data' => function (FormInterface $form) {
                return $this->skuFactory->create(
                    $this->skuManager->generateUniqueCode(),
                    $form->get('project')->getData()
                );
            }
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
