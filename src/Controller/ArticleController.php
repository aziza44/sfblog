<?php

namespace App\Controller;
use App\Entity\Article;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request; 



class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(LoggerInterface $logger): Response
    {
        $bdd_articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        $logger->info('I just got the logger');
        return $this->render('article/list.html.twig', [
            'title' => 'ArticleController', 
            'subtitle'=> 'ArticleController',
            'articles'=>$bdd_articles,
        ]);
    }
    
    
        /**
         * @Route("/article/{id}", name="details")
         */
        public function details($id): Response
        {
            $bdd_article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->find($id);
            return $this->render('article/details.html.twig', [
                'title' => 'ArticleController', 
                'subtitle'=> 'ArticleController',
                'article'=>$bdd_article,
            ]);
        }  

        /**
         *  @Route("/articlecreate", name="article_create")
         */
        public function create(Request $request, LoggerInterface $logger):Response
    {

      
        $form = $this->createFormBuilder(null, array(
            'csrf_protection' => false,
        ))
            ->add('title', TextType::class)
            ->add('image', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Article'])
            //->add('submit', 'submit')
            ->getForm();
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                // data is an array with "title", "image", and "description" keys
                $data = $form->getData();
                $logger->info($data["title"]);
                $logger->info($data["image"]);
                $logger->info($data["description"]);
                $entityManager = $this->getDoctrine()->getManager();
                $article = new Article();
                $article->setImage($data["image"]);
                $article->setTitle($data["title"]);
                $article->setDescription($data["description"]);
                $entityManager->persist($article);
                $entityManager->flush();
            }
            return $this->render('article/create.html.twig', [
                'form' => $form->createView(),
               


            ]);

        

    }
}
        



