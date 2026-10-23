<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function dashboardOverview1()
    {
        return view('pages/dashboard-overview-1', [
            'layout' => 'side-menu',
        ]);
    }

    public function dashboardOverview2()
    {
        return view('pages/dashboard-overview-2');
    }

    public function dashboardOverview3()
    {
        return view('pages/dashboard-overview-3');
    }

    public function inbox()
    {
        return view('pages/inbox');
    }

    public function fileManager()
    {
        return view('pages/file-manager');
    }

    public function pointOfSale()
    {
        return view('pages/point-of-sale');
    }

    public function chat()
    {
        return view('pages/chat');
    }

    public function post()
    {
        return view('pages/post');
    }

    public function calendar()
    {
        return view('pages/calendar');
    }

    public function crudDataList()
    {
        return view('pages/crud-data-list');
    }

    public function crudForm()
    {
        return view('pages/crud-form');
    }

    public function usersLayout1()
    {
        return view('pages/users-layout-1');
    }

    public function usersLayout2()
    {
        return view('pages/users-layout-2');
    }

    public function usersLayout3()
    {
        return view('pages/users-layout-3');
    }

    public function profileOverview1()
    {
        return view('pages/profile-overview-1');
    }

    public function profileOverview2()
    {
        return view('pages/profile-overview-2');
    }

    public function profileOverview3()
    {
        return view('pages/profile-overview-3');
    }

    public function wizardLayout1()
    {
        return view('pages/wizard-layout-1');
    }

    public function wizardLayout2()
    {
        return view('pages/wizard-layout-2');
    }

    public function wizardLayout3()
    {
        return view('pages/wizard-layout-3');
    }

    public function blogLayout1()
    {
        return view('pages/blog-layout-1');
    }

    public function blogLayout2()
    {
        return view('pages/blog-layout-2');
    }

    public function blogLayout3()
    {
        return view('pages/blog-layout-3');
    }

    public function pricingLayout1()
    {
        return view('pages/pricing-layout-1');
    }

    public function pricingLayout2()
    {
        return view('pages/pricing-layout-2');
    }

    public function invoiceLayout1()
    {
        return view('pages/invoice-layout-1');
    }

    public function invoiceLayout2()
    {
        return view('pages/invoice-layout-2');
    }

    public function faqLayout1()
    {
        return view('pages/faq-layout-1');
    }

    public function faqLayout2()
    {
        return view('pages/faq-layout-2');
    }

    public function faqLayout3()
    {
        return view('pages/faq-layout-3');
    }

    public function login()
    {
        return view('pages/login');
    }

    public function register()
    {
        return view('pages/register');
    }

    public function errorPage()
    {
        return view('pages/error-page');
    }

    public function updateProfile()
    {
        return view('pages/update-profile');
    }

    public function changePassword()
    {
        return view('pages/change-password');
    }

    public function regularTable()
    {
        return view('pages/regular-table');
    }

    public function tabulator()
    {
        return view('pages/tabulator');
    }

    public function modal()
    {
        return view('pages/modal');
    }

    public function slideOver()
    {
        return view('pages/slide-over');
    }

    public function notification()
    {
        return view('pages/notification');
    }

    public function accordion()
    {
        return view('pages/accordion');
    }

    public function button()
    {
        return view('pages/button');
    }

    public function alert()
    {
        return view('pages/alert');
    }

    public function progressBar()
    {
        return view('pages/progress-bar');
    }

    public function tooltip()
    {
        return view('pages/tooltip');
    }

    public function dropdown()
    {
        return view('pages/dropdown');
    }

    public function typography()
    {
        return view('pages/typography');
    }

    public function icon()
    {
        return view('pages/icon');
    }

    public function loadingIcon()
    {
        return view('pages/loading-icon');
    }

    public function regularForm()
    {
        return view('pages/regular-form');
    }

    public function datepicker()
    {
        return view('pages/datepicker');
    }

    public function tomSelect()
    {
        return view('pages/tom-select');
    }

    public function fileUpload()
    {
        return view('pages/file-upload');
    }

    public function wysiwygEditorClassic()
    {
        return view('pages/wysiwyg-editor-classic');
    }

    public function wysiwygEditorInline()
    {
        return view('pages/wysiwyg-editor-inline');
    }

    public function wysiwygEditorBalloon()
    {
        return view('pages/wysiwyg-editor-balloon');
    }

    public function wysiwygEditorBalloonBlock()
    {
        return view('pages/wysiwyg-editor-balloon-block');
    }

    public function wysiwygEditorDocument()
    {
        return view('pages/wysiwyg-editor-document');
    }

    public function validation()
    {
        return view('pages/validation');
    }

    public function chart()
    {
        return view('pages/chart');
    }

    public function slider()
    {
        return view('pages/slider');
    }

    public function imageZoom()
    {
        return view('pages/image-zoom');
    }
}