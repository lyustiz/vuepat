<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//MODELS
use App\Models\RolUsuario;
use App\Models\RolMenu;
use App\Models\Menu;

class MenuBuilderController extends Controller
{
    public function getMenuByUsuario($id_usuario)
    {

        $rolesUsuario = RolUsuario::select('id_rol')->where('id_usuario', '=', $id_usuario)->where('id_status', '=', 1)->get();

        $menusArray = array();
        $counter = 0;

        foreach($rolesUsuario as $id_rol){

        	if(isset(($counter == 0) ? $counter : $counter - 1)){
	         
				$menus = RolMenu::select('id_menu')
									->where('id_rol', '=', $id_rol)
									->where('id_status', '=', 1)
									->get();

				foreach($menus as $menu){

					if($menu->id_menu != $menusArray[$counter - 1]->id_menu){
						array_push($menusArray, $menu);
						$counter ++;
					}

				}
        		
	        }
        }

        $counter = 0;
        $menus = array();

        foreach ($menusArray as $menu) {
        	
        	$menus[$counter] = Menu::select('id_menu', 'nb_menu', 'nb_icon', 'tx_ruta', 'id_padre')
        					->where('id_menu', '=', $menu->id_menu
        					->where('id_status', '=', 1)
        					->get();

        	$counter ++;
        }


        $menuJson = getMenuJson($menus, false, null);

        return json_encode($menuJson);



    }

    public function getMenuJson($menus = null, $hasChilds = false, $menuPadre = null)
    {

    	//HANDLES WHEN MENU HAS CHILDS
      if($hasChilds){

        foreach($menus as $menu) {
          
          $menuJson = '';
          $isParent = false;


          foreach($menus as $menuChild){

            if($menu->id_menu == $menuChild->id_padre) {

              $isParent = true;
              break;

            }

          }

          if ($menuPadre->id_menu == $menu->id_padre) {

            if ($isParent) {

              $menuJson += '{ "icon" : "' + $menu->nb_icon + '", "text": "' + $menu->nb_menu + '", "children": [ ';

              $menuJson += getMenuJson(null, true, $menu);

              $menuJson += '], },';

              return $menuJson;

            }else {

              $menuJson += '{ "icon": "' + $menu->nb_icon + '", "text": "' + $menu->nb_menu + '", "href": "' + $menu->tx_ruta + '" },';

              return $menuJson;
            }

          }//END IF


        }//END FOREACH

      }else{

        $menuJson = "[";
        $isParent = false;

        foreach($menus as $menu){

         foreach($menus as $menuChild){

            if($menu->id_menu == $menuChild->id_padre) {

              $isParent = true;
              break;

            }

          }

          if($menu->id_padre == 0){
        
            $menuJson += '{ "heading": "' + $menu->nb_menu + '" },';
        
          }else if ($isParent) {

            $menuJson += '{ "icon" : "' + $menu->nb_icon + '", "text": "' + $menu->nb_menu + '", "children": [ ';

            $menuJson += getMenuJson(null, true, $menu);

            $menuJson += '], },';

          }else {

            $menuJson += '{ "icon": "' + $menu->nb_icon + '", "text": "' + $menu->nb_menu + '", "href": "' + $menu->tx_ruta + '" },';

          }

        }//END FOREACH

        $menuJson += ']';
        return $menuJson;

      }//END ELSE HAS CHILD

    }//END FUNCTION



}//END CLASS