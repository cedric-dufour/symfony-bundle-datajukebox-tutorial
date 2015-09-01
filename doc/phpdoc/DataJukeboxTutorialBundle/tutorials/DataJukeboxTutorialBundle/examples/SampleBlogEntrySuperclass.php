<?php
namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/** Blog entry superclass
 * @ORM\MappedSuperclass
 */
class SampleBlogEntrySuperclass
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

  /** Title
   * @var string
   * @ORM\Column(name="Title_vc", type="string", length=100)
   */
  protected $Title;

  /** Date
   * @var \DateTime
   * @ORM\Column(name="Date_d", type="date")
   */
  protected $Date;

  // ...

  /*
   * METHODS
   ********************************************************************************/

  // Getters/...


  /*
   * METHODS: PrimaryKeyInterface
   ********************************************************************************/

  public function getPrimaryKey()
  {
    return array('PK' => $this->PK);
  }

}
