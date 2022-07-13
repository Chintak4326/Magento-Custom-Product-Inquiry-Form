<?php

namespace ChintakExtensions\ProductInquiry\Model;

use ChintakExtensions\ProductInquiry\Api\Data\InquiryInterface;

class Inquiry extends \Magento\Framework\Model\AbstractModel implements InquiryInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'chintakextensions_inquiry';

    /**
     * @var string
     */
    protected $_cacheTag = 'chintakextensions_inquiry';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'chintakextensions_inquiry';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('ChintakExtensions\ProductInquiry\Model\ResourceModel\Inquiry');
    }

    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }


    public function getJewelleryType()
    {
        return $this->getData(self::JEWELLEY_TYPE);
    }

    public function setJewelleryType($jewellery_type)
    {
        return $this->setData(self::JEWELLEY_TYPE, $jewellery_type);
    }


    public function getJewelleryAttribute()
    {
        return $this->getData(self::JEWELLEY_ATTRIBUTE);
    }

    public function setJewelleryAttribute($jewellery_attribute)
    {
        return $this->setData(self::JEWELLEY_ATTRIBUTE, $jewellery_attribute);
    }


    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

 
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }


    public function getFname()
    {
        return $this->getData(self::FNAME);
    }

 
    public function setFname($fname)
    {
        return $this->setData(self::FNAME, $fname);
    }


    public function getLname()
    {
        return $this->getData(self::LNAME);
    }

 
    public function setLname($lname)
    {
        return $this->setData(self::LNAME, $lname);
    }


    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

 
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

   

    public function getMobile()
    {
        return $this->getData(self::MOBILE);
    }

 
    public function setMobile($mobile)
    {
        return $this->setData(self::MOBILE, $mobile);
    }

   
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

 
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }
    

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

   
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

   
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

}