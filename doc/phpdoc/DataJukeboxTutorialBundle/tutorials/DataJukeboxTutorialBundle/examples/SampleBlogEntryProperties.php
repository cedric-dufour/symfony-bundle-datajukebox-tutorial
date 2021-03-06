<?php
namespace DataJukeboxTutorialBundle\Properties;

class SampleBlogEntryProperties
  extends \DataJukeboxBundle\DataJukebox\Properties
  implements \DataJukeboxBundle\DataJukebox\FormatInterface
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
          'detail' => array('SampleBlogEntry_view'),
          'insert' => array('SampleBlogEntry_edit'),
          'delete' => array('SampleBlogEntry_delete'),
          'select_delete' => array('SampleBlogEntry_delete'),
        );
      case 'detail':
        return array(
          'list' => array('SampleBlogEntry_view'),
          'update' => array('SampleBlogEntry_edit'),
          'delete' => array('SampleBlogEntry_delete'),
        );
      }
    }

    // Default
    switch ($this->getAction()) {
    case 'list':
      return array(
        'detail' => array('SampleBlogEntry_view'),
      );
    case 'detail':
      return array(
        'list' => array('SampleBlogEntry_view'),
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
      $this->getMeta('label', 'SampleBlogEntry')
    );
  }

  // Return the (localized) fields tooltips
  public function getTooltips()
  {
    return array_merge(
      parent::getTooltips(),
      $this->getMeta('tooltip', 'SampleBlogEntry')
    );
  }

  // Return the fields allowed to query/display
  public function getFields()
  {
    switch ($this->getAction()) {
    case 'list':
      return array('Title', 'Date', 'Category', 'Tags');
    }
    return array('Title', 'Date', 'Category', 'Content', 'Tags');
  }

  // Return the fields to query/display by default
  public function getFieldsDefault()
  {
    switch ($this->getAction()) {
    case 'list':
      return array('Title', 'Date', 'Category');
    }
    return parent::getFieldsDefault();
  }

  // Return the fields that must be kept hidden
  public function getFieldsHidden()
  {
    return array_merge(
      array('PK', 'CategoryFK'),
      parent::getFieldsHidden()
    );
  }

  // Return the fields that must be displayed or data-filled
  public function getFieldsRequired()
  {
    switch ($this->getAction()) {
    case 'list':
    case 'detail':
      return array('CategoryFK', 'Title');
    case 'insert':
    case 'update':
      return array('Title', 'Date', 'Category', 'Content');
    }
    return parent::getFieldsRequired();
  }

  // Return the fields that may not be modified
  public function getFieldsReadonly()
  {
    switch ($this->getAction()) {
    case 'update':
      return array('Date');
    }
    return parent::getFieldsReadOnly();
  }

  // Return the default value for specific fields
  public function getFieldsDefaultValue()
  {
    return array(
      'Date' => new \DateTime(),
    );
  }

  // Return the links to associate with each field
  public function getFieldsLink()
  {
    switch ($this->getAction()) {
    case 'list':
      return array_merge(
        array(
          'Title' => array('path', 'SampleBlogEntry_view', array('_pk' => 'PK')),
        ),
        parent::getFieldsLink()
      );
    case 'detail':
      return array_merge(
        array(
          'Category' => array('path', 'SampleBlogCategory_view', array('_pk' => 'CategoryFK')),
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
      array('Date'),
      parent::getFieldsOrder()
    );
  }

  // Return the fields that may be used for filtering list views
  public function getFieldsFilter()
  {
    return array('Title', 'Date', 'Category', 'Tags');
  }

  // Return the fields that are used for searching data (in list views)
  public function getFieldsSearch()
  {
    return array('Title', 'Content');
  }

  // Return the additional links to create in views
  public function getFooterLinks()
  {
    switch ($this->getAction()) {
    case 'detail':
      return array_merge(
        array(
          '_view_list' => array('path+query', 'SampleBlogEntry_view', null, '⊛'),
        ),
        parent::getFooterLinks()
      );
    }
    return parent::getFooterLinks();
  }

  // Return the Twig template for each action
  public function getTemplate()
  {
    switch ($this->getAction()) {
    case 'list':
      return '@DataJukeboxTutorial/list.html.twig';
    case 'detail':
      return '@DataJukeboxTutorial/detail.html.twig';
    case 'insert':
    case 'update':
      return '@DataJukeboxTutorial/form.html.twig';
    }
    throw new \Exception(sprintf('Invalid action (%s)', $this->getAction()));
  }


  /*
   * METHODS: FormatInterface
   ********************************************************************************/

  // Custom formatting for specific fields
  public function formatFields(array &$aRow, $iIndex) {
    if (isset($aRow['Date'])) $aRow['Date_formatted'] = $aRow['Date']->format('r');
  }

}
