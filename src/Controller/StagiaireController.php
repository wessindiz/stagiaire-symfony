<?php

namespace App\Controller;

use \DateTime;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="stagiaire")
     */
    public function index(Request $request, StagiaireRepository $repo1, PaginatorInterface $paginator): Response
    {
        $repo = $this->getDoctrine()->getRepository(Stagiaire::class);
        $infoSearch = $request->query->get('searchbar');

        $stagiaires = $repo1->findAllStagiaireByName($infoSearch);

        $card = $paginator->paginate(
            $stagiaires, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1),2 // Nombre de résultats par page
        );
        
        
        //Affiche les stagiaire correspondants à la recherche dans la Searchbar
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $card,
        ]);


    }


    /**
     * @Route("/stagiaire/new", name="nvx_stg")
     */
    public function addStg()
    {

        $em = $this->getDoctrine()->getManager();

        $stg = new Stagiaire;
        $stg->setNom("Stinson");
        $stg->setPrenom("Kimberley");
        $stg->setTelephone("0634455789");
        $stg->setAdresse("4, chemin des Vagues");
        $stg->setDiplome(" Bac L");
        $stg->setContrat(true);
        $stg->setDescription("Autonome, travail de qualité !");
        $stg->setPhoto("Kimberley.jpg");
        $em->persist($stg);
        $em->flush();
        return $this->redirectToRoute("stagiaire");
    }


    /**
     * @Route("/stagiaire/show/{id}", name="show")
     */

    public function showstg($id)
    {

        $em = $this->getDoctrine()->getManager();
        $stagiaires = $em->getRepository(Stagiaire::class)->find($id);

        return $this->render('stagiaire/show.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    /**
     * @Route("/stagiaire/edit/{id}", name="edit")
     */


    public function editstg($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $stagiaire = $em->getRepository(Stagiaire::class)->find($id);
        $formedit = $this->createForm(StagiaireType::class, $stagiaire);
        $oldPhoto = $stagiaire->getPhoto();

        if (!$stagiaire) {
            throw $this->createNotFoundException(
                "Aucun stagiaire trouvé avec cet id!"
            );
        }

        //dd($oldPhoto);
        $formedit->handleRequest($request);
        if ($formedit->isSubmitted() && $formedit->isValid()) {
            $fileDelete = new FileSystem();
            $file = $formedit->get('photo')->getData();

            if ($file != null) {
                $fileName = time() . "." . $file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                if (file_exists('photos/' . $oldPhoto)) {
                    $fileDelete->remove('photos/' . $oldPhoto);
                }


                $stagiaire->setPhoto($fileName);
            } else {
                $stagiaire->setPhoto($oldPhoto);
            }

            $em->flush();

            return $this->redirectToRoute('stagiaire');
        }

        return $this->render('stagiaire/edit.html.twig', ["formedit" => $formedit->createView(), "stagiaire" => $stagiaire]);
    }



    /**
     * @Route("/stagiaire/delete/ {id}", name="delete")
     */
    public function deleteStg($id)
    {

        $em = $this->getDoctrine()->getManager();
        $stagiaires = $em->getRepository(Stagiaire::class)->find($id);

        if (!$stagiaires) {
            throw $this->createNotFoundException(
                "il n'y a rien à supprimer!"
            );
        }
        $em->remove($stagiaires);
        $em->flush();

        return $this->redirectToRoute("stagiaire");
    }


    /**
     * @Route("/stagiaire/add", name="add_stg")
     */
    public function newStg(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $stg = new Stagiaire();
        $form_add = $this->createForm(StagiaireType::class, $stg);
        $form_add->handleRequest($request);

        if ($form_add->isSubmitted() && $form_add->isValid()) {

            $imageDestination = $this->getParameter('images_directory');
            $file = $form_add->get('photo')->getData();
            $fileName = "";
            if ($file) {
                $fileName = time() . '.' . $file->guessExtension();
                $file->move(
                    $imageDestination,
                    $fileName
                );
            }
            $stg->setPhoto($fileName);
            $em->persist($stg);
            $em->flush();

            return $this->redirectToRoute("stagiaire");
        }

        return $this->render("stagiaire/add.html.twig", [
            "form" => $form_add->createView(),
        ]);
    }

    /**
     * @Route("/stagiaire/login", name="login")
     */
    public function login()
    {
        return $this->render('user/login.html.twig');
    }

    
}
