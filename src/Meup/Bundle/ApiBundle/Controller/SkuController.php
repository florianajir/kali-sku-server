<?php
/**
 * This file is part of the 1001 Pharmacies Kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Meup\Bundle\ApiBundle\Factory\SkuFactory;
use Meup\Bundle\ApiBundle\Manager\SkuManagerInterface;
use Meup\Bundle\ApiBundle\Service\SkuAllocatorInterface;
use Meup\Bundle\ApiBundle\Service\SkuCodeGeneratorInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SkuController
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class SkuController extends FOSRestController
{
    /**
     * @return SkuCodeGeneratorInterface
     */
    private function getSkuCodeGenerator()
    {
        return $this->get('meup_kali.sku_generator');
    }

    /**
     * @return SkuFactory
     */
    private function getSkuFactory()
    {
        return $this->get('meup_kali.sku_factory');
    }

    /**
     * @return SkuManagerInterface
     */
    private function getSkuManager()
    {
        return $this->get('meup_kali.sku_manager');
    }

    /**
     * @return SkuAllocatorInterface
     */
    private function getSkuAllocator()
    {
        return $this->get('meup_kali.sku_allocator');
    }

    /**
     * Get a sku.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Get a single sku.",
     *   output = "Meup\Bundle\ApiBundle\Entity\Sku",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found",
     *     410 = "Returned when deleted"
     *   }
     * )
     *
     * @Rest\View()
     *
     * @Rest\QueryParam(
     *      name="sku",
     *      nullable=false,
     *      strict=true,
     *      description="Sku code"
     * )
     *
     * @param string $sku
     *
     * @return array
     *
     * @throws NotFoundHttpException when sku code not exist
     */
    public function getSkuAction($sku)
    {
        if (empty($sku)) {
            throw new BadRequestHttpException("Request parameters values does not match requirements.");
        }
        if (null === $sku = $this->getSkuManager()->findByCode($sku)) {
            throw $this->createNotFoundException("Sku does not exist.");
        }
        if (false === $sku->isActive()) {
            throw new GoneHttpException("Sku is gone.");
        }

        $view = new View($sku);

        return $view;
    }

    /**
     * Creates a new sku from the submitted data.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Create a new sku.",
     *   input = "Meup\Bundle\ApiBundle\Form\Type\SkuType",
     *   statusCodes = {
     *     200 = "Returned when existing sku found",
     *     201 = "Returned when successful",
     *     401 = "You are not authenticated",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     *
     * @return FormInterface|View
     */
    public function postSkuAction(Request $request)
    {
        $manager = $this->getSkuManager();
        $sku = $this->getSkuFactory()->create();
        $form = $this->createForm('sku', $sku);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $existantSku = $manager->findByUniqueGroup(
                $form->get('project')->getViewData(),
                $form->get('type')->getViewData(),
                $form->get('id')->getViewData()
            );

            if (false === is_null($existantSku)) {
                return new View($existantSku);
            }

            $generator = $this->getSkuCodeGenerator();
            while ($sku->getCode() === null || $manager->exists($sku->getCode())) {
                $sku->setCode($generator->generateSkuCode());
            }
            $manager->persist($sku);

            return new View($sku, Codes::HTTP_CREATED);
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Edit an sku from the submitted data.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Edit an sku.",
     *   input = "Meup\Bundle\ApiBundle\Form\Type\SkuType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param string  $sku
     * @param Request $request the request object
     *
     * @return FormInterface|View
     */
    public function putSkuAction($sku, Request $request)
    {
        $manager = $this->getSkuManager();

        if (empty($sku)) {
            throw new BadRequestHttpException("Request parameters values does not match requirements.");
        }
        if (null === $sku = $manager->findByCode($sku)) {
            throw $this->createNotFoundException("Sku does not exist.");
        }

        $form = $this->createForm('sku', $sku, array('method' => 'PUT'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($sku);

            return new View($sku, Codes::HTTP_CREATED);
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Removes a sku.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Delete an existing sku.",
     *   statusCodes={
     *     204 = "Returned when successful",
     *     400 = "Returned when sku parameter is missing",
     *     401 = "You are not authenticated",
     *     404 = "Returned when the sku is not found"
     *   }
     * )
     *
     * @Rest\QueryParam(
     *      name="sku",
     *      nullable=false,
     *      strict=true,
     *      description="Sku code"
     * )
     *
     * @param string $sku
     *
     * @return View
     *
     * @throws BadRequestHttpException when sku code is missing
     * @throws NotFoundHttpException when sku code not exist
     */
    public function deleteSkuAction($sku)
    {
        if (empty($sku)) {
            throw new BadRequestHttpException("Request parameters values does not match requirements.");
        }
        if (null === $sku = $this->getSkuManager()->findByCode($sku)) {
            throw $this->createNotFoundException("Sku does not exist.");
        }
        $this->getSkuManager()->delete($sku);

        return new View($sku, Codes::HTTP_NO_CONTENT);
    }

    /**
     * Generate and allocate a new sku code in registry.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Allocate a new sku.",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     401 = "You are not authenticated"
     *   }
     * )
     *
     * @Rest\QueryParam(
     *      name="project",
     *      nullable=false,
     *      strict=true,
     *      description="Client application name"
     * )
     *
     * @param string $project
     *
     * @return View
     */
    public function allocateSkuAction($project)
    {
        $sku = $this->getSkuAllocator()->allocate($project);

        return new View($sku, Codes::HTTP_CREATED);
    }
}
