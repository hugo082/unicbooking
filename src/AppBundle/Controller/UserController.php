<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.users');
    }

}
