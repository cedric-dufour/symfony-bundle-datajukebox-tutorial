<?php // -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
// DataJukeboxTutorialBundle\Properties\SampleBlogCategoryProperties.php

/** Data Jukebox Bundle Tutorial
 *
 * @package    DataJukeboxTutorialBundle
 * @author     Cedric Dufour <http://cedric.dufour.name>
 * @version    %{VERSION}
 */

namespace DataJukeboxTutorialBundle\Properties;

/** Blog category properties
 * @package    DataJukeboxTutorialBundle
 */
class SampleBlogCategoryProperties
  extends \DataJukeboxBundle\DataJukebox\Properties
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Administration authorization level
   * @var integer
   */
  const AUTH_ADMIN = 9;


  /*
   * METHODS: Properties(Interface)
   ********************************************************************************/

  // Return whether the defined action is authorized
  // at the defined authorization level
  public function isAuthorized()
  {
    switch ($this->getAction()) {
    case 'list':
    case 'detail':
      return true;
    case 'insert':
    case 'update':
    case 'delete':
      return $this->getAuthorization() >= self::AUTH_ADMIN;
    }
    return parent::isAuthorized();
  }

  // Return the routes for the various CRUD operations
  public function getRoutes()
  {
    // As per authorization level
    if ($this->getAuthorization() >= self::AUTH_ADMIN) {
      switch ($this->getAction()) {
      case 'list':
        return array(
          'detail' => array('SampleBlogCategory_view'),
          'insert' => array('SampleBlogCategory_edit'),
          'delete' => array('SampleBlogCategory_delete'),
          'select_delete' => array('SampleBlogCategory_delete'),
        );
      case 'detail':
        return array(
          'list' => array('SampleBlogCategory_view'),
          'update' => array('SampleBlogCategory_edit'),
          'delete' => array('SampleBlogCategory_delete'),
        );
      }
    }

    // Default
    switch ($this->getAction()) {
    case 'list':
      return array(
        'detail' => array('SampleBlogCategory_view'),
      );
    case 'detail':
      return array(
        'list' => array('SampleBlogCategory_view'),
      );
    }

    // Fallback
    return parent::getRoutes();
  }

  // Return the (localized) fields labels
  public function getLabels()
  {
    return array_merge(
      parent::getLabels(),
      $this->getMeta('label', 'SampleBlogCategory')
    );
  }

  // Return the (localized) fields tooltips
  public function getTooltips()
  {
    return array_merge(
      parent::getTooltips(),
      $this->getMeta('tooltip', 'SampleBlogCategory')
    );
  }

  // Return the fields allowed to query/display
  public function getFields()
  {
    switch ($this->getAction()) {
    case 'list':
      return array('Name');
    }
    return array('Name', 'Description');
  }

  // Return the fields that must be kept hidden
  public function getFieldsHidden()
  {
    return array_merge(
      array('PK'),
      parent::getFieldsHidden()
    );
  }

  // Return the fields that must be displayed or data-filled
  public function getFieldsRequired()
  {
    switch ($this->getAction()) {
    case 'list':
    case 'detail':
    case 'insert':
    case 'update':
      return array('Name');
    }
    return parent::getFieldsRequired();
  }

  // Return the fields that may not be modified
  public function getFieldsReadonly()
  {
    switch ($this->getAction()) {
    case 'update':
      return array('Name');
    }
    return parent::getFieldsReadOnly();
  }

  // Return the links to associate with each field
  public function getFieldsLink()
  {
    switch ($this->getAction()) {
    case 'list':
      return array_merge(
        array(
          'Name' => array('path', 'SampleBlogCategory_view', array('_pk' => 'PK')),
        ),
        parent::getFieldsLink()
      );
    }
    return parent::getFieldsLink();
  }

  // Return the fields that may be used for sorting list views
  public function getFieldsOrder()
  {
    return array_merge(
      array('Name'),
      parent::getFieldsOrder()
    );
  }

  // Return the fields that are used for searching data (in list views)
  public function getFieldsSearch()
  {
    return array('Name');
  }

  // Return the additional links to create in views
  public function getFooterLinks()
  {
    switch ($this->getAction()) {
    case 'detail':
      return array_merge(
        array(
          '_view_list' => array('path+query', 'SampleBlogCategory_view', null, '⊛'),
        ),
        parent::getFooterLinks()
      );
    }
    return parent::getFooterLinks();
  }

  // Return the Twig template for each action
  public function getTemplate()
  {
    switch ($this->getFormat()) {
    case 'html':
      switch ($this->getAction()) {
      case 'list':
        return 'DataJukeboxTutorialBundle::list.html.twig';
      case 'detail':
        return 'DataJukeboxTutorialBundle::detail.html.twig';
      case 'insert':
      case 'update':
        return 'DataJukeboxTutorialBundle::form.html.twig';
      }
    }
    return parent::getTemplate();
  }

}
