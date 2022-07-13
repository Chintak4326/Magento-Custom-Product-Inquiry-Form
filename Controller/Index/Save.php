<?php

namespace ChintakExtensions\ProductInquiry\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Exception\LocalizedException;
use ChintakExtensions\ProductInquiry\Model\Mail\TransportBuilder;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_inquiry;
    protected $scopeConfig;
    protected $storeManager;
    public $messageManager;
    protected $request;
    protected $transportBuilder;
    protected $_mediaDirectory;
    protected $_fileUploaderFactory;
    protected $filesystem;
    protected $adapterFactory;


    public function __construct(
      Context $context,
      Request $request,
      \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
      \Magento\Store\Model\StoreManagerInterface $storeManager,
      \Magento\Framework\Message\ManagerInterface $messageManager,
      \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
      \ChintakExtensions\ProductInquiry\Model\InquiryFactory $inquiry,
      TransportBuilder $transportBuilder,
      \Magento\Framework\Filesystem $filesystem,
      \Magento\Framework\Filesystem\Io\File $file,
      \Magento\Framework\Image\AdapterFactory $adapterFactory,
      \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
  ) {
        $this->_inquiry =  $inquiry;
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->messageManager = $messageManager;
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        parent::__construct($context); 
    }
    public function execute()
    {
        // Get Store URL with Store Code
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeurl = $storeManager->getStore()->getBaseUrl();

        $inquirypageurl = $storeurl . 'inquiry';

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $sendEmailTo =  $this->scopeConfig->getValue('inquiry/email/inquiry_recipient_email', $storeScope);
        $ccEmailTo =  $this->scopeConfig->getValue('inquiry/email/copy_to', $storeScope);
        $emailSender =  $this->scopeConfig->getValue('inquiry/email/inquiry_sender_email_identity', $storeScope);

        if($emailSender=='sales')
        {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_sales/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_sales/email', $storeScope);

        }
        elseif ($emailSender=='general') {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_general/email', $storeScope);
        }
        elseif ($emailSender=='support') {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_support/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_support/email', $storeScope);
        }

        elseif ($emailSender=='custom1') {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_custom1/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_custom1/email', $storeScope);
        }
        else
        {
            $adminSenderName= $this->scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
            $adminSenderEmail= $this->scopeConfig->getValue('trans_email/ident_general/email', $storeScope); 
        }

        $post = $this->getRequest()->getPostValue();
        $this->inlineTranslation->suspend();

        $data = $this->getRequest()->getParams();

        // For Mobile with Country Code
        $countrycode = $data['country_code'];
        $mobile = $data['mobile'];
        $mobilewithcode = '+' . $countrycode." ".$mobile;

        // Load Model
        $model = $this->_inquiry->create();

        if(empty($data['jewellery_type']) || empty($data['jewellery_attribute']) || empty($data['fname']) || empty($data['lname']) || empty($data['email'])  || empty($data['mobile']) || empty($data['message']))
        {   
            $this->messageManager->addErrorMessage(__('Someone feild was missing'));
            $this->_redirect($inquirypageurl);
            return;
        }
        else
        {
            // Image upload
            $files = $this->request->getFiles()->toArray(); // same as $_FILES

            if (isset($files['image']) && !empty($files['image']["name"]))
            {
                try{
                    $target = $this->_mediaDirectory->getAbsolutePath('chintakextensions/inquiry');
                    $targetOne = 'chintakextensions/inquiry/';
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->validateFile();
                    $result = $uploader->save($target);
                    $imagePath = $targetOne.$result['file'];
                    $filePath = $result['path'].'/'.$result['file'];
                    $data['image'] = $imagePath;
                    $modelsave = $model->setImage($data['image'])
                        ->setJewelleryType($data['jewellery_type'])
                        ->setJewelleryAttribute($data['jewellery_attribute'])
                        ->setFName($data['fname'])
                        ->setLname($data['lname'])
                        ->setEmail($data['email'])
                        ->setMobile($mobilewithcode)
                        ->setMessage($data['message'])
                        ->save();

                    // Get type and name for invoice attachment
                    $fileType = $result['type'];
                    $fileName = $result['name'];
                } catch (ValidationException $e) {
                    // throw new LocalizedException(__('Upload valid profile image. Only JPG, JPEG, PNG and PDF are allowed'));
                    $this->messageManager->addErrorMessage(__('Upload valid image. Only JPG, JPEG, PNG and PDF are allowed'));
                    $this->_redirect($inquirypageurl);
                    return;

                } catch (\Exception $e) {
                    //if an except is thrown, no image has been uploaded
                    throw new LocalizedException(__($e->getMessage()));
                }
            }
            
            if($modelsave->save())
            {
                $recipientMail = $this->getRequest()->getPostValue('email');
                $postObject = new \Magento\Framework\DataObject();
                $postObject->setData($post);
                $error = false;
                $sender = array('name' => $adminSenderName,'email' => $adminSenderEmail);
                $data=$this->getRequest()->getParams(); 
                $emailTemplateVariables = array(
                   'fname' => $data['fname']
               );

                $transport = $this->transportBuilder
                    ->setTemplateIdentifier('inquiry_email_template_front') // this code we have mentioned in the email_templates.xml
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars($emailTemplateVariables)
                    ->setFrom($sender)
                    ->addTo($recipientMail)
                    ->getTransport();

                    $transport->sendMessage();
                    $this->inlineTranslation->resume();

                // Admin Mail
                    $emailTemplateVariables = array(
                        'jewellery_type' => $data['jewellery_type'],
                        'jewellery_attribute' => $data['jewellery_attribute'],
                        'fname' => $data['fname'],
                        'lname' => $data['lname'],
                        'email'=>$data['email'],
                        'mobile'=>$mobilewithcode,
                        'message'=>$data['message']
                    );
                    $to = $sendEmailTo;
                    $sender = array('name' => $adminSenderName,'email' => $adminSenderEmail);
                    $transport = $this->transportBuilder->setTemplateIdentifier('inquiry_email_template_admin')
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars($emailTemplateVariables)
                    ->setFrom($sender)
                    ->addTo($to);
                    if($ccEmailTo != "")
                    {
                        $transport->addCc($ccEmailTo);
                    }
                    if(isset($files['image']) && $files['image'] != "")
                    {
                        $transport->addAttachment($this->file->read($filePath), $fileName, $fileType);
                    }
                    $transport->setReplyTo($adminSenderEmail)
                    ->getTransport()
                    ->sendMessage();
                    $this->inlineTranslation->resume();
                    $this->_redirect($inquirypageurl);
                    return;
                    $this->messageManager->addSuccess(__('Your form of Product Inquiry has been submitted successfully.'));
                } 
                else
                {
                    $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                    $this->_redirect($inquirypageurl);
                    return;
                }
            }
        }
    }
    ?>



