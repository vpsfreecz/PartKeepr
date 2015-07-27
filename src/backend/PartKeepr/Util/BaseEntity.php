<?php
namespace PartKeepr\Util;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\SoftDeleteable;

/** @ORM\MappedSuperclass
 *  @SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
abstract class BaseEntity {
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	* @var integer
	*/
	private $id;
	
	/**
	 * Returns the ID of this object.
	 * @param none
	 * @return int The ID of this object
	 */
	public function getId () {
		return $this->id;
	}

	/**
	 * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
	 */
	private $deletedAt;
}