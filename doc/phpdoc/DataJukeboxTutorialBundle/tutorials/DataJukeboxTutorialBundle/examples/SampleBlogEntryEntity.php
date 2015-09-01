<?php
namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DataJukeboxBundle\Annotations\Properties;

/** Blog entry entity (for create/update purposes)
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

  // Setters/Getters/...

}
