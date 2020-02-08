<?php


namespace App\Controller\Admin\SuperAdmin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\CategoryTreeAdminList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller\Admin\SuperAdmin
 * @Route("/admin")
 */
class CategoryController extends AbstractController
{
    private function saveCategory($category, $form, Request $request): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setName($request->request->get('category')['name']);

            $repository = $this->getDoctrine()->getRepository(Category::class);
            $parent = $repository->find($request->request->get('category')['parent']);
            $category->setParent($parent);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return true;
        }
        return false;

    }


    /**
     * @Route("/su/delete-category/{id}", name="delete_category")
     * @param Category $category
     * @return RedirectResponse
     */
    public function deleteCategory(Category $category)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('categories');
    }

    /**
     * @Route("/su/edit-category/{id}", name="edit_category", methods={"GET","POST"})
     * @param Category $category
     * @param Request $request
     * @return Response
     */
    public function editCategory(Category $category, Request $request)
    {
        $form = $this->createForm(CategoryType::class, $category);

        $is_invalid = null;

        if ($this->saveCategory($category, $form, $request)){

            return $this->redirectToRoute('categories');

        }elseif ($request->isMethod('post')){
            $is_invalid = ' is-invalid';
        }

        return $this->render('admin/edit_category.html.twig', [
            'category' => $category,
            'form' =>$form->createView(),
            'is_invalid' => $is_invalid,
        ]);
    }


    /**
     * @Route("/su/categories", name="categories", methods={"GET", "POST"})
     * @param CategoryTreeAdminList $categories
     * @param Request $request
     * @return Response
     */
    public function categories(CategoryTreeAdminList $categories, Request $request)
    {

        $categories->getCategoryList($categories->buildTree());

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $is_invalid = null;


        if ($this->saveCategory($category, $form, $request)){

            return $this->redirectToRoute('categories');

        }elseif ($request->isMethod('post')){
            $is_invalid = ' is-invalid';
        }

        dump($categories->categorylist);
        return $this->render('admin/categories.html.twig', [
            'categories'=> $categories->categorylist,
            'form' =>$form->createView(),
            'is_invalid' => $is_invalid,

        ]);
    }

}
