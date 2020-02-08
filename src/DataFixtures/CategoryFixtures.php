<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load($manager)
    {

        $this->loadMainCategories($manager);
        $this->loadElectronics($manager);
        $this->loadComputers($manager);
        $this->loadLaptops($manager);
        $this->loadBooks($manager);
        $this->loadMovies($manager);
        $this->loadRomance($manager);
        $this->loadMusic($manager);



    }

    private function loadMainCategories(ObjectManager $manager)
    {
        foreach ($this->getMainCategoryData() as [$name] ){
                $category = new category();
                $category->setName($name) ;

                $manager->persist($category);
        }

        $manager->flush();
    }

    private function loadSubCategories(ObjectManager $manager, $category, $parent_id)
    {
        $parent = $manager->getRepository(Category::class)->find($parent_id);
        $methodName = "get{$category}Data";
        foreach ($this->$methodName() as [$name] ){

            $category = new category();
            $category->setName($name) ;
            $category->setParent($parent);
            $manager->persist($category);
        }

        $manager->flush();
    }

    private function loadElectronics($manager)
    {
        $this->loadSubCategories($manager, 'Electronics', 1);
    }

    private function loadComputers($manager)
    {
        $this->loadSubCategories($manager, 'Computers', 7);
    }

    private function loadLaptops($manager)
    {
        $this->loadSubCategories($manager, 'Laptops', 9);
    }

    private function loadBooks($manager)
    {
        $this->loadSubCategories($manager, 'Books', 3);
    }

    private function loadMovies($manager)
    {
        $this->loadSubCategories($manager, 'Movies', 4);
    }

    private function loadRomance($manager)
    {
        $this->loadSubCategories($manager, 'Romance', 19);
    }

    private function loadMusic($manager)
    {
        $this->loadSubCategories($manager, 'Music', 5);
    }

    private function getMainCategoryData()
    {
        return [
            ['Electronics' , 1],
            [ 'Toys' , 2],
            ['Books' , 3],
            ['Movies', 4],
            ['Music' , 5],
        ];
    }

    private function getElectronicsData()
    {
        return [
            ['Cameras' , 6],
            ['Computers' , 7],
            ['Cell Phones' , 8],
        ];
    }

    private function getComputersData()
    {
        return [
            ['Laptops' , 9],
            ['Desktops' , 10]
        ];
    }

    private function getLaptopsData()
    {
        return [
            ['Apple' , 11],
            ['Asus' , 12],
            ['Dell' , 13],
            ['Lenovo' , 14],
            ['HP' , 15],
        ];
    }

    private function getBooksData()
    {
        return [
            ['Children\'s Books' , 16],
            ['Kindle eBooks' , 17],
        ];
    }

    private function getMoviesData()
    {
        return [
            ['Family' , 18],
            ['Romance' , 19],
        ];
    }

    private function getRomanceData()
    {
        return [
            ['Romantic Comedy' , 20],
            ['Romantic Drama' , 21],
        ];
    }

    private function getMusicData()
    {
        return [
            ['Afro' , 22],
            ['Pop' , 23],
            ['Moombahton' , 24],
            ['Trap' , 25],
            ['Rock' , 26],
        ];
    }
}
