<?php
/**
 * This file is part of the 1001 Pharmacies Kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\DemoBundle\Controller;

use FOS\RestBundle\Util\Codes;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
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
     * Get a sku.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Get a single center.",
     *   output = "Meup\DemoBundle\Model\Center",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the center is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="center")
     *
     * @param string $sku the sku code
     *
     * @return array
     *
     * @throws NotFoundHttpException when center not exist
     */
    public function getSkuAction($sku)
    {
        $sku = $this->getSkuManager()->get($sku);
        if (false === $sku) {
            throw $this->createNotFoundException("Sku does not exist.");
        }

        $view = new View($sku);
        $group = $this->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        $view->getSerializationContext()->setGroups(array('Default', $group));

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
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newSkuAction()
    {
        return $this->createForm(new SkuType());
    }

    /**
     * Creates a new sku from the submitted data.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Create a new sku.",
     *   input = "Meup\DemoBundle\Form\SkuType",
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
        $sku = new Sku();
        $form = $this->createForm(new SkuType(), $sku);

        $form->submit($request);
        if ($form->isValid()) {
            $this->getSkuManager()->set($sku);

            return $this->routeRedirectView('get_sku', array('id' => $sku->id));
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Removes a sku.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Delete an existing sku.",
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $sku      the sku code
     *
     * @return RouteRedirectView
     */
    public function deleteSkuAction(Request $request, $sku)
    {
        $this->getSkuManager()->delete($sku);

        return $this->routeRedirectView('get_sku', array(), Codes::HTTP_NO_CONTENT);
    }

}
