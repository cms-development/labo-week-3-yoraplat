<?php

namespace App\Controller;

use Doctrine\DBAL\Types\BooleanType;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

        /*
        $kamp->setTitle($request->request->get('title'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($kamp);
        $entityManager->flush();

        return $this->redirectToRoute('view_kamp', ['id' => $id]);
*/
        $form = $this->createFormBuilder($kamp)
            ->add('title', TextType::class, [
                'label' => 'Name of the title',
                'empty_data' => ''
            ])
            ->add('date', DateType::class)
            ->add('description', TextareaType::class)
            ->add('image', TextType::class)
            ->add('quote', TextType::class)
            ->add('spotlight', CheckboxType::class)
            ->add('created_at', DateType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        // validate
        if($form->isSubmitted() && $form->isValid()) {
            $kamp = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($kamp);
            $em->flush();
        }

        return $this->redirectToRoute('view_kamp', [
            'form' => $form->createView(),
            'id' => $id
        ]);


    }


    /**
     * @Route("/create", name="create_kamp")
     */
    public function createKamp(Request $request) {
        $kamp = new Kamp();

    $form = $this->createFormBuilder($kamp)
        ->add('title', TextType::class, [
            'label' => 'Name of the title',
            'empty_data' => ''
        ])
        ->add('date', DateType::class)
        ->add('description', TextareaType::class)
        ->add('image', TextType::class)
        ->add('quote', TextType::class)
        ->add('spotlight', CheckboxType::class)
        ->add('created_at', DateType::class)
        ->add('save', SubmitType::class)
        ->getForm();

    $form->handleRequest($request);

    // validate
    if($form->isSubmitted() && $form->isValid()) {
        $kamp = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($kamp);
        $em->flush();
    }

    return $this->render('kamp/create.html.twig', [
        'form' => $form->createView()
    ]);
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
