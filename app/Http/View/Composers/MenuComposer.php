<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Main\TopMenu;
use App\Main\SideMenu;
use App\Main\SimpleMenu;

class MenuComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        if (!is_null(request()->route())) {
            $pageName = request()->route()->getName();
            $layout = $this->layout($view);
            $activeMenu = $this->activeMenu($pageName, $layout);

            $view->with('top_menu', TopMenu::menu());
            $view->with('side_menu', SideMenu::menu());
            $view->with('simple_menu', SimpleMenu::menu());
            $view->with('first_level_active_index', $activeMenu['first_level_active_index']);
            $view->with('second_level_active_index', $activeMenu['second_level_active_index']);
            $view->with('third_level_active_index', $activeMenu['third_level_active_index']);
            $view->with('page_name', $pageName);
            $view->with('layout', $layout);
        }
    }

    /**
     * Specify used layout.
     */
    public function layout($view)
    {
        if (isset($view->layout)) {
            return $view->layout;
        } else if (request()->has('layout')) {
            return request()->query('layout');
        }

        return 'side-menu';
    }

    /**
     * Determine active menu & submenu.
     *
     * Match berdasarkan BASE resource:
     * admin.galleries.edit  →  base: admin.galleries
     * admin.galleries.index →  base: admin.galleries
     * = MATCH
     */
    public function activeMenu($pageName, $layout)
    {
        $firstLevelActiveIndex = '';
        $secondLevelActiveIndex = '';
        $thirdLevelActiveIndex = '';

        // Helper: match route name
        $match = function ($routeName) use ($pageName) {
            if (!$routeName) return false;
            if ($routeName === $pageName) return true;

            // Buang suffix resource
            $suffix = '/\.(index|create|edit|show|store|update|destroy|reorder)$/';
            $base       = preg_replace($suffix, '', $pageName);
            $targetBase = preg_replace($suffix, '', $routeName);

            return $base === $targetBase;
        };

        if ($layout == 'top-menu') {
            $menus = TopMenu::menu();
        } elseif ($layout == 'simple-menu') {
            $menus = SimpleMenu::menu();
        } else {
            $menus = SideMenu::menu();
        }

        foreach ($menus as $menuKey => $menu) {
            if ($menu === 'devider') continue;

            if (isset($menu['route_name']) && $match($menu['route_name'])) {
                $firstLevelActiveIndex = $menuKey;
            }

            if (isset($menu['sub_menu'])) {
                foreach ($menu['sub_menu'] as $subMenuKey => $subMenu) {
                    if (isset($subMenu['route_name']) && $match($subMenu['route_name'])) {
                        $firstLevelActiveIndex = $menuKey;
                        $secondLevelActiveIndex = $subMenuKey;
                    }

                    if (isset($subMenu['sub_menu'])) {
                        foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu) {
                            if (isset($lastSubMenu['route_name']) && $match($lastSubMenu['route_name'])) {
                                $firstLevelActiveIndex = $menuKey;
                                $secondLevelActiveIndex = $subMenuKey;
                                $thirdLevelActiveIndex = $lastSubMenuKey;
                            }
                        }
                    }
                }
            }
        }

        return [
            'first_level_active_index' => $firstLevelActiveIndex,
            'second_level_active_index' => $secondLevelActiveIndex,
            'third_level_active_index' => $thirdLevelActiveIndex
        ];
    }
}