<?php

namespace ClubBundle\Controller;


use ClubBundle\Entity\Inscription;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ClubBundle\Entity\Event;
use ClubBundle\Entity\Member;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{


    public function indexAction()
    {  $em = $this->getDoctrine()->getManager();
       $repo1 = $em->getRepository('ClubBundle:Event');
        $repo2 = $em->getRepository('ClubBundle:Member');
       $events= $repo1->findAll();
       $members=$repo2->findAll();

        return $this->render('ClubBundle::index.html.twig', array('events' => $events, 'members'=>$members));
    }

    public function memberAction()
    {
        return $this->render('ClubBundle:member:index.html.twig');
    }

    public function profilAction(Member $member)
    {
        return $this->render('ClubBundle:Default:page.html.twig', array('member'=>$member));
    }

    public function eventAction(Event $event)
    {   $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ClubBundle:Inscription');

        $user = $this->getUser();
        $bool = "S'inscrire";

        $test=$repo->findOneBynomUser($user->getUserName());
        if(!$test==null) {
            $bool = "Déja inscrit";
            return $this->render('ClubBundle:Default:event.html.twig', array('event' => $event, 'bool'=> $bool, 'test'=>$test));}

        else {
            return $this->render('ClubBundle:Default:event.html.twig', array('event' => $event, 'bool'=> $bool));
    }}

    /**1
     * @param Event $event
     */
    public function subscribeAction(Request $request,Event $event)
{   $em = $this->getDoctrine()->getManager();
    $repo1 = $em->getRepository('ClubBundle:Event');
    $repo2 = $em->getRepository('AppBundle:User');
    $repo =  $em->getRepository('ClubBundle:Inscription');
    $bool = "Déja inscrit";
    $session = $request->getSession();
       $user =  $this->getUser();

    $test=$repo->findOneBynomUser($user->getUserName());
    if(!$test==null) {
        $session->getFlashBag()->add('alert',  'Vous avez déjà envoyé votre inscription ');
        return $this->render('ClubBundle:Default:event.html.twig', array('event' => $event, 'bool'=> $bool, 'test'=>$test));
    } else {
//n'Existe pas


        $inscription = new Inscription();
        $inscription->setUserId($user->getId())
            ->setEventId($event->getId())
            ->setNomEvent($event->getTitre())
            ->setNomUser($user->getUserName())
            ->setEtat("non validé");
        $em->persist($inscription);
        $em->flush();

        $session->getFlashBag()->add('success', 'Votre demande a été envoyée avec succès,Veuillez attendre la confirmation de l\'admin ');
        return $this->render('ClubBundle:Default:event.html.twig', array('event' => $event, 'bool' => $bool));
    }
}

     public function validerAction()
     {
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository('ClubBundle:Inscription');
    $inscriptions = $repo->findBy(['etat' =>'non validé']);
    return $this->render('ClubBundle:admin:validation.html.twig',array('inscriptions'=>$inscriptions));

     }

    public function confirmerAction($id,$confirm ,Request $request)
    {  $session = $request->getSession();
       $em =  $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ClubBundle:Inscription');
        //$rep=$em->getRepository('AppBundle:User');
        $inscription = $repo->find($id);
        //$userid=$inscription->getUserId();
        //$email=$rep->findOneByid($userid)->getEmail;
        //$name=$rep->findOneBy($userid)->getUsername;
        if($confirm == "accepter")
        {  $inscription->setEtat("validé");
            $session->getFlashBag()->add('success',  'Demande d inscription  est acceptée avec succès');

            //send mail
          /*  $message = \Swift_Message::newInstance()
                ->setSubject('Confirmation | Club INSAT')
                ->setFrom('club.insatien@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'ClubBundle::ConfMail.txt.twig',
                        array('name' => $name)
                    )
                )
            ;
            $this->get('mailer')->send($message);*/
            //end

        }
        else
        {  $inscription->setEtat("refuse");
            $session->getFlashBag()->add('echec',  'Demande d inscription  est refusée avec succès');
        }
        $em->persist($inscription);
        $em->flush();

        $inscriptions = $repo->findBy(['etat' =>'non validé']);
        return $this->render('ClubBundle:admin:validation.html.twig',array('inscriptions'=>$inscriptions));

    }
  public function inscriptionsAction()
  {
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('ClubBundle:Inscription');
      $inscriptions = $repo->findAll();
      return $this->render('ClubBundle:admin:inscriptions.html.twig',array('inscriptions'=>$inscriptions));
  }
}
