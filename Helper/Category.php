<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Subcategory\Helper;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterfaceFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Category extends AbstractHelper
{
    /**
     * @param \Magento\Framework\Registry $registry
     */
    protected $_registry;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Catalog\Api\Data\CategoryInterfaceFactory
     */
    protected $categoryInterfaceFactory;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepositoryInterface;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * Category constructor.
     * @param Context $context
     * @param Registry $registry
     * @param LoggerInterface $logger
     * @param CategoryInterfaceFactory $categoryInterfaceFactory
     * @param CategoryRepositoryInterface $categoryRepositoryInterface
     * @param CollectionFactory $categoryCollectionFactory
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        LoggerInterface $logger,
        CategoryInterfaceFactory $categoryInterfaceFactory,
        CategoryRepositoryInterface $categoryRepositoryInterface,
        CollectionFactory $categoryCollectionFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->_registry = $registry;
        $this->logger = $logger;
        $this->categoryInterfaceFactory = $categoryInterfaceFactory;
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    /**
     * Get current cateogry from registry
     * @return mixed|null
     */
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    /**
     * Children categories
     * @return array
     */
    public function getChildrenCategories()
    {
        $category = $this->getCurrentCategory();
        if ($category && $category->getChildrenCategories()) {
            return $category->getChildrenCategories();
        }
        return [];
    }

    /**
     * @param $categoryId
     * @return false|\Magento\Catalog\Api\Data\CategoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategoryById($categoryId)
    {
        try {
            return $this->categoryRepositoryInterface->get($categoryId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
