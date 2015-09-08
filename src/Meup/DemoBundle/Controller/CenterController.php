<?php
/**
 * This file is part of the 1001 Pharmacies Symfony REST edition
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/symfony-bifrost-edition>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\DemoBundle\Controller;

use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

use Meup\DemoBundle\Form\CenterType;
use Meup\DemoBundle\Model\Center;

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
 * Class CenterController
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class CenterController extends FOSRestController
{
    /**
     * return \Meup\DemoBundle\Manager\CenterManager
     */
    public function getCenterManager()
    {
        return $this->get('meup.demo.center_manager');
    }

    /**
     * List all centers.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Retrieve list of centers.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(
     *  name="page",
     *  requirements="\d+",
     *  nullable=true,
     *  description="Page from which to start listing centers."
     * )
     * @Annotations\QueryParam(
     *  name="perPage",
     *  requirements="\d+",
     *  default="10",
     *  description="How many centers to return."
     * )
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getCentersAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page') ? $paramFetcher->get('page') : 1;
        $start  = (0 == $page) ? 0 : $page - 1;
        $limit  = $paramFetcher->get('perPage') ? $paramFetcher->get('perPage') : 10;

        $centers = $this->getCenterManager()->fetch($start, $limit);
        $total   = $this->getCenterManager()->count();

        $totalPages  = ceil($total / $limit);

        return new PaginatedRepresentation(
            new CollectionRepresentation($centers),
            'get_centers',
            array(),
            $page,
            $limit,
            $totalPages,
            'page',
            'perPage',
            false,
            $total
        );
    }

    /**
     * Get a single center.
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
     * @param Request $request the request object
     * @param int     $id      the center id
     *
     * @return array
     *
     * @throws NotFoundHttpException when center not exist
     */
    public function getCenterAction(Request $request, $id)
    {
        $center = $this->getCenterManager()->get($id);
        if (false === $center) {
            throw $this->createNotFoundException("Center does not exist.");
        }

        $view = new View($center);
        $group = $this->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        $view->getSerializationContext()->setGroups(array('Default', $group));

        return $view;
    }

    /**
     * Presents the form to use to create a new center.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Present a form to create a new center.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newCenterAction()
    {
        return $this->createForm(new CenterType());
    }

    /**
     * Creates a new center from the submitted data.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Create a new center.",
     *   input = "Meup\DemoBundle\Form\CenterType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "MeupDemoBundle:Center:newCenter.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|RouteRedirectView
     */
    public function postCentersAction(Request $request)
    {
        $center = new Center();
        $form = $this->createForm(new CenterType(), $center);

        $form->submit($request);
        if ($form->isValid()) {
            $this->getCenterManager()->set($center);

            return $this->routeRedirectView('get_center', array('id' => $center->id));
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Presents the form to use to update an existing center.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Present a form to update an existing center.",
     *   statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when the center is not found"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request the request object
     * @param int     $id      the center id
     *
     * @return FormTypeInterface
     *
     * @throws NotFoundHttpException when center not exist
     */
    public function editCentersAction(Request $request, $id)
    {
        $center = $this->getCenterManager()->get($id);
        if (false === $center) {
            throw $this->createNotFoundException("Center does not exist.");
        }

        $form = $this->createForm(new CenterType(), $center);

        return $form;
    }

    /**
     * Update existing center from the submitted data or create a new center at a specific location.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Update an existing center.",
     *   input = {"class" = "Meup\DemoBundle\Form\CenterType", "paramType" = "query"},
     *   statusCodes = {
     *     201 = "Returned when a new resource is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template="MeupDemoBundle:Center:editCenter.html.twig",
     *   templateVar="form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the center id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when center not exist
     */
    public function putCentersAction(Request $request, $id)
    {
        $center = $this->getCenterManager()->get($id);
        if (false === $center) {
            $center = new Center();
            $center->id = $id;
            $statusCode = Codes::HTTP_CREATED;
        } else {
            $statusCode = Codes::HTTP_NO_CONTENT;
        }

        $form = $this->createForm(new CenterType(), $center);

        $form->submit($request);
        if ($form->isValid()) {
            $this->getCenterManager()->set($center);

            return $this->routeRedirectView('get_center', array('id' => $center->id), $statusCode);
        }

        return $form;
    }

    /**
     * Removes a center.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Delete an existing center.",
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the center id
     *
     * @return RouteRedirectView
     */
    public function deleteCentersAction(Request $request, $id)
    {
        $this->getCenterManager()->remove($id);

        return $this->routeRedirectView('get_centers', array(), Codes::HTTP_NO_CONTENT);
    }

    /**
     * Removes a center.
     *
     * @ApiDoc(
     *   section = "Demo",
     *   resource = true,
     *   resourceDescription="Remove a center.",
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the center id
     *
     * @return RouteRedirectView
     */
    public function removeCentersAction(Request $request, $id)
    {
        return $this->deleteCentersAction($request, $id);
    }
}
