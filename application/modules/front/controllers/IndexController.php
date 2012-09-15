<?php
/**
 * PHP Class IndexController.php
 * PHP Version 5
 *
 * @category    : default
 * @package     : default
 * @license     : Not for free use
 * @date        : 21/07/12
 * @author      : aroy <contact@aroy.fr>
 */

class IndexController
    extends Zend_Controller_Action
{
    public function init()
    {
    }

    public function indexAction()
    {
        $request = new Llv_Services_Cms_Request_Page();
        $request->id = Llv_Constant_Cms_Page::HOME_ID;
        $request->idLangue = Llv_Context_Application::getInstance()->getCurrentLocale()->getIdLangue();
        $page = Llv_Context_Cms::getInstance()->pageGetRow($request);

        $filter = new Llv_Services_Cms_Filter_Carrousel();
        $filter->online = true;
        $carrousel = Llv_Context_Cms::getInstance()->carrouselGetList($filter);

        $this->view->assign('page', $page);
        $this->view->assign('carrousel', $carrousel);
    }
}