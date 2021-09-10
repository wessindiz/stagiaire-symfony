<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Stagiaire;
use App\Repository\UserRepository;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $repoStg;
    private $repoUser;
    public function __construct(StagiaireRepository $repoStg, UserRepository $repoUser)
    {
        $this->repoStg = $repoStg;
        $this->repoUser = $repoUser;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
         $repo = $this->getDoctrine()->getRepository(Stagiaire::class);
         $stagiaire = $repo->findAll();
         $nbStg = count($stagiaire);
         $repo1 = $this->getDoctrine()->getRepository(User::class);
         $repoUser= $repo1->findAll();
         $nbUser= count($repoUser);
        return $this->render('bundles/easyAdminBundle/welcome.html.twig',[
         'Stg' => $nbStg,
         'Users' => $nbUser,
       // 'nbStg' => count($this->repoStg->findAll()),
       // 'nbUser' => count($this->repoUser>findAll()),

        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tableau de bord');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToCrud('Stagiaires', 'far fa-id-card', Stagiaire::class),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-users-cog', User::class),
            MenuItem::linkToRoute('Retour sur le site', 'fa fa-home', 'stagiaire'),
        ];
    }
}
