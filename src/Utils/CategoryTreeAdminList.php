<?php


namespace App\Utils;


use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeAdminList extends CategoryTreeAbstract
{

    /**
     * @var string
     */
    public $html_1 = '<ul class="fa-ul text-left">';
    /**
     * @var string
     */
    public $html_2 = '<li><i class="fa-li fa fa-arrow-right"></i>';
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
    public $html_5 = '</a> <a onclick="return confirm(\'Are you sure?\');"href="';
    /**
     * @var string
     */
    public $html_6 = '">';
    /**
     * @var string
     */
    public $html_7 = '</a>';
    public $html_8 = '</li>';
    public $html_9 = '</ul>';

    public function getCategoryList(array $categories_array)
    {
        $this->categorylist .= $this->html_1;

        foreach ($categories_array as $value){

            $url_edit = $this->urlGenerator->generate('edit_category.en',    //note, en has been added because of translation in urls
                ['id' => $value['id']]
            );
            $url_delete = $this->urlGenerator->generate('delete_category.en',
                ['id' => $value['id']]
            );


            $this->categorylist.=$this->html_2 . $value['name'] .
                $this->html_3.$url_edit.$this->html_4 . ' '.'Edit' .
                $this->html_5 . $url_delete . $this->html_6 . 'Delete' .
                $this->html_7;

            if (!empty($value['children'])){
                $this->getCategoryList($value['children']);
            }
            $this->categorylist.= $this->html_8;
        }
        $this->categorylist.=$this->html_9;

        return $this->categorylist;
    }
}
