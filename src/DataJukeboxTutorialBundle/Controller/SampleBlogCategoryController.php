<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
// DataJukeboxTutorialBundle\Controller\SampleBlogCategoryController.php

/** Data Jukebox Bundle Tutorial
 *
 * @package    DataJukeboxTutorialBundle
 * @author     Cedric Dufour <http://cedric.dufour.name>
 * @version    %{VERSION}
 */

namespace DataJukeboxTutorialBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use DataJukeboxTutorialBundle\Properties\SampleBlogCategoryProperties;

/** Blog category controller
 * @package    DataJukeboxTutorialBundle
 */
class SampleBlogCategoryController
  extends Controller
{

  /** Get the authorization level (based on Symfony security context)
   * @return integer
   */
  public function getAuthorization()
  {
    $oSecurityAuthorizationChecker = $this->get('security.authorization_checker');
    if ($oSecurityAuthorizationChecker->isGranted('ROLE_BLOG_ADMIN')) return SampleBlogCategoryProperties::AUTH_ADMIN;
    return SampleBlogCategoryProperties::AUTH_PUBLIC;
  }

  /** Create the 'list' or 'detail' view
   */
  public function viewAction($_pk, Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogCategoryEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction(is_null($_pk) ? 'list' : 'detail')
                                ->setTranslator($this->get('translator'));
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Browsing
    $oBrowser = $oProperties->getBrowser($oRequest);
    if (is_null($_pk) and !$oBrowser->getFieldsOrder()) $oBrowser->setFieldsOrder('Name_A');

    // Data query
    $oRepository = $oDataJukebox->getRepository($oProperties);
    $oResult = $oRepository->getDataResult($_pk, $oBrowser);

    // Response
    return $this->render(
      $oProperties->getTemplate(),
      array('data' => $oResult->getTemplateData())
    );
  }

  /** Create the 'insert' or 'update' view
   */
  public function editAction($_pk, Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogCategoryEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction(is_null($_pk) ? 'insert' : 'update')
                                ->setTranslator($this->get('translator'));
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Data query
    $oRepository = $oDataJukebox->getRepository($oProperties);
    $oData = is_null($_pk) ? null : $oRepository->getDataEntity($_pk);

    // Form resources
    $oFormType = $oDataJukebox->getFormType($oProperties);
    $oForm = $this->createForm($oFormType, $oData);

    // Form handling
    $oForm->handleRequest($oRequest);
    if ($oForm->isValid()) {
      $oData = $oForm->getData();
      $oEntityManager = $oProperties->getEntityManager();
      $oEntityManager->persist($oData);
      $oEntityManager->flush();
      return $this->redirectToRoute('SampleBlogCategory_view', $oFormType->getPrimaryKeySlug($oData));
    }

    // Response
    return $this->render(
      $oProperties->getTemplate(),
      array(
        'form' => $oForm->createView(),
        'data' => array('properties' => $oProperties->getTemplateData()),
      )
    );
  }

  /** Handle 'delete' requests
   */
  public function deleteAction($_pk, Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogCategoryEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction('delete');
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Data primary keys (potentially passed by HTTP POST)
    if (is_null($_pk)) {
      $asPK = \DataJukeboxBundle\DataJukebox\Browser::getPrimaryKeys($oRequest);
    } else {
      $asPK = array($_pk);
    }

    // Data query
    if (count($asPK)) {
      $oRepository = $oDataJukebox->getRepository($oProperties);
      $oEntityManager = $oProperties->getEntityManager();
      foreach ($asPK as $sPK) {
        $oData = $oRepository->getDataEntity($sPK);
        $oEntityManager->remove($oData);
      }
      $oEntityManager->flush();
    }

    // Response
    return $this->redirectToRoute('SampleBlogCategory_view', $oRequest->query->all());
  }

}
