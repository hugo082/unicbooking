<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;

use AppBundle\EventListener\ChangePassListener;

use AppBundle\Entity\User;

class UserController extends Controller
{
    /**
    * @Route("/admin/manage/users", name="admin.manage.users")
    */
    public function indexAction(Request $request)
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->getAllVisible();
        return $this->render('booking/manage/user.html.twig', array(
            'entities' => $users
        ));
    }

    /**
    * @Route("/admin/manage/users/remove/{id}", requirements={"id" = "\d+"}, name="admin.manage.users.remove")
    */
    public function removeAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if ($user) {
            $user->setEnabled(false);
            $user->setRemoved(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.users');
    }

    /**
    * @Route("/admin/manage/users/changepass/{id}", requirements={"id" = "\d+"}, name="admin.manage.users.changepass")
    */
    public function changepassAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (!$user) {
            $this->addFlash('warning','User not found.');
            return $this->redirectToRoute('admin.manage.users');
        }
        $user->addRole(ChangePassListener::CHANGE_PASS_ROLE);
        $em = $this->getDoctrine()->getManager();
        $em->flush($user);
        $this->addFlash('success','User will have to change password at next logon');
        return $this->redirectToRoute('admin.manage.users');
    }
}
