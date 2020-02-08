<?php


namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract
{

    public $categoriesArrayFromDb;
    public $categorylist;
    protected static $dbconnection;
    /**
     * @var UrlGeneratorInterface
     */
    public $urlGenerator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CategoryTreeAbstract constructor.
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(EntityManagerInterface $entityManager,
    UrlGeneratorInterface $urlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->categoriesArrayFromDb = $this->getCategories();
    }

    abstract public function getCategoryList(array $categories_array);

    public function buildTree(int $paren_id = null): array
    {
        $subcategory = [];
        foreach ($this->categoriesArrayFromDb as $category){
            if ($category['parent_id'] == $paren_id){
                $children = $this->buildTree($category['id']);
                if ($children){
                    $category['children'] = $children;
                }
                $subcategory[] = $category;
            }
        }
        return $subcategory;
    }

    private function getCategories():array
    {
        if (self::$dbconnection){
            return self::$dbconnection;
        }else {
            $conn = $this->entityManager->getConnection();
            $sql = "SELECT * FROM categories";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return self::$dbconnection = $stmt->fetchAll();
        }

    }
}
