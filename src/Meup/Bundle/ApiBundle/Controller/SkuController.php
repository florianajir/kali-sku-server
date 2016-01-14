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
use Meup\Bundle\ApiBundle\Manager\SkuManagerInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc; // Do not delete this line (annotations)
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
     * @return SkuManagerInterface
     */
    private function getSkuManager()
    {
        return $this->get('meup_kali.sku_manager');
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
     *     401 = "You are not authenticated",
     *     404 = "Returned when not found",
     *     410 = "Returned when deleted"
     *   },
     *   parsers = {
     *     "Nelmio\ApiDocBundle\Parser\JmsMetadataParser"
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
        if (null === $sku = $this->getSkuManager()->find($sku)) {
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
     *   input = "Meup\Bundle\ApiBundle\Form\SkuType",
     *   statusCodes = {
     *     200 = "Returned when existing entity found",
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     401 = "You are not authenticated"
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
        $form = $this->createForm($this->container->get('meup_kali.sku_form_type'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $existantSku = $manager->findByProjectTypeId(
                $form->get('project')->getViewData(),
                $form->get('type')->getViewData(),
                $form->get('id')->getViewData()
            );

            if (false === is_null($existantSku)) {
                return new View($existantSku);
            }
            $sku = $form->getData();
            $manager->save($sku);

            return new View($sku, Codes::HTTP_CREATED);
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Edit an existing sku from the submitted data.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Edit an sku.",
     *   input = "Meup\Bundle\ApiBundle\Form\SkuType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     401 = "You are not authenticated",
     *     404 = "Returned when not found",
     *     409 = "Returned if an existing resource is found with submitted values"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param string  $sku
     *
     * @return FormInterface|View
     */
    public function putSkuAction(Request $request, $sku)
    {
        $manager = $this->getSkuManager();
        if (empty($sku)) {
            throw new BadRequestHttpException("Request parameters values does not match requirements.");
        }
        if (null === $sku = $manager->find($sku)) {
            throw $this->createNotFoundException("Sku does not exist.");
        }
        $form = $this->createForm('sku', $sku, array('method' => 'PUT'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $existantSku = $manager->findByProjectTypeId(
                $form->get('project')->getViewData(),
                $form->get('type')->getViewData(),
                $form->get('id')->getViewData()
            );
            if (false === is_null($existantSku)) {
                return new View($existantSku, Codes::HTTP_CONFLICT);
            }
            $manager->save($sku);

            return new View($sku, Codes::HTTP_OK);
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Deletes a sku from registry.
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
        if (null === $sku = $this->getSkuManager()->find($sku)) {
            throw $this->createNotFoundException("Sku does not exist.");
        }
        $this->getSkuManager()->delete($sku);

        return new View(null, Codes::HTTP_NO_CONTENT);
    }

    /**
     * Disables a sku.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Disable an existing sku.",
     *   statusCodes={
     *     200 = "Returned when successful",
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
    public function disableSkuAction($sku)
    {
        if (empty($sku)) {
            throw new BadRequestHttpException("Request parameters values does not match requirements.");
        }
        if (null === $sku = $this->getSkuManager()->find($sku)) {
            throw $this->createNotFoundException("Sku does not exist.");
        }
        $this->getSkuManager()->disable($sku);

        return new View($sku, Codes::HTTP_OK);
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
     *     400 = "Returned when project parameter is missing",
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
        $sku = $this->getSkuManager()->allocate($project);

        return new View($sku, Codes::HTTP_CREATED);
    }
}
