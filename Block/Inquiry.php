<?php

namespace ChintakExtensions\ProductInquiry\Block;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\AttributeSetRepositoryInterface;
 
class Inquiry extends \Magento\Framework\View\Element\Template
{
     protected $_pageFactory;

     protected $_postLoader;

     protected $_productAttributeRepository;

     private $attributeSetRepository;

     private $searchCriteriaBuilder;
 
     public function __construct(
          \Magento\Framework\View\Element\Template\Context $context,
          \Magento\Framework\View\Result\PageFactory $pageFactory,
          \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
          AttributeSetRepositoryInterface $attributeSetRepository,
          SearchCriteriaBuilder $searchCriteriaBuilder,
          array $data = []
     ) {
          $this->_pageFactory = $pageFactory;
          $this->_productAttributeRepository = $productAttributeRepository;
          $this->attributeSetRepository = $attributeSetRepository;
          $this->searchCriteriaBuilder = $searchCriteriaBuilder;
          return parent::__construct($context, $data);
     }
 
     public function execute()
     {
          return $this->_pageFactory->create();
     }

     public function getAllType(){
        $typeOptions = $this->_productAttributeRepository->get('type')->getOptions();       
        $values = [];
        foreach ($typeOptions as $typeOption) { 
            if ($typeOption['value'] > 0) {
                $values[] = $typeOption;
            }
        }
        return $values;
    }

    public function listAttributeSets()
    {
        $attributeSetList = null;
        try {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $attributeSet = $this->attributeSetRepository->getList($searchCriteria);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        if ($attributeSet->getTotalCount()) {
            $attributeSetList = $attributeSet;            
        }

        return $attributeSetList;
    }
}
?>