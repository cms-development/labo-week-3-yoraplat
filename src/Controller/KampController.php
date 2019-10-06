<?php

namespace App\Controller;

use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Kamp;
use App\Entity\Reaction;

use \Datetime;

class KampController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $recentKampen = $this->getDoctrine()
            ->getRepository(Kamp::class)
            ->findAll();


        $spotlightKamp = $this->getDoctrine()
            ->getRepository(Kamp::class)
            ->findOneBy(['spotlight' => 'true']);



        return $this->render('kamp/index.html.twig', [
            'random' => $spotlightKamp,
            'kampen' => $recentKampen,
        ]);
    }

    /**
     * @Route("/kamp/{id}", name="view_kamp")
     */

    public function getKamp($id) {
        $kamp = $this->getDoctrine()
            ->getRepository(Kamp::class)
            ->find($id);

        $reactions = $this->getDoctrine()
            ->getRepository(Reaction::class)
            ->findBy(
                ['kamp_id' => $id]
                );

        return $this->render('kamp/view.html.twig', [
            'kamp' => $kamp,
            'reactions' =>$reactions
        ]);
    }

    /**
     * @Route("/kamp/{id}/edit", name="edit_kamp")
     */
    public function editKamp($id) {
        $kamp = $this->getDoctrine()
            ->getRepository(Kamp::class)
            ->find($id);
        return $this->render('kamp/edit.html.twig', [
            'kamp' => $kamp,
        ]);
    }

    /**
     * @Route("/update/{id}", name="update_kamp")
     */
    public function updateKamp(Request $request, $id) {
        $kamp = $this->getDoctrine()
            ->getRepository(Kamp::class)
            ->find($id);

        $kamp->setTitle($request->request->get('title'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($kamp);
        $entityManager->flush();

        return $this->redirectToRoute('view_kamp', ['id' => $id]);

    }

    /**
     * @Route("/new-kamp", name="new_kamp")
     */

    public function newKamp() {
        return $this->render('kamp/create.html.twig');
    }

    /**
     * @Route("/create", name="create_kamp")
     */
    public function createKamp(Request $request) {
        $kamp = new Kamp();



        $date = new DateTime();
        $kamp->setTitle($request->request->get('title'));
        // $kamp->setDate($request->request->get('date'));
        $kamp->setDate($date);
        $kamp->setDescription($request->request->get('description'));
        $kamp->setImage('empty');
        $kamp->setQuote($request->request->get('quote'));
        $kamp->setSpotlight(0);
        $kamp->setCreatedAt($date);



        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($kamp);
        $entityManager->flush();

        return$this->redirectToRoute('home');

    }

    /**
     * @Route("/kamp/react/{id}", name="react_kamp")
     */
    public function react($id, Request $request) {
        $reaction = new Reaction();

        $reaction->setKampId($id);
        $reaction->setReaction($request->request->get('reaction'));
        $reaction->setName($request->request->get('name'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reaction);
        $entityManager->flush();

        return $this->redirectToRoute('view_kamp', ['id' => $id]);

    }

    /**
     * @Route("/admin", name="admin")
     */

    public function getAdminPage() {
        $kampen = $this->getDoctrine()
            ->getRepository(Kamp::class)
            ->findAll();

        return $this->render('kamp/admin.html.twig', [
            'kampen' => $kampen,
        ]);
    }

    /**
     * @Route("/kamp/{id}/delete", name="delete_kamp")
     */

    public function deleteKamp($id) {
        $kamp = $this->getDoctrine()
            ->getRepository(Kamp::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($kamp);
        $entityManager->flush();

        return $this->redirectToRoute('admin');

    }
}
