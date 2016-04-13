<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */

namespace Emizentech\ShopByBrand\Controller\Adminhtml\Items;
use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Emizentech\ShopByBrand\Controller\Adminhtml\Items
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_objectManager->create('Emizentech\ShopByBrand\Model\Items');
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                try{
					$uploader = $this->_objectManager->create(
						'Magento\MediaStorage\Model\File\Uploader',
						['fileId' => 'logo']
					);
					$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
					/** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
					$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
					$uploader->setAllowRenameFiles(true);
					$uploader->setFilesDispersion(true);
					/** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
					$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
						->getDirectoryRead(DirectoryList::MEDIA);
					$result = $uploader->save($mediaDirectory->getAbsolutePath('brand'));
					if($result['error']==0)
					{
						$data['logo'] = 'brand' . $result['file'];
					}
				} catch (\Exception $e) {
					//unset($data['image']);
				}
	//             var_dump($data);die;
				if(isset($data['logo']['delete']) && $data['logo']['delete'] == '1')
					$data['logo'] = '';
			if(isset($data['logo']['value']) && strlen($data['logo']['value']) > 1)
                        	$data['logo'] = $data['logo']['value'];

                
                
                
                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('emizentech_shopbybrand/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('emizentech_shopbybrand/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('emizentech_shopbybrand/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('emizentech_shopbybrand/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('emizentech_shopbybrand/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('emizentech_shopbybrand/*/');
    }
}
