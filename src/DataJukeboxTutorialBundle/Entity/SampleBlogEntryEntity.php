<?php // -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
// DataJukeboxTutorialBundle\Entity\SampleBlogEntryEntity.php

/** Data Jukebox Bundle Tutorial
 *
 * @package    DataJukeboxTutorialBundle
 * @author     Cedric Dufour <http://cedric.dufour.name>
 * @version    %{VERSION}
 */

namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DataJukeboxBundle\Annotations\Properties;

/** Blog entry entity (for create/update purposes)
 * @package    DataJukeboxTutorialBundle
 * @ORM\Entity
 * @ORM\Table(name="SampleBlogEntry")
 * @Properties(propertiesClass="DataJukeboxTutorialBundle\Properties\SampleBlogEntryProperties")
 */
class SampleBlogEntryEntity
  extends SampleBlogEntrySuperclass
{

  /*
   * PROPERTIES
   ********************************************************************************/

  /** Category (entity)
   * @var SampleBlogCategoryEntity
   * @ORM\ManyToOne(targetEntity="SampleBlogCategoryEntity")
   * @ORM\JoinColumn(name="Category_fk", referencedColumnName="pk")
   */
  protected $Category;


  /*
   * METHODS
   ********************************************************************************/

  /*
   * SETTERS
   */

  public function setTitle($Title)
  {
    $this->Title = $Title;
    return $this;
  }

  public function setDate($Date)
  {
    $this->Date = $Date;
    return $this;
  }

  public function setCategory($Category)
  {
    $this->Category = $Category;
    return $this;
  }

  public function setContent($Content)
  {
    $this->Content = $Content;
    return $this;
  }

  public function setTags($Tags)
  {
    $this->Tags = $Tags;
    return $this;
  }

  /*
   * GETTERS
   */

  public function getCategory()
  {
    return $this->Category;
  }

}
