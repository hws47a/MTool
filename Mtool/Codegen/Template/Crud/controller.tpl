<?php
/**
 * @category   Local
 * @package    #{company_name}_#{module_name}
 * @copyright  Copyright (C) #{year} #{copyright_company}
 * @author     #{author}
 */
class #{class_name} extends #{controller_abstract}
{
    protected $_model = '#{model_ns}/#{model_path}';

    /**
     * Init layout
     *
     * @return #{class_name}
     */
    protected function _initAction()
    {
        $this->loadLayout()
            //->_setActiveMenu('custom_modules/scheduled_content')
            ->_addBreadcrumb($this->__('#{header_text}'), $this->__('#{header_text}'));
        $this->_title($this->__('#{header_text}'));

        return $this;
    }

    /**
     * Show grid
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_initAction()
        ->renderLayout();
    }

    /**
     * Edit item action
     *
     * @return void
     */
    public function editAction()
    {
        $modelId = intval($this->getRequest()->getParam('id', 0));
        $error = false;
        if ($modelId) {
            $model = Mage::getModel($this->_model)->load($modelId);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($modelId);
                }
                Mage::register('current_#{model_ns}_#{model_path}', $model);
            } else {
                $this->_getSession()->addError($this->__('Item doesn\'t exist'));
                $error = true;
            }
        }

        if ($error) {
            $this->_redirectError($this->_getRefererUrl());
        } else {
            $this->_initAction();
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        }
    }

    /**
     * New item action
     *
     * @return void
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save menu item action
     *
     * @return void
     */
    public function saveAction()
    {
        $error = false;

        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel($this->_model);

            $modelId = intval($this->getRequest()->getParam('id', 0));
            if ($modelId) {
                $model->load($modelId);
            }

            $this->_getSession()->setFormData($data);

            try {
                $model->setData($data);

                if ($modelId) {
                    $model->setId($modelId);
                }

                $model->save();

                if (!$model->getId()) {
                    Mage::throwException($this->__('Error saving item'));
                }

                $this->_getSession()->addSuccess($this->__('Item was successfully saved.'));
                $this->_getSession()->setFormData(false);

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $error = true;
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Error while saving item'));
                Mage::logException($e);
                $error = true;
            }
        } else {
            $this->_getSession()->addError($this->__('No data found to save'));
        }

        if (!$error && isset($model) && $model->getId()) {
            // The following line decides if it is a "save" or "save and continue"
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
            } else {
                $this->_redirect('*/*/');
            }
        } else {
            $this->_redirectReferer();
        }
    }

    /**
     * Delete item action
     *
     * @return mixed
     */
    public function deleteAction()
    {
        if ($modelId = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel($this->_model);
                $model->setId($modelId);
                $model->delete();
                $this->_getSession()->addSuccess($this->__('Item has been deleted.'));
                $this->_redirect('*/*/');

                return;
            }
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        $this->_getSession()->addError($this->__('Unable to find the item to delete.'));
        $this->_redirect('*/*/');
    }

    /**
     * Load grid for ajax action
     *
     * @return void
     */
    public function gridAction()
    {
        $this->loadLayout()
            ->renderLayout();
    }

    /**
     * Mass delete items action
     *
     * @return void
     */
    public function massDeleteAction()
    {
        $modelIds = $this->getRequest()->getParam('item');
        if (!is_array($modelIds)) {
            $this->_getSession()->addError($this->__('Please select item(s).'));
        } else {
            try {
                foreach ($modelIds as $modelId) {
                    Mage::getSingleton($this->_model)
                        ->load($modelId)
                        ->delete();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were deleted.', count($modelIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/');
    }
}
