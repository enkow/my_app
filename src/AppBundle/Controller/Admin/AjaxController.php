<?php
/**
 * Ajax controller.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Year;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AjaxController.
 *
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin/ajax")
 */
class AjaxController extends Controller
{
  /**
   * Toggle action.
   *
   * @param Year $year Year
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse HTTP JsonResponse
   *
   * @Route(
   *     "/toggle/year/{id}",
   *     name="admin_ajax_toggle_year",
   * )
   * @Method("GET")
   */
    public function toggleAction(Year $year)
    {
        $year->setActive(!$year->getActive());
        $this->get('app.repository.year')->save($year);
        $response = array('status' => 'success', 'value' => $year->getActive());

        return new JsonResponse($response);
    }
}
