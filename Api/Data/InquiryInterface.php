<?php

namespace ChintakExtensions\ProductInquiry\Api\Data;

interface InquiryInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const JEWELLEY_TYPE = 'jewellery_type';
    const JEWELLEY_ATTRIBUTE = 'jewellery_attribute';
    const IMAGE = 'image';
    const FNAME = 'fname';
    const LNAME = 'lname';
    const EMAIL = 'email';
    const MOBILE = 'mobile';
    const MESSAGE = 'message';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function getEntityId();

    public function setEntityId($entityId);


    public function getJewelleryType();

    public function setJewelleryType($jewellery_type);


    public function getJewelleryAttribute();

    public function setJewelleryAttribute($jewellery_attribute);


    public function getImage();

    public function setImage($image);


    public function getFname();

    public function setFname($fname);


    public function getLname();

    public function setLname($lname);


    public function getEmail();

    public function setEmail($email);


    public function getMobile();

    public function setMobile($mobile);


    public function getMessage();

    public function setMessage($message);

    
    public function getCreatedAt();

    public function setCreatedAt($createdAt);


    public function getUpdatedAt();

    public function setUpdatedAt($updatedAt);

}