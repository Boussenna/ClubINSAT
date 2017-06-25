<?php
namespace ClubBundle\Controller;

use ClubBundle\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Member controller.
 *
 */
class MemberController extends Controller
{


    /**
     * Lists all event entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $members = $em->getRepository('ClubBundle:Member')->findAll();

        return $this->render('ClubBundle:admin:index.html.twig', array(
            'members' => $members,
        ));
    }

    /**
     * Creates a new event entity.
     *
     */
    public function newAction(Request $request)
    {
        $member = new Member();
        $form = $this->createForm('ClubBundle\Form\MemberType', $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $member->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'), $fileName);
            $member->setPhoto($fileName);
            $em->persist($member);
            $em->flush();

            return $this->redirectToRoute('member_show', array('id' => $member->getId()));
        }

        return $this->render('ClubBundle:admin:new.html.twig', array(
            'member' => $member,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a event entity.
     *
     */
    public function showAction(Member $member)
    {
        $deleteForm = $this->createDeleteForm($member);
        $repository= $this->getDoctrine()->getManager() ->getRepository('ClubBundle:Member');
        $members=  $repository->findBy(['id' => $member->getId()]);

        return $this->render('ClubBundle:admin:show.html.twig', array(
            'member' => $member,
            'delete_form' => $deleteForm->createView(),
            'members' => $members
        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     */
    public function editAction(Request $request, Member $member)
    {
        $deleteForm = $this->createDeleteForm($member);
        $editForm = $this->createForm('ClubBundle\Form\MemberType', $member);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $file = $member->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'), $fileName);
            $member->setPhoto($fileName);
            $em->persist($member);
            $em->flush();
            return $this->redirectToRoute('member_show', array('id' => $member->getId()));
        }

        return $this->render('ClubBundle:admin:edit.html.twig', array(
            'member' => $member,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     *
     */
    public function deleteAction(Request $request, Member $member)
    {
        $form = $this->createDeleteForm($member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($member);
            $em->flush();
        }

        return $this->redirectToRoute('member_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Member $member)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('member_delete', array('id' => $member->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}