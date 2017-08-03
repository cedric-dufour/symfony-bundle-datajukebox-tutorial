<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
// DataJukeboxTutorialBundle\Controller\SampleBlogEntryController.php

/** Data Jukebox Bundle Tutorial
 *
 * @package    DataJukeboxTutorialBundle
 * @author     Cedric Dufour <http://cedric.dufour.name>
 * @version    %{VERSION}
 */

namespace DataJukeboxTutorialBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use DataJukeboxBundle\Controller\Controller;

use DataJukeboxTutorialBundle\Properties\SampleBlogEntryProperties;

/** Blog entry controller
 * @package    DataJukeboxTutorialBundle
 */
class SampleBlogEntryController
  extends Controller
{

  /** Get the authorization level (based on Symfony security context)
   * @return integer
   */
  public function getAuthorization()
  {
    $oSecurityAuthorizationChecker = $this->get('security.authorization_checker');
    if ($oSecurityAuthorizationChecker->isGranted('ROLE_BLOG_ADMIN')) return SampleBlogEntryProperties::AUTH_ADMIN;
    return SampleBlogEntryProperties::AUTH_PUBLIC;
  }

  /** Create the 'list' or 'detail' view
   */
  public function viewAction($_pk, Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogEntryViewEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction(is_null($_pk) ? 'list' : 'detail')
                                ->setTranslator($this->get('translator'));
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Browsing
    $oBrowser = $oProperties->getBrowser($oRequest);
    if (is_null($_pk) and !$oBrowser->getFieldsOrder()) $oBrowser->setFieldsOrder('Date_D');

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
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogEntryEntity')
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
      return $this->redirectToRoute('SampleBlogEntry_view', $oFormType->getPrimaryKeySlug($oData));
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
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogEntryEntity')
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
    return $this->redirectToRoute('SampleBlogEntry_view', $oRequest->query->all());
  }

  /** Create the 'export' view
   */
  public function exportAction($_format, $_pk, Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogEntryViewEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction('export')
                                ->setFormat($_format);
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Browsing
    $oBrowser = $oProperties->getBrowser($oRequest);
    $oBrowser->setOffset(0)->setLimit(PHP_INT_MAX);
    if (is_null($_pk) and !$oBrowser->getFieldsOrder()) $oBrowser->setFieldsOrder('Date_D');

    // Data query
    $oRepository = $oDataJukebox->getRepository($oProperties);
    $oResult = $oRepository->getDataResult($_pk, $oBrowser);

    // Response
    $asMimetype = array('xml' => 'text/xml', 'json' => 'application/json', 'csv' => 'text/csv');
    $sResponse = $this->renderView(
      $oProperties->getTemplate(),
      array('data' => $oResult->getTemplateData())
    );
    $oResponse = new \Symfony\Component\HttpFoundation\Response($sResponse);
    $oResponse->headers->set('Cache-Control', 'private');
    $oResponse->headers->set('Content-Type', $asMimetype[$_format]);
    $oResponse->headers->set('Content-Length', strlen($sResponse));
    $oResponse->headers->set('Content-Disposition', sprintf('attachment; filename="%s.%s";', $oProperties->getName(), $_format));
    return $oResponse;
  }

}
