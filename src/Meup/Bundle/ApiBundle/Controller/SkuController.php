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

use FOS\RestBundle\Util\Codes;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SkuController
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
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
     * @return SkuFactoryInterface
     */
    private function getSkuFactory()
    {
        return $this->get('meup_kali.sku_factory');
    }

    /**
     * Get a sku.
     *
     * @ApiDoc(
     *   section = "Sku",
     *   resource = true,
     *   resourceDescription="Get a single sku.",
     *   output = "Meup\Bundle\ApiBundle\Model\Sku",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the center is not found"
     *   }
     * )
     *
     * @Rest\View()
     *
     * @Rest\QueryParam(
     *      name="sku",
     *      nullable=false,
     *      strict=true,
     *      description="Sku code."
     * )
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return array
     *
     * @throws NotFoundHttpException when sku code not exist
     */
    public function getSkuAction(ParamFetcherInterface $paramFetcher)
    {
        if ('' === $paramFetcher->get('sku')){
            throw new BadRequestHttpException("Request parameters values does not match requirements.");
        }
        $sku = $this->getSkuManager()->getByCode($paramFetcher->get('sku'));
        if (false === $sku) {
            throw $this->createNotFoundException("Sku does not exist.");
        }
        $view = new View($sku);

        return $view;
    }

    /**
     * Presents the form to use to create a new sku.
     *
     * @ApiDoc(
     *   resource = true,
     *   resourceDescription="Present a form to create a new sku.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\View()
     *
     * @return FormTypeInterface
     */
    public function newSkuAction()
    {
        return $this->createForm('sku');
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
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|RouteRedirectView
     */
    public function postSkuAction(Request $request)
    {
        $sku = $this->getSkuFactory()->create();
        $form = $this->createForm('sku', $sku);

        $form->submit($request);
        if ($form->isValid()) {
            $this->getSkuManager()->create($sku);

            return $this->routeRedirectView('get_sku', array('sku' => $sku->getCode()));
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
     *     404 = "Returned when the sku is not found"
     *   }
     * )
     *
     * @Rest\QueryParam(
     *      name="sku",
     *      nullable=false,
     *      strict=true,
     *      description="Sku code."
     * )
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return RouteRedirectView
     *
     * @throws NotFoundHttpException when sku code not exist
     */
    public function deleteSkuAction(ParamFetcherInterface $paramFetcher)
    {
        if ('' === $paramFetcher->get('sku')){
            throw new BadRequestHttpException("Request parameters values does not match requirements.");
        }
        $sku = $this->getSkuManager()->getByCode($paramFetcher->get('sku'));

        if (false === $sku) {
            throw $this->createNotFoundException("Sku does not exist.");
        }

        $this->getSkuManager()->delete($sku);

        return $this->routeRedirectView('get_sku', array(), Codes::HTTP_NO_CONTENT);
    }

}
