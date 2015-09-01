<?php // -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
// DataJukeboxTutorialBundle\Entity\SampleBlogEntryViewEntity.php

/** Data Jukebox Bundle Tutorial
 *
 * @package    DataJukeboxTutorialBundle
 * @author     Cedric Dufour <http://cedric.dufour.name>
 * @version    %{VERSION}
 */

namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DataJukeboxBundle\Annotations\Properties;

/** Blog entry (view) entity (for reading purposes)
 * @package    DataJukeboxTutorialBundle
 * @ORM\Entity
 * @ORM\Table(name="SampleBlogEntry_view")
 * @Properties(propertiesClass="DataJukeboxTutorialBundle\Properties\SampleBlogEntryProperties")
 */
class SampleBlogEntryViewEntity
  extends SampleBlogEntrySuperclass
{

  /*
   * PROPERTIES
   ********************************************************************************/

  /** Category (foreign key)
   * @var integer
   * @ORM\Column(name="Category_fk", type="integer")
   */
  protected $CategoryFK;

  /** Category (name)
   * @var string
   * @ORM\Column(name="Category_vc", type="string", length=100)
   */
  protected $Category;


  /*
   * METHODS
   ********************************************************************************/

  /*
   * GETTERS
   */

  public function getCategoryFK()
  {
    return $this->CategoryFK;
  }

  public function getCategory()
  {
    return $this->Category;
  }

}
