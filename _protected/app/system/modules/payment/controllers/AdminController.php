<?php
/**
 * @title          Admin Controller
 *
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Payment / Controller
 */

namespace PH7;

use PH7\Framework\Cache\Cache;
use PH7\Framework\Mvc\Model\DbConfig;
use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Url\Header;

class AdminController extends MainController
{
    /** @var int */
    private $iDefMembershipGroup;

    public function __construct()
    {
        parent::__construct();
        $this->iDefMembershipGroup = (int)DbConfig::getSetting('defaultMembershipGroupId');
    }

    public function index()
    {
        $this->sTitle = t('Administration of Payment System');
        $this->view->page_title = $this->sTitle;
        $this->view->h2_title = $this->sTitle;
        $this->output();
    }

    public function config()
    {
        $this->sTitle = t('Config Payment Gateway');
        $this->view->page_title = $this->sTitle;
        $this->view->h2_title = $this->sTitle;
        $this->output();
    }

    public function membershipList()
    {
        $oMembership = $this->oPayModel->getMemberships();

        if (empty($oMembership)) {
            $this->displayPageNotFound(t('No membership found!'));
        } else {
            $this->sTitle = t('Memberships List');
            $this->view->page_title = $this->sTitle;
            $this->view->h2_title = $this->sTitle;
            $this->view->memberships = $oMembership;
            $this->view->default_group = $this->iDefMembershipGroup;
            $this->output();
        }
    }

    public function addMembership()
    {
        $this->sTitle = t('Add Membership');
        $this->view->page_title = $this->sTitle;
        $this->view->h2_title = $this->sTitle;
        $this->output();
    }

    public function editMembership()
    {
        $this->sTitle = t('Update Membership');
        $this->view->page_title = $this->sTitle;
        $this->view->h2_title = $this->sTitle;
        $this->output();
    }

    public function deleteMembership()
    {
        $iMembershipId = $this->httpRequest->post('id', 'int');

        if ($iMembershipId === $this->iDefMembershipGroup) {
            echo t('You cannot delete the default membership group.');
            exit;
        }

        $this->oPayModel->deleteMembership($iMembershipId);
        /* Clean UserCoreModel Cache */
        (new Cache)->start(UserCoreModel::CACHE_GROUP, null, null)->clear();

        Header::redirect(
            Uri::get('payment', 'admin', 'membershiplist'),
            t('The Membership has been removed!')
        );
    }
}
