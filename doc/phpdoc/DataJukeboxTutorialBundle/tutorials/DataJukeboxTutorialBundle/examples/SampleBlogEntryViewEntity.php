<?php
namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DataJukeboxBundle\Annotations\Properties;

/** Blog entry (view) entity (for reading purposes)
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

  // Getters/...

}
