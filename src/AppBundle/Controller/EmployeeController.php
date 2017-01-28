<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Employee;
use AppBundle\Form\EmployeeType;

class EmployeeController extends Controller
{
    /**
    * @Route("/admin/manage/employees", name="admin.manage.employees")
    */
    public function indexAction(Request $request)
    {
        $employee = new Employee();

        $employees = $this->getDoctrine()->getRepository('AppBundle:Employee')->getAllVisible();

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();
            return $this->redirectToRoute('admin.manage.employees');
        }

        return $this->render('booking/manage/employee.html.twig', array(
            'form' => $form->createView(),
            'entities' => $employees
        ));
    }

    /**
    * @Route("/admin/manage/employees/remove/{id}", requirements={"id" = "\d+"}, name="admin.manage.employees.remove")
    */
    public function removeAction(Request $request, $id)
    {
        $employee = $this->getDoctrine()->getRepository('AppBundle:Employee')->find($id);
        if ($employee) {
            $employee->setRemoved(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.employees');
    }

    /**
    * @Route("/admin/manage/employees/edit/{id}", requirements={"id" = "\d+"}, name="admin.manage.employees.edit")
    */
    public function editAction(Request $request, $id)
    {
        $employee = $this->getDoctrine()->getRepository('AppBundle:Employee')->find($id);
        if (!$employee) {
            return $this->redirectToRoute('admin.manage.employees');
        }
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();
            return $this->redirectToRoute('admin.manage.employees');
        }
        return $this->render('booking/manage/employee.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
