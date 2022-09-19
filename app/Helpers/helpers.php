<?php

use Illuminate\Support\Facades\DB;

function generate_order_no()
{
    // this function will generate a five digit order number
    $data = DB::table('orders')->select('id')->orderBy('id', 'desc')->first();
    $code = 1001;

    if ($data) {
        $code = $data->id + 1;
    }
    return $code;
}

function sideMenu($role_id)
{
    $parent = DB::table('menus')
        ->select(DB::raw('menus.id, menus.name, menus.menu_url, menus.parent_id, menus.icon'))
        ->join('permissions', 'permissions.menu_id', '=', 'menus.id')
        ->where('permissions.role_id', $role_id)
        ->orderBy('menus.president', 'ASC')
        ->where('menus.parent_id', 0)
        ->get();

    $sidmenu = [];
    foreach ($parent as $value) {
        $menus = [];
        $menus['id'] = $value->id;
        $menus['name'] = $value->name;
        $menus['url'] = $value->menu_url;
        $menus['icon'] = $value->icon;
        $menus['parent_id'] = $value->parent_id;


        if ($value->menu_url != null) {

            $menus['sub_menu'] = [];
        } else {

            $menus['sub_menu'] = subMenu($role_id, $value->id);

        }

        array_push($sidmenu, $menus);

    }

    return $sidmenu;

}


function subMenu($role_id, $id)
{

    return DB::table('menus')
        ->select(DB::raw('menus.id, menus.name, menus.menu_url, menus.parent_id, menus.icon'))
        ->join('permissions', 'permissions.menu_id', '=', 'menus.id')
        ->where('permissions.role_id', $role_id)
        ->where('menus.parent_id', '=', $id)
        ->orderBy('president', 'ASC')
        ->get()->toArray();
}

function makeNested($source)
{
    $menu = array();

    $sub_menu = array();

    $new_menu = [];

    foreach ($source as &$s) {
        if ($s['parent_id'] == 0) {
            // no parent_id so we put it in the root of the array
            $menu[] = &$s;
        }
        if ($s['parent_id'] != 0) {
            // it have  parent id so making child id
            $sub_menu[] = &$s;
        }
    }

    // in this loop we are puting child into there parent
    foreach ($menu as $key => $value) {
        $value['sub_menu'] = [];
        foreach ($sub_menu as $sk => $sub) {

            if ($value['id'] == $sub['parent_id']) {

                array_push($value['sub_menu'], $sub);

            }
        }

        array_push($new_menu, $value);
    }

    return $new_menu;
}

function date_convert($data)
{
    $strDate = substr($data, 4, 11);
    $finaldt = date('Y-m-d H:i:s', strtotime($strDate));
    return $finaldt;
}

function allPages()
{
    return App\Model\Setting\PageSetting::where('publish', 1)->get();
}

function facebookChat()
{
    return App\Model\Setting\Messenger::orderBy('id', 'desc')->first();
}

function googleAnalytics()
{
    return App\Model\Setting\GoogleAnalytic::orderBy('id', 'desc')->first();
}


function frontCategory()
{

    return App\Model\Category::select('id', 'category_name', 'category_native_name', 'icon')
        ->with('sub_category.sub_sub_category')
        ->where('status', '=', 1)
        ->orderBy('rank')
        ->get();

}

function getCurrentCurrency()
{
    return App\Model\Currency::where('currency_status', 1)->first();
}

function getLocationData()
{
    return App\Model\Setting\ShippingArea::where('status', 1)->get();
// calculating minute difference between to date time
}

function minuteCalculate($from_date_time, $to_date_time)
{

    $to_time = strtotime($to_date_time);
    $from_time = strtotime($from_date_time);
    return round(abs($to_time - $from_time) / 60, 2);
}
