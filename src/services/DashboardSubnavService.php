<?php

class DashboardSubnavService
{
    public function renderNavigationLinks(string $currentPageUri): string {
        $navItems = [
            [
                'name' => 'Home',
                'path' => '/',
                'active_paths' => ['/', '/dashboard/index.php'],
            ],
            [
                'name' => 'My Games',
                'path' => '/dashboard/my-games.php',
                'active_paths' => ['/dashboard/my-games.php', '/dashboard/trash.php'],
            ],
            [
                'name' => 'Profile',
                'path' => '/dashboard/profile-edit.php',
                'active_paths' => ['/dashboard/profile-edit.php', '/accounts/avatar.php'],
            ],
            [
                'name' => 'Friends',
                'path' => '/friends/index.php',
                'active_paths' => ['/friends/index.php', '/friends/all.php'],
            ],
            [
                'name' => 'Awards',
                'path' => '/awards/index.php',
                'active_paths' => ['/awards/index.php', '/awards/all.php', '/awards/creator.php'],
            ],
            [
                'name' => 'Graphics',
                'path' => '/dashboard/my-graphics.php',
                'active_paths' => ['/dashboard/my-graphics.php', '/dashboard/tag-graphic.php'],
            ],
            [
                'name' => 'My Account',
                'path' => '/accounts/account.php',
                'active_paths' => ['/accounts/account.php'],
                'style' => 'float: right;'
            ],
        ];

        $cleanUri = trim(parse_url($currentPageUri, PHP_URL_PATH), '/');

        $html = '<div id="subnav"><ul class="nav_dashboard">';

        foreach ($navItems as $item) {
            $activePaths = array_map(function($path) {
                return trim($path, '/');
            }, $item['active_paths']);

            $activeClass = in_array($cleanUri, $activePaths) ? 'active' : '';
            $style = isset($item['style']) ? ' style="' . $item['style'] . '"' : '';
            
            if ($cleanUri === '' && in_array('/', $item['active_paths'])) {
                $activeClass = 'active';
            }

            $html .= '<li ' . $style . '>';
            $html .= '<a href="' . $item['path'] . '" ' . ($activeClass ? ' class="' . $activeClass . '"' : '') . '>' . $item['name'] . '</a>';
            $html .= '</li>';
        }

        $html .= '</ul></div>';

        return $html;
    }
}
