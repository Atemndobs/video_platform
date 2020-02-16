<?php

namespace App\Controller;

use App\Controller\Traits\Likes;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Video;
use App\Repository\VideoRepository;
use App\Utils\CategoryTreeFrontPage;
use App\Utils\VideoForNoValidSubscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FrontController extends AbstractController
{
    use Likes;

    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/video-list/category/{categoryname},{id}/{page}", defaults={"page":"1"}, name="video_list")
     * @param $id
     * @param $page
     * @param CategoryTreeFrontPage $categories
     * @param Request $request
     * @param VideoForNoValidSubscription $video_no_members
     * @return Response
     */
    public function videoList($id, $page, CategoryTreeFrontPage $categories, Request $request, VideoForNoValidSubscription $video_no_members)
    {
        $ids = $categories->getChildIds($id);

        array_push($ids, $id);

        $videos = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findByChildIds($ids, $page, $request->get('sortby'));

        $categories->getCategoryListAndParent($id);

        return $this->render('front/video_list.html.twig', [
            'subcategories' => $categories,
            'videos' => $videos,
            'video_no_members' => $video_no_members->check()
        ]);
    }


    /**
     * @Route("/video-details/{video}", name="video_details")
     * @param VideoRepository $videoRepository
     * @param $video
     * @param VideoForNoValidSubscription $video_no_members
     * @return Response
     */
    public function videoDetails(VideoRepository $videoRepository, $video, VideoForNoValidSubscription $video_no_members)
    {
        return $this->render('front/video_details.html.twig', [
            'video' => $videoRepository->videoDetails($video),
            'video_no_members' => $video_no_members->check(),
        ]);
    }

    /**
     * @param Video $video
     * @param Request $request
     * @return RedirectResponse
     * @Route("/new-comment/{video}", methods={"POST"}, name="new_comment")
     */
    public function newComment(Video $video, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        if (!empty(trim($request->request->get('comment')))) {
            $comment = new  Comment();
            $comment->setContent($request->request->get('comment'));
            $comment->setUser($this->getUser());
            $comment->setVideo($video);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('video_details', [
            'video' => $video->getId()
        ]);
    }

    /**
     * @Route("/search-results/{page}", name="search_results", methods={"GET"}, defaults={"page" : "1"})
     * @param $page
     * @param Request $request
     * @param VideoForNoValidSubscription $video_no_members
     * @return Response
     */
    public function searchResults($page, Request $request, VideoForNoValidSubscription $video_no_members)
    {

        $videos = null;
        $query = null;

        if ($query = $request->get('query')) {
            $videos = $this->getDoctrine()
                ->getRepository(Video::class)
                ->findByTitle($query, $page, $request->get('sortby'));

            if (!$videos->getItems()) $videos = null;
        }
        return $this->render('front/search_results.html.twig', [
            'videos' => $videos,
            'query' => $query,
            'video_no_members' => $video_no_members->check(),
        ]);
    }

    /**
     * @param Video $video
     * @param Request $request
     * @return Response
     * @Route("/video-list/{video}/like", name="like_video", methods={"POST"})
     * @Route("/video-list/{video}/dislike", name="dislike_video", methods={"POST"})
     * @Route("/video-list/{video}/unlike", name="undo_like_video", methods={"POST"})
     * @Route("/video-list/{video}/undodislike", name="undo_dislike_video", methods={"POST"})
     */
    public function toggleLikesAjax(Video $video, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        switch ($request->get('_route')) {
            case 'like_video':
                $result = $this->likeVideo($video);
                break;
            case 'dislike_video':
                $result = $this->dislikeVideo($video);
                break;
            case 'undo_like_video':
                $result = $this->unlikeVideo($video);
                break;
            case 'undo_dislike_video':
                $result = $this->undoDislikeVideo($video);
                break;
        }

        return $this->json(['action' => $result, 'id' => $video->getId()]);
    }


    public function mainCategories()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['parent' => null], ['name' => 'ASC']);
        return $this->render('front/_main_categories.html.twig', [
            'categories' => $categories
        ]);

    }

    /**
     * @Route("/delete-comment/{comment}", name="delete_comment")
     * @Security("user.getId() == comment.getUser().getId()")
     * @param Comment $comment
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteComment(Comment $comment, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
