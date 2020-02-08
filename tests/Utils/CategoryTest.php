<?php

namespace App\Tests\Utils;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;


class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;
    protected $mockedCategoryTreeAdminList;
    protected $mockedCategoryTreeAdminOptionList;

    protected function setUp(): void
    {

        $kernel = self::bootKernel();

        $urlGenerator = $kernel->getContainer()->get('router');

        $tested_classes = [
            'CategoryTreeAdminList',
            'CategoryTreeAdminOptionList',
            'CategoryTreeFrontPage'
        ];

        foreach ($tested_classes as $class) {
            $name = 'mocked' . $class;
            $this->$name = $this->getMockBuilder('App\Utils\\' . $class)
                ->disableOriginalConstructor()
                ->setMethods()
                ->getMock();

            $this->$name->urlGenerator = $urlGenerator;
        }


        $this->mockedCategoryTreeFrontPage = $this->getMockBuilder('App\Utils\CategoryTreeFrontPage')
            ->disableOriginalConstructor()
            ->setMethods() // if no, all method returned unless mocked
            ->getMock();

        $this->mockedCategoryTreeFrontPage->urlGenerator = $urlGenerator;
    }


    /**
     * @param $string
     * @param $array
     * @param $id
     * @dataProvider dataForCategoryTreeFrontPage
     */
    public function testCategoryTreeFrontPage($string, $array, $id)
    {
        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension();
        $main_parent_id = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($main_parent_id);


        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }

    /**
     * @dataProvider dataForCategoryTreeAdminOptionList
     * @param $arrayToCompare
     * @param $arrayFromDb
     */
    public function testCategoryTreeAdminOptionList($arrayToCompare, $arrayFromDb)
    {


        $this->mockedCategoryTreeAdminOptionList->categoriesArrayFromDb = $arrayFromDb;


        $arrayFromDb = $this->mockedCategoryTreeAdminOptionList->buildTree();
        $this->assertSame($arrayToCompare, $this->mockedCategoryTreeAdminOptionList->getCategoryList($arrayFromDb));
    }

    /**
     * @param $string
     * @param $array
     * @dataProvider dataForCategoryTreeAdminList
     */
    public function testCategoryTreeAdminList($string, $array)
    {
        $this->mockedCategoryTreeAdminList->categoriesArrayFromDb = $array;

        $array = $this->mockedCategoryTreeAdminList->buildTree();

        $this->assertSame($string, $this->mockedCategoryTreeAdminList->getCategoryList($array));

    }

    public function dataForCategoryTreeFrontPage()
    {
        yield ['<ul><li><a href="/video-list/category/computers,7">Computers</a><ul><li><a href="/video-list/category/laptops,9">Laptops</a><ul><li><a href="/video-list/category/hp,15">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "7", "parent_id" => "1", "name" => "Computers",],
                ["id" => "9", "parent_id" => "7", "name" => "Laptops"],
                ["id" => "15", "parent_id" => "9", "name" => "HP"]
            ],
            1
        ];
        yield ['<ul><li><a href="/video-list/category/computers,7">Computers</a><ul><li><a href="/video-list/category/laptops,9">Laptops</a><ul><li><a href="/video-list/category/hp,15">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "7", "parent_id" => "1", "name" => "Computers",],
                ["id" => "9", "parent_id" => "7", "name" => "Laptops"],
                ["id" => "15", "parent_id" => "9", "name" => "HP"]
            ],
            7
        ];
        yield ['<ul><li><a href="/video-list/category/computers,7">Computers</a><ul><li><a href="/video-list/category/laptops,9">Laptops</a><ul><li><a href="/video-list/category/hp,15">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "7", "parent_id" => "1", "name" => "Computers",],
                ["id" => "9", "parent_id" => "7", "name" => "Laptops"],
                ["id" => "15", "parent_id" => "9", "name" => "HP"]
            ],
            9
        ];
        yield ['<ul><li><a href="/video-list/category/computers,7">Computers</a><ul><li><a href="/video-list/category/laptops,9">Laptops</a><ul><li><a href="/video-list/category/hp,15">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "7", "parent_id" => "1", "name" => "Computers",],
                ["id" => "9", "parent_id" => "7", "name" => "Laptops"],
                ["id" => "15", "parent_id" => "9", "name" => "HP"]
            ],
            15
        ];
    }

    public function dataForCategoryTreeAdminOptionList()
    {
        yield [
            [
                ['name' => 'Electronics', 'id' => 1],
                ['name' => '--Computers', 'id' => 7],
                ['name' => '----Laptops', 'id' => 9],
                ['name' => '------HP', 'id' => 15]
            ],
            [
                ['name' => 'Electronics', 'id' => 1, 'parent_id' => null],
                ['name' => 'Computers', 'id' => 7, 'parent_id' => 1],
                ['name' => 'Laptops', 'id' => 9, 'parent_id' => 7],
                ['name' => 'HP', 'id' => 15, 'parent_id' => 9]
            ]
        ];

    }

    public function  dataForCategoryTreeAdminList()
    {
        yield ['<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>Toys<a href="/admin/su/edit-category/2"> Edit</a> <a onclick="return confirm(\'Are you sure?\');"href="/admin/su/delete-category/2">Delete</a></li></ul>',

            [
                ['id' => 2, 'parent_id' => null, 'name' => "Toys"],
            ]
        ];

        yield ['<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>Toys<a href="/admin/su/edit-category/2"> Edit</a> <a onclick="return confirm(\'Are you sure?\');"href="/admin/su/delete-category/2">Delete</a></li><li><i class="fa-li fa fa-arrow-right"></i>Movies<a href="/admin/su/edit-category/4"> Edit</a> <a onclick="return confirm(\'Are you sure?\');"href="/admin/su/delete-category/4">Delete</a></li></ul>',

            [
                ['id' => 2, 'parent_id' => null, 'name' => "Toys"],
                ['id' => 4, 'parent_id' => null, 'name' => "Movies"],
            ]
        ];

        yield ['<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>Toys<a href="/admin/su/edit-category/2"> Edit</a> <a onclick="return confirm(\'Are you sure?\');"href="/admin/su/delete-category/2">Delete</a></li><li><i class="fa-li fa fa-arrow-right"></i>Movies<a href="/admin/su/edit-category/4"> Edit</a> <a onclick="return confirm(\'Are you sure?\');"href="/admin/su/delete-category/4">Delete</a><ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>Family<a href="/admin/su/edit-category/18"> Edit</a> <a onclick="return confirm(\'Are you sure?\');"href="/admin/su/delete-category/18">Delete</a></li></ul></li></ul>',

            [
                ['id' => 2, 'parent_id' => null, 'name' => "Toys"],
                ['id' => 4, 'parent_id' => null, 'name' => "Movies"],
                ['id' => 17, 'parent_id' => 3, 'name' => "Kindle eBooks"],
                ['id' => 18, 'parent_id' => 4, 'name' => "Family"]
            ]
        ];
    }
}
