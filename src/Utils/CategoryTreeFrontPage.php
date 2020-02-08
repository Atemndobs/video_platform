<?php


namespace App\Utils;


use App\Twig\AppExtension;
use App\Utils\AbstractClasses\CategoryTreeAbstract;

/**
 * Class CategoryTreeFrontPage
 * @package App\Utils
 */
class CategoryTreeFrontPage extends CategoryTreeAbstract
{
    /**
     * @var string
     */
    public $html_1 = '<ul>';
    /**
     * @var string
     */
    public $html_2 = '<li>';
    /**
     * @var string
     */
    public $html_3 = '<a href="';
    /**
     * @var string
     */
    public $html_4 = '">';
    /**
     * @var string
     */
    public $html_5 = '</a>';
    /**
     * @var string
     */
    public $html_6 = '</li>';
    /**
     * @var string
     */
    public $html_7 = '</ul>';
    /**
     * @var AppExtension
     */
    public $slugger;
    /**
     * @var
     */
    public $mainParentName;
    /**
     * @var
     */
    public $mainParentId;
    /**
     * @var
     */
    public $currentCategoryName;

    /**
     * @param int $id
     * @return string
     */
    public function getCategoryListAndParent(int $id) :string
    {
        $this->slugger = new AppExtension(); // twig extension slugify url for categories


        $parentData = $this->getMainParent($id) ; // main parent sub   category

        $this->mainParentName = $parentData['name'];  // for accessing name in view
        $this->mainParentId = $parentData['id'];

        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));
        $this->currentCategoryName = $this->categoriesArrayFromDb[$key]['name']; // for accessing name in view

        $categories_array = $this->buildTree($parentData['id']); // builds array for generating nested Html Lists
        return $this->getCategoryList($categories_array);

    }

    /**
     * @param array $categories_array
     * @return string
     */
    public function getCategoryList(array $categories_array)
     {
         $this->categorylist.= $this->html_1;

         foreach ($categories_array as $value){

             $catName  = $this->slugger->slugify($value['name']);

             $url = $this->urlGenerator->generate('video_list', ['categoryname'=> $catName , 'id'=>$value['id']]);

             $this->categorylist.=  $this->html_2.$this->html_3 . $url. $this->html_4. $value['name'] .$this->html_5;

             if (!empty($value['children'])){
                 $this->getCategoryList($value['children']);
             }
         }
         $this->categorylist.= $this->html_6;
         $this->categorylist.= $this->html_7;

         return $this->categorylist;
     }

    /**
     * @param int $id
     * @return array
     */
    public function getMainParent(int $id):array
    {
        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));

        if ($this->categoriesArrayFromDb[$key]['parent_id'] != null){
            return $this->getMainParent($this->categoriesArrayFromDb[$key]['parent_id']);
        }else{
            return [
                'id'=>$this->categoriesArrayFromDb[$key]['id'],
                'name'=>$this->categoriesArrayFromDb[$key]['name']
            ];
        }
     }

    public function getChildIds(int $parent): array
    {
        static $ids  = [];

        foreach ($this->categoriesArrayFromDb as $value){
            if ($value['parent_id'] == $parent){
                $ids[] = $value['id'].',';

                $this->getChildIds($value['id']);
            }
        }
        return $ids;
    }
}
