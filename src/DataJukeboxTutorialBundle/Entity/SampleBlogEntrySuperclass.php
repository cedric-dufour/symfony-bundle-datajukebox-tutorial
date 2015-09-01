<?php // -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
// DataJukeboxTutorialBundle\Entity\SampleBlogEntrySuperclass.php

/** Data Jukebox Bundle Tutorial
 *
 * @package    DataJukeboxTutorialBundle
 * @author     Cedric Dufour <http://cedric.dufour.name>
 * @version    %{VERSION}
 */

namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/** Blog entry superclass
 * @package    DataJukeboxTutorialBundle
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

  /** Content
   * @var string
   * @ORM\Column(name="Content_tx", type="text")
   */
  protected $Content;

  /** Tags
   * @var string
   * @ORM\Column(name="Tags_vc", type="string", length=1000)
   */
  protected $Tags;


  /*
   * METHODS
   ********************************************************************************/

  /*
   * GETTERS
   */

  public function getPK()
  {
    return $this->PK;
  }

  public function getTitle()
  {
    return $this->Title;
  }

  public function getDate()
  {
    return $this->Date;
  }

  public function getContent()
  {
    return $this->Content;
  }

  public function getTags()
  {
    return $this->Tags;
  }


  /*
   * METHODS: PrimaryKeyInterface
   ********************************************************************************/

  public function getPrimaryKey()
  {
    return array('PK' => $this->PK);
  }


  /*
   * METHODS: PHP
   ********************************************************************************/

  public function __toString()
  {
    return $this->Title;
  }

}
