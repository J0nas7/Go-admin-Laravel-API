<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Corcel\Model\Page;
use Corcel\Model\Menu;
use Corcel\Model\Option;

class PageController extends Controller
{
    /**
     * basicPageInfo
     * Return general page info
     *
     * @return response json
     */
    public function basicPageInfo() {
        // Grab blog name and description from WP
        $options = Option::asArray(['blogname', 'blogdescription']);
        
        return response()->json([
            'success' => true,
            'message' => 'Basic Page Info',
            'data'    => $options
        ], 200);
    }
    
    /**
     * getMenuLocation
     * Get a menu location
     *
     * @param  string $slug
     * @return response json
     */
    public function getMenuLocation($slug) {
        $menu = Menu::slug($slug)->first();
        $menuItems = array();
        foreach ($menu->items as $item) {
            $itemLabel = $item->title;
            $itemLink = $item->_menu_item_url;
            $menuItems[] = array("label" => $itemLabel, "link" => $itemLink);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Get Menu Location by Slug',
            'data'    => $menuItems
        ], 200);
    }

    /**
     * showSpecificPage
     * Page content by slug
     *
     * @return view
     */
    public function showSpecificPage() {
        $thePage = Page::slug('levering')->first();

        return view('page', [
            'displayPage' => $thePage,
        ]);
    }
}
?>