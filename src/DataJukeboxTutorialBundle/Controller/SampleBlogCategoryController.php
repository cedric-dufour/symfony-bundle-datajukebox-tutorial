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
    $oSecurityContext = $this->get('security.context');
    if ($oSecurityContext->isGranted('ROLE_BLOG_ADMIN')) return SampleBlogCategoryProperties::AUTH_ADMIN;
    return SampleBlogCategoryProperties::AUTH_PUBLIC;
  }

  /** Create the 'list' view
   */
  public function listAction(Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogCategoryEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction('list')
                                ->setTranslator($this->get('translator'));
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Browsing
    $oBrowser = $oProperties->getBrowser($oRequest);
    if (!$oBrowser->getFieldsOrder()) $oBrowser->setFieldsOrder('Name_A');

    // Data query
    $oRepository = $oDataJukebox->getRepository($oProperties);
    $oResult = $oRepository->getDataList($oBrowser);

    // Response
    return $this->render(
      $oProperties->getTemplate(),
      array('data' => $oResult->getTemplateData())
    );
  }

  /** Create the 'detail' view
   */
  public function detailAction($_pk, Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogCategoryEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction('detail')
                                ->setTranslator($this->get('translator'));
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Browsing
    $oBrowser = $oProperties->getBrowser($oRequest);

    // Data query
    $oRepository = $oDataJukebox->getRepository($oProperties);
    $oResult = $oRepository->getDataDetail($_pk, $oBrowser);

    // Response
    return $this->render(
      $oProperties->getTemplate(),
      array('data' => $oResult->getTemplateData())
    );
  }

  /** Create the 'insert' view
   */
  public function insertAction(Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogCategoryEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction('insert')
                                ->setTranslator($this->get('translator'));
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Form resources
    $oFormType = $oDataJukebox->getFormType($oProperties);
    $oForm = $this->createForm($oFormType);

    // Form handling
    $oForm->handleRequest($oRequest);
    if ($oForm->isValid()) {
      $oData = $oForm->getData();
      $oEntityManager = $oProperties->getEntityManager();
      $oEntityManager->persist($oData);
      $oEntityManager->flush();
      return $this->redirect(
        $this->generateUrl(
          'SampleBlogCategory_detail',
          $oFormType->getPrimaryKeySlug($oData)
        )
      );
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

  /** Create the 'update' view
   */
  public function updateAction($_pk, Request $oRequest)
  {
    // Properties
    $oDataJukebox = $this->get('DataJukebox');
    $iAuthorization = $this->getAuthorization();
    $oProperties = $oDataJukebox->getProperties('DataJukeboxTutorialBundle:SampleBlogCategoryEntity')
                                ->setAuthorization($iAuthorization)
                                ->setAction('update')
                                ->setTranslator($this->get('translator'));
    if (!$oProperties->isAuthorized()) throw new AccessDeniedException();

    // Data query
    $oRepository = $oDataJukebox->getRepository($oProperties);
    $oData = $oRepository->getDataObject($_pk);

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
      return $this->redirect(
        $this->generateUrl(
          'SampleBlogCategory_detail',
          $oFormType->getPrimaryKeySlug($oData)
        )
      );
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
        $oData = $oRepository->getDataObject($sPK);
        $oEntityManager->remove($oData);
      }
      $oEntityManager->flush();
    }

    // Response
    return $this->redirect(
      $this->generateUrl(
        'SampleBlogCategory_list',
        $oRequest->query->all()
      )
    );
  }

}
