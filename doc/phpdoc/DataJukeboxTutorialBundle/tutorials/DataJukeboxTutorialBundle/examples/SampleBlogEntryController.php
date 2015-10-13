<?php
namespace DataJukeboxTutorialBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use DataJukeboxTutorialBundle\Properties\SampleBlogEntryProperties;

class SampleBlogEntryController
  extends Controller
{

  public function getAuthorization()
  {
    $oSecurityAuthorizationChecker = $this->get('security.authorization_checker');
    if ($oSecurityAuthorizationChecker->isGranted('ROLE_BLOG_ADMIN')) return SampleBlogEntryProperties::AUTH_ADMIN;
    return SampleBlogEntryProperties::AUTH_PUBLIC;
  }

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

}
