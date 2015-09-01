<?php // -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
// DataJukeboxTutorialBundle\Entity\SampleBlogCategoryEntity.php

/** Data Jukebox Bundle Tutorial
 *
 * @package    DataJukeboxTutorialBundle
 * @author     Cedric Dufour <http://cedric.dufour.name>
 * @version    %{VERSION}
 */

namespace DataJukeboxTutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DataJukeboxBundle\Annotations\Properties;

/** Blog category entity
 * @package    DataJukeboxTutorialBundle
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

  /** Description
   * @var string
   * @ORM\Column(name="Description_tx", type="text")
   */
  protected $Description;


  /*
   * METHODS
   ********************************************************************************/

  /*
   * SETTERS
   */

  public function setName($Name)
  {
    $this->Name = $Name;
    return $this;
  }

  public function setDescription($Description)
  {
    $this->Description = $Description;
    return $this;
  }

  /*
   * GETTERS
   */

  public function getPK()
  {
    return $this->PK;
  }

  public function getName()
  {
    return $this->Name;
  }

  public function getDescription()
  {
    return $this->Description;
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
    return $this->Name;
  }

}
