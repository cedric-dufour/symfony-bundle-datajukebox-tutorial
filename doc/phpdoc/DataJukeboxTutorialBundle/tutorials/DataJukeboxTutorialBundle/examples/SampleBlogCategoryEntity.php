<?php
namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DataJukeboxBundle\Annotations\Properties;

/** Blog category entity
 * @ORM\Entity
 * @ORM\Table(name="SampleBlogCategory")
 * @Properties(propertiesClass="DataJukeboxTutorialBundle\Properties\SampleBlogCategoryProperties")
 */
class SampleBlogCategoryEntity
  implements \DataJukeboxBundle\DataJukebox\PrimaryKeyInterface
{

  /*
   * PROPERTIES
   ********************************************************************************/

  /** Primary key
   * @var integer
   * @ORM\Column(name="pk", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue
   */
  protected $PK;

  /** Name
   * @var string
   * @ORM\Column(name="Name_vc", type="string", length=100)
   */
  protected $Name;

  // ...

  /*
   * METHODS
   ********************************************************************************/

  // Setters/Getters/...


  /*
   * METHODS: PrimaryKeyInterface
   ********************************************************************************/

  public function getPrimaryKey()
  {
    return array('PK' => $this->PK);
  }

}
